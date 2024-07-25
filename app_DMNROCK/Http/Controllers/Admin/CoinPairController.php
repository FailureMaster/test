<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Models\Market;
use App\Models\CoinPair;
use App\Models\Currency;
use App\Models\MarketData;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CoinPairController extends Controller
{

    public function updateMarketDataForCoinPairs()
    {
        $coinPairs = CoinPair::all(); // Retrieve all CoinPairs
        foreach ($coinPairs as $coinPair) {
            $coin = Currency::where('id', $coinPair->coin_id)->active()->crypto()->first();
            $marketData = MarketData::where('pair_id', $coinPair->id)
                ->where('currency_id', 0)
                ->first();

            if (!$marketData) {
                $marketData = new MarketData();
                $marketData->pair_id = $coinPair->id;
            }
            $marketData->symbol = $coin ? $coin->symbol : "UNKNOWN";
            $marketData->save();
        }
    }

    public function updateMarketDataForCurrencies()
    {
        $currencies = Currency::all();
        foreach ($currencies as $currency) {
            if ($currency->type == Status::CRYPTO_CURRENCY) {
                $marketData = MarketData::where('pair_id', 0)->where('currency_id', $currency->id)->first();
                if (!$marketData) {
                    $marketData = new MarketData();
                    $marketData->currency_id = $currency->id;
                    $marketData->symbol = $currency->symbol;
                    $marketData->pair_id = 0;
                }
                $marketData->price = $currency->rate;
                $marketData->save();
            }
        }
    }

    public function list()
    {
        $this->updateMarketDataForCurrencies();
        $this->updateMarketDataForCoinPairs();
        $pageTitle = "Symbol";
        $pairs = CoinPair::with('market.currency', 'coin')
            ->searchable(['coin:name,symbol', 'market.currency:name,symbol'])
            ->select('coin_pairs.*')
            ->leftJoin('orders', function ($q) {
                $q->on('coin_pairs.id', 'orders.pair_id')->where('orders.status', Status::ORDER_OPEN);
            })
            ->selectRaw('SUM(CASE WHEN orders.order_side = ? THEN ((orders.amount-orders.filled_amount)*orders.rate) ELSE 0 END) as buy_liquidity', [Status::BUY_SIDE_ORDER])
            ->selectRaw('SUM(CASE WHEN orders.order_side = ?  THEN ((orders.amount-orders.filled_amount)*orders.rate) ELSE 0 END) as sell_liquidity', [Status::SELL_SIDE_ORDER])
            ->orderBy('is_default', 'DESC')
            ->filter(['market_id'])
            ->groupBy('coin_pairs.id')
            ->paginate(getPaginate());

        return view('admin.coin_pair.list', compact('pageTitle', 'pairs'));
    }
    public function create()
    {
        $pageTitle = "New Symbol";
        $markets = Market::with('currency')->active()->orderBy('name')->get();
        return view('admin.coin_pair.create', compact('pageTitle', 'markets'));
    }

    public function edit($id)
    {
        $pageTitle = "Edit Symbol";
        $coinPair = CoinPair::where('id', $id)->firstOrFail();
        $markets = Market::with('currency')->active()->get();
        return view('admin.coin_pair.create', compact('pageTitle', 'markets', 'coinPair'));
    }
    
    public function save(Request $request, $id = 0)
    {
        if ($id) {
            return $this->update($request, $id);
        }

        $request->validate([
            'market' => "required|integer",
            'coin' => "nullable|integer",
            'percent_charge_for_buy' => 'required|numeric|gte:0|lt:100',
            'percent_charge_for_sell' => 'required|numeric|gte:0',
            'listed_market_name' => 'required',
            'symbol' => 'required|string',
        ]);

        $market = Market::where('id', $request->market)
            ->whereHas('currency', function ($q) {
                $q->active();
            })
            ->active()
            ->first();

        if (!$market)
            return returnBack("Selected market is invalid.");

        if (strtoupper($market->currency->symbol) == strtoupper($request->symbol))
            return returnBack("Market currency & coin can't be the same.", "error", true);

        $symbol = strtoupper($request->symbol) . '_' . $market->currency->symbol;
        $alreadyExists = CoinPair::where('id', '!=', $id)->where('symbol', $symbol)->exists();

        if ($alreadyExists)
            return returnBack("Can't make one more coin pair with the same currency & market", "error", true);

        if ($id) {
            $message = "CoinPair updated successfully";
            $coinPair = CoinPair::findOrFail($id);
        } else {
            $message = "CoinPair saved successfully";
            $coinPair = new CoinPair();
            $coinPair->market_id = $request->market;
        }

        $coinPair->custom_symbol = $request->custom_symbol ? Status::YES : Status::NO;
        $coinPair->symbol = $symbol;
        $coinPair->percent_charge_for_sell = $request->percent_charge_for_sell;
        $coinPair->percent_charge_for_buy = $request->percent_charge_for_buy;
        $coinPair->listed_market_name = strtoupper($request->listed_market_name);

        if ($request->is_default) {
            CoinPair::where('id', '!=', $id)->where('is_default', Status::YES)->update(['is_default' => Status::NO]);
            $coinPair->is_default = Status::YES;
        } else {
            $defaultPair = CoinPair::where('id', '!=', $id)->where('is_default', Status::YES)->exists();
            if (!$defaultPair)
                return returnBack("Default coin pair is required.", "error", true);
            $coinPair->is_default = Status::NO;
        }

        $coinPair->save();

        $marketData = MarketData::where('pair_id', $coinPair->id)->where('currency_id', 0)->first();

        if (!$marketData) {
            $marketData = new MarketData();
            $marketData->pair_id = $coinPair->id;
        }
        $marketData->symbol = $coinPair->symbol;
        $marketData->save();

        return returnBack($message, 'success');
    }

    public function update($request, $id)
    {
        $request->validate([
            'percent_charge_for_sell' => 'required|numeric|gte:0',
            'percent_charge_for_buy' => 'required|numeric|gte:0',
            'level_percent' => 'required|numeric|gte:0',
            'listed_market_name' => 'required',
        ]);

        $coinPair = CoinPair::findOrFail($id);

        $coinPair->percent_charge_for_sell = $request->percent_charge_for_sell;
        $coinPair->percent_charge_for_buy = $request->percent_charge_for_buy;
        $coinPair->level_percent = $request->level_percent;
        $coinPair->listed_market_name = strtoupper($request->listed_market_name);

        $coinPair->save();

        return returnBack('Coin pair update successfully', 'success');
    }

    public function status($id)
    {
        return CoinPair::changeStatus($id);
    }
}
