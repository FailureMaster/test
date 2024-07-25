<?php

namespace App\Http\Controllers;

use App\Models\FavoriteSymbol;
use App\Models\Order;
use App\Models\Market;
use App\Models\Wallet;
use App\Models\CoinPair;
use App\Models\Currency;
use App\Constants\Status;
use App\Constants\Defaults;
use App\Http\Controllers\Controller;
use App\Models\FavoritePair;
use App\Models\GatewayCurrency;
use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;

class TradeController extends Controller
{
    public function fetchCoin()
    {
        $disabledSymbol = CoinPair::query()
            ->where('status', Status::DISABLE)
            ->pluck('symbol')
            ->toArray();
    
        $jsonData = file_get_contents(resource_path('data/data1.json'));
        $data = json_decode($jsonData, true);
    
        $filterData = function($items) use ($disabledSymbol) {
            return array_filter($items, function($key) use ($disabledSymbol) {
                return !in_array($key, $disabledSymbol);
            }, ARRAY_FILTER_USE_KEY);
        };
    
        foreach ($data as $category => $items) {
            $data[$category] = $filterData($items);
        }
    
        return response()->json($data);
    }

    public function addToFavorite(Request $request)
    {
        $request->validate([
            'coin' => 'required|string',
            'category' => 'required|string',
        ]);

        $userId = auth()->id();

        $existingFavorite = FavoriteSymbol::where('user_id', $userId)
                                    ->where('symbol', $request->coin)
                                    ->where('category', $request->category)
                                    ->first();

        if ($existingFavorite) {
            $existingFavorite->delete();
    
            return returnBack('Favorite removed successfully', 'success');
        } else {
            $favorite = new FavoriteSymbol();
            $favorite->symbol = $request->coin;
            $favorite->category = $request->category;
            $favorite->user_id = $userId;
            $favorite->save();

            return returnBack('Favorite added successfully', 'success');
        }
    }

    public function fetchUserFavorites(Request $request)
    {
        $favorites = FavoriteSymbol::where('user_id', auth()->id())->get();

        // $jsonData = file_get_contents(resource_path('data/data1.json'));
        // $marketData = json_decode($jsonData, true);

        // $userFavoriteData = [];
        // foreach ($favorites as $favorite) {
        //     $symbol = strtoupper($favorite->symbol);

        //     foreach ($marketData as $category => $symbols) {
        //         if (isset($symbols[$symbol])) {
        //             $item = $symbols[$symbol];
        //             $item['symbol'] = $symbol;
        //             $item['category'] = $category;
        //             $userFavoriteData[] = $item;
        //         }
        //     }
        // }

        return response()->json($favorites);
    }

    public function trade(Request $request)
    {
        $symbol = null;
        if ($request->has('symbolHIFHSRbBIKR1pDOisb7nMDFp6JsuVZv')) {
                   
                   $symbol = $request->input('symbolHIFHSRbBIKR1pDOisb7nMDFp6JsuVZv');
                   $parts = explode(':', $symbol);
                   $symbol = $parts[1];
        }

        $pair = CoinPair::active()->activeMarket()->activeCoin()->with('market', 'coin', 'marketData');
        if ($symbol) {
            $pair = $pair->where('symbol', $symbol)->first();
        } else {
            $pair = $pair->where('is_default', Status::YES)->first();
        }
        if (!$pair) {
            $notify[] = ['error', 'No pair found'];
            return back()->withNotify($notify);
        }
        
        $markets = Market::with('currency:id,name,symbol')->active()->get();
        $userId = auth()->id() ?? 0;
        $coinWallet = Wallet::where('user_id', $userId)->where('currency_id', $pair->coin->id)->spot()->first();
        
        $order_count = Order::query()
            ->where('status', Status::ORDER_OPEN)
            ->where('user_id', auth()->id())
            ->count();
            
        $marketCurrencyWallet = Wallet::where('user_id', $userId)->where('currency_id', Defaults::DEF_WALLET_CURRENCY_ID /* $pair->market->currency->id */)->spot()->first();
        
        $gateways = GatewayCurrency::where(function ($q) use ($pair) {
            $q->where('currency', @$pair->coin->symbol)->orWhere('currency', $pair->market->currency->symbol);
        })->whereHas('method', function ($gate) {
            $gate->where('status', Status::ENABLE);
        })->with('method:id,code,crypto')->get();
        $pageTitle = $pair->symbol;
        
        $requiredMarginTotal = $this->requiredMarginTotal();
        
        $currency = Currency::where('id', 4)->first();
        
        return view($this->activeTemplate . 'trade.index', compact('pageTitle', 'pair', 'markets', 'coinWallet', 'marketCurrencyWallet', 'gateways', 'order_count', 'requiredMarginTotal', 'currency'));
    }

