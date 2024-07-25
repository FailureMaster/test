<?php

namespace App\Http\Controllers\DIY;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\UpdateRequest;
use App\Models\Market;
use App\Models\Order;
use App\Models\Trade;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;

class OrderController extends Controller
{
    public function open()
    {
        $pageTitle = "Open Order";
        $orders    = $this->orderData('open');
        return view('diy.order.list', compact('pageTitle', 'orders'));
    }

    public function close()
    {
        $pageTitle = "Close Order";
        $orders    = $this->orderData('canceled');
        return view('diy.order.list', compact('pageTitle', 'orders'));
    }

    public function history()
    {
        $pageTitle = "Order History";
        $orders    = $this->orderData();
        return view('diy.order.list', compact('pageTitle', 'orders'));
    }

    protected function orderData($scope = null)
    {
        $query = Order::filter(['order_side', 'user_id', 'status'])
            ->searchable(['id', 'pair:symbol', 'pair.coin:symbol', 'pair.market.currency:symbol'])
            ->with('pair', 'pair.coin', 'pair.market.currency', 'user')
            ->orderBy('id', 'desc');
        if ($scope) {
            $query->$scope();
        }
        return $query->paginate(getPaginate());
    }

    public function tradeHistory()
    {
        $pageTitle = "Trade History";
        $trades    = Trade::filter(['trade_side', 'trader_id'])
            ->whereHas('order', function ($query) {
                $query->where('status', Status::ORDER_CANCELED);
            })
            ->searchable(['order.pair:symbol', 'order.pair.coin:symbol', 'order.pair.market.currency:symbol'])
            ->with('order.pair', 'order.pair.coin', 'order.pair.market.currency')
            ->orderBy('id', 'desc')
            ->paginate(getPaginate());
        return view('diy.order.trade_history', compact('pageTitle', 'trades'));
    }

    public function edit(Order $order): View
    {
        $pageTitle = "Edit Open Order";
        $markets = Market::with('currency')
            ->active()
            ->get();

        $marketDataJson = File::get(base_path('resources/data/data.json'));
        $marketData = json_decode($marketDataJson);
    
        return view('diy.order.edit', compact('markets', 'order', 'pageTitle', 'marketData'));
    }

    public function update(UpdateRequest $request, Order $order)
    {
        DB::transaction(
            function () use ($request, $order) {
                $order->update($request->validated());
            }
        );

        return returnBack('Open price updated successfully', 'success');
    }

    public function destroy(Order $order)
    {
        DB::transaction(
            function () use ($order) {
                $order->delete();
            }
        );

        return returnBack('Open price delete successfully', 'success');
    }

    public function fetchMarketData() {
        $marketDataJson = File::get(base_path('resources/data/data.json'));
        $marketData = json_decode($marketDataJson);

        return response()->json($marketData);
    }
}
