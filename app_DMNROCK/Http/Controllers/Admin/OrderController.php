<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\UpdateRequest;
use App\Models\Market;
use App\Models\Order;
use App\Models\Trade;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class OrderController extends Controller
{
    public function open(Request $request)
    {
        $pageTitle = "Open Order";
        $orders    = $this->orderData($request, 'open');
        return view('admin.order.list', compact('pageTitle', 'orders'));
    }

    public function close(Request $request)
    {
        $pageTitle = "Close Order";
        $orders    = $this->orderData($request, 'canceled');
        return view('admin.order.list', compact('pageTitle', 'orders'));
    }

    public function history(Request $request)
    {
        $pageTitle = "Order History";
        $orders    = $this->orderData($request);
        return view('admin.order.list', compact('pageTitle', 'orders'));
    }

    protected function orderData(Request $request, $scope = null)
    {
        $filter = $request->get('filter');

        if ($request->get('customfilter')) {
            $filter = 'custom';
        }
    
        $startDate = null;
        $endDate = null;

        switch ($filter) {
            case 'today':
                $startDate = Carbon::today();
                $endDate = Carbon::today()->endOfDay();
                break;
            case 'yesterday':
                $startDate = Carbon::yesterday();
                $endDate = Carbon::yesterday()->endOfDay();
                break;
            case 'this_week':
                $startDate = Carbon::now()->startOfWeek();
                $endDate = Carbon::now()->endOfWeek();
                break;
            case 'last_week':
                $startDate = Carbon::now()->subWeek()->startOfWeek();
                $endDate = Carbon::now()->subWeek()->endOfWeek();
                break;
            case 'this_month':
                $startDate = Carbon::now()->startOfMonth();
                $endDate = Carbon::now()->endOfMonth();
                break;
            case 'last_month':
                $startDate = Carbon::now()->subMonth()->startOfMonth();
                $endDate = Carbon::now()->subMonth()->endOfMonth();
                break;
            case 'custom':
                $date = explode('-', $request->get('customfilter'));
                $startDate = Carbon::parse(trim($date[0]))->format('Y-m-d');
                $endDate = @$date[1] ? Carbon::parse(trim(@$date[1]))->format('Y-m-d') : $startDate;
                break;
        }

        $query = Order::filter(['order_side', 'user_id', 'status'])
            ->searchable(['id', 'pair:symbol', 'pair.coin:symbol', 'pair.market.currency:symbol'])
            ->with('pair', 'pair.coin', 'pair.market.currency')
            ->orderBy('id', 'desc');

        if ($scope) {
            $query->$scope();
        }

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
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
        return view('admin.order.trade_history', compact('pageTitle', 'trades'));
    }

    public function edit(Order $order): View
    {
        $pageTitle = "Edit Open Order";
        $markets = Market::with('currency')
            ->active()
            ->get();
    
        return view('admin.order.edit', compact('markets', 'order', 'pageTitle'));
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