    public function fetchUserBalance()
    {
        $userId = auth()->id() ?? 0;
        $marketCurrencyWallet = Wallet::where('user_id', $userId)->where('currency_id', Defaults::DEF_WALLET_CURRENCY_ID /* $pair->market->currency->id */)->spot()->first();

        return response()->json([
            'success' => true,
            'balance' => $marketCurrencyWallet->balance,
            'bonus' => $marketCurrencyWallet->bonus,
            'credit' => $marketCurrencyWallet->credit,
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }
    
    public function getCurrentPrice($type, $symbol)
    {
        $marketDataJson = File::get(base_path('resources/data/data.json'));
        $marketData = json_decode($marketDataJson);

        return response()->json([ 
            'current_price' => $marketData->{$type}->{$symbol} 
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }
    
    public function closeAllOrders(Request $request)
    {
        // dd([ $request->level, $request->equity ]);
        // if (empty($request->level) || empty($request->equity)) {
        //     return response()->json([
        //         'status' => 500,
        //         'message' => 'Invalid Payload!',
        //         'type' => 'INVALID_PAYLOAD'
        //     ], 500);
        // }
        
        // $orders = Order::query()
        //     ->where('status', Status::ORDER_OPEN)
        //     ->where('user_id', auth()->id());
            
        // if ($orders->count() == 0) {
        //     return response()->json([
        //         'status' => 200,
        //         'type' => 'NO_ORDERS',
        //         'message' => 'No orders'
        //     ], 200);
        // }
        
        // $order_closed = $orders->update([ 'status' => Status::ORDER_CANCELED ]);
            
        // return response()->json([
        //     'status' => 200,
        //     'type' => 'ALL_ORDERS_CLOSED',
        //     'message' => 'All open order(s) ('. $order_closed .' Orders) are now closed! Reason: Level is already below or equal to the 10% of the equity.'
        // ], 200);
    }

    public function history($symbol)
    {
        $pair = $this->findPair($symbol);

        if (!$pair) {
            return response()->json([
                'success' => false,
                'message' => "Coin Pair not found"
            ]);
        }
        $trades = Trade::where('pair_id', $pair->id)->orderBy('id', 'desc')->take(50)->get();
        return response()->json([
            'success' => true,
            'trades' => $trades
        ]);
    }
    
    public function orderList(Request $request, $symbol, $status)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'nullable|in:all,open,canceled,completed'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->all()
            ]);
        }

        $query = Order::with('pair')->where('user_id', auth()->id());

        // if ($request->status && $request->status != 'all') {
        //     $scope = $request->status;
        //     $query->$scope();
        // }
        
        if ($status == Status::ORDER_OPEN) {
            $query->where('status', Status::ORDER_OPEN);
            $query->orderBy('created_at', 'desc');
        } else {
            $query->where('status', Status::ORDER_CANCELED);
            $query->orderBy('updated_at', 'desc');
        }

        $orders = $query->orderBy('id', 'desc')->take(20)->get();
        
        $marketDataJson = File::get(base_path('resources/data/data.json'));
        $marketData = json_decode($marketDataJson);

        $wallet = Wallet::where('currency_id', 4)
            ->where('wallet_type', 1)
            ->where('user_id', auth()->id())
            ->first();

        return response()->json([
            'success' => true,
            'orders' => $orders,
            'marketData' => $marketData,
            'totalRequiredMargin' => $this->requiredMarginTotal(),
            'wallet' => $wallet,
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function fetchOrderProfit($id)
    {
        $order = Order::with('pair')
            ->where('id', $id)
            ->where('user_id', auth()->id())
            ->first();
        
        $marketDataJson = File::get(base_path('resources/data/data.json'));
        $marketData = json_decode($marketDataJson);

        return response()->json([
            'success' => true,
            'order' => $order,
            'marketData' => $marketData,
        ])->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function orderBook($symbol = null)
    {
        $pair = $this->findPair($symbol);

        if (!$pair) {
            return response()->json([
                'success' => false,
                'message' => "Coin Pair not found"
            ]);
        }

        $orderType = request()->order_type;
        $query = Order::open()->where('orders.pair_id', $pair->id)
            ->select('orders.*')
            ->leftJoin('trades', 'orders.id', 'trades.order_id')
            ->selectRaw("SUM(orders.amount) as total_amount")
            ->selectRaw("COUNT(DISTINCT orders.id) as total_order")
            ->selectRaw("COUNT(DISTINCT trades.id) as total_trade")
            ->selectRaw('MAX(CASE WHEN orders.user_id = ? THEN 1 ELSE 0 END)  AS has_my_order', [auth()->id()])
            ->groupBy('orders.rate')
            ->orderBy('orders.rate', 'DESC');

        if ($orderType == 'all' || $orderType == 'sell') {
            $sellSideOrders = (clone $query)->sellSideOrder()->take(15)->get();
        }
        if ($orderType == 'all' || $orderType == 'buy') {
            $buySideOrders = (clone $query)->buySideOrder()->take(15)->get();
        }

        return response()->json([
            'success' => true,
            'sell_side_orders' => @$sellSideOrders ?? [],
            'buy_side_orders' => @$buySideOrders ?? [],
        ]);
    }

    private function findPair($symbol = null)
    {
        $pair = CoinPair::active()->activeMarket()->activeCoin();
        if ($symbol) {
            $pair = $pair->where('symbol', $symbol)->first();
        } else {
            $pair = $pair->where('is_default', Status::YES)->first();
        }
        return $pair;
    }

    public function pairs()
    {

        $query = CoinPair::activeMarket()->activeCoin()->with('coin:name,id,symbol', 'market:id,name,currency_id', 'market.currency:id,symbol', 'marketData:id,pair_id,price,html_classes,percent_change_1h');

        if (request()->marketId) {
            $marketId = request()->marketId;
            $query->where(
                ($marketId == 'Stocks' ||
                    $marketId == 'FOREX' ||
                    $marketId == 'COMMODITY' ||
                    $marketId == 'INDEX' ||
                    $marketId == 'Crypto')
                ? 'type' : 'market_id',
                $marketId
            );
        }

        if (request()->search) {
            $query->where('symbol', 'Like', "%" . request()->search . "%");
        }

        $pairs = $query->orderBy('id', 'desc')->take(50)->get();
        $favoritePairId = FavoritePair::where('user_id', auth()->id() ?? 0)->pluck('pair_id')->toArray();

        return response()->json([
            'success' => true,
            'pairs' => $pairs,
            'favoritePairId' => $favoritePairId
        ]);
    }
    
    public function requiredMarginTotal()
    {
        return Order::where('user_id', auth()->id())->open()->sum('required_margin');
    }
}
