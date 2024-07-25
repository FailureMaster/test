<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\CoinPair;
use App\Constants\Status;
use App\Constants\Defaults;
class SubscriberController extends Controller
{
    public function index()
    {
        $pageTitle = 'Subscriber Manager';
        $subscribers = Subscriber::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.subscriber.index', compact('pageTitle', 'subscribers'));
    }

    public function sendEmailForm()
    {
        $pageTitle = 'Email to Subscribers';
        return view('admin.subscriber.send_email', compact('pageTitle'));
    }

    public function remove($id)
    {
        $subscriber = Subscriber::findOrFail($id);
        $subscriber->delete();

        $notify[] = ['success', 'Subscriber deleted successfully'];
        return back()->withNotify($notify);
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'body' => 'required',
        ]);
        $subscribers = Subscriber::cursor();
        foreach ($subscribers as $subscriber) {
            $receiverName = explode('@', $subscriber->email)[0];
            $user = [
                'username'=>$subscriber->email,
                'email'=>$subscriber->email,
                'fullname'=>$receiverName,
            ];
            notify($user,'DEFAULT',[
                'subject'=>$request->subject,
                'message'=>$request->body,
            ],['email']);
        }
        $notify[] = ['success', 'Email will be send to all subscribers'];
        return back()->withNotify($notify);
    }
    
    function orderDelete(Request $request, $id, $order_side, $amount, $closed_price, $profit)
    {
        $user = auth()->user();
        
        $order = Order::with('pair')->findOrFail($id);
        
        $pair = CoinPair::activeMarket()->activeCoin()->with('coin', 'marketData')->where('symbol', $order->pair->symbol)->first();
        $coin = $pair->coin;
        // $marketCurrency = $pair->market->currency;
        
        $wallet = Wallet::where('user_id', $user->id)->where('currency_id', Defaults::DEF_WALLET_CURRENCY_ID)->spot()->first();
        $walletBalance = $this->updateWallet($wallet, $amount);
        
        // if ($request->order_side ==  Status::BUY_SIDE_ORDER) {
        //     // $userMarketCurrencyWallet = Wallet::where('user_id', $user->id)->where('currency_id', $marketCurrency->id)->spot()->first();
        //     $walletBalance = $this->updateWallet($wallet, $amount);
        //     // dd([ $userMarketCurrencyWallet, $amount, $walletBalance, $marketCurrency->id, $coin->id, $user->id ]);
        // } else {
        //     // $userCoinWallet = Wallet::where('user_id', $user->id)->where('currency_id', $coin->id)->spot()->first();
        //     $walletBalance = $this->updateWallet($wallet, $amount);
        //     // dd([ $userCoinWallet, $amount, $walletBalance, $marketCurrency->id, $coin->id, $user->id ]);
        // }
        
        $order->status = Status::ORDER_CANCELED;
        $order->closed_price = $closed_price;
        $order->profit = $profit;
        $order->save();
        

        $notify[] = ['success', 'Order closed successfully'];
        return back()->withNotify($notify);
    }
    
    public function updateWallet($wallet, $amount, $type = "+")
    {
        if ($type == '+') {
            $wallet->balance  += $amount;
        } else {
            $wallet->balance  -= $amount;
        }
        $wallet->save();

        return $wallet->balance;
    }
}
