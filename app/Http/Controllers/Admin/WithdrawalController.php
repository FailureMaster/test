<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WithdrawalController extends Controller
{
    public function pending(Request $request)
    {
        $pageTitle   = 'Pending Withdrawals';
        $withdrawals = $this->withdrawalData($request, 'pending');
        return view('admin.withdraw.withdrawals', compact('pageTitle', 'withdrawals'));
    }

    public function approved(Request $request)
    {
        $pageTitle   = 'Approved Withdrawals';
        $withdrawals = $this->withdrawalData($request, 'approved');
        return view('admin.withdraw.withdrawals', compact('pageTitle', 'withdrawals'));
    }

    public function rejected(Request $request)
    {
        $pageTitle   = 'Rejected Withdrawals';
        $withdrawals = $this->withdrawalData($request, 'rejected');
        return view('admin.withdraw.withdrawals', compact('pageTitle', 'withdrawals'));
    }

    public function log(Request $request)
    {
        $pageTitle      = 'Withdrawals Log';
        $withdrawalData = $this->withdrawalData($request, $scope = null, $summery = true);

        $withdrawals    = $withdrawalData['data'];
        $summery        = $withdrawalData['summery'];
        $successful     = $summery['successful'];
        $pending        = $summery['pending'];
        $rejected       = $summery['rejected'];
        return view('admin.withdraw.withdrawals', compact('pageTitle', 'withdrawals','successful','pending','rejected'));
    }

    protected function withdrawalData(Request $request, $scope = null, $summery = false){
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

        if ($scope) {
            $withdrawals = Withdrawal::$scope()->with(['user','method'])->whereHas('user');;
        }else{
            $withdrawals = Withdrawal::with(['user','method'])->where('withdrawals.status','!=',Status::PAYMENT_INITIATE)->whereHas('user');
        }

        if ($request->filled('status')) {
            $withdrawals = $deposits->where('deposits.status', $request->get('status'));
        }

        if ($request->filled('method_name')) {
            $methodName = $request->get('method_name');
            $withdrawals = $withdrawals->whereHas('method_name', function($query) use ($methodName) {
                $query->where('name', 'LIKE', "%{$methodName}%");
            });
        }

        if  ($request->filled('lead_code')) {
            $userID = $request->get('lead_code');
            $withdrawals = $withdrawals->whereHas('user', function($query) use ($userID) {
                $query->where('lead_code', $userID);
            });
        }

        if ($request->filled('user_name')) {
            $userName = $request->get('user_name');
            $withdrawals = $withdrawals->whereHas('user', function($query) use ($userName) {
                $query->where('firstname', 'LIKE', "%{$userName}%")->orWhere('lastname', 'LIKE', "%{$userName}%");
            });
        }

        if ($request->filled('user_email')) {
            $userEmail = $request->get('user_email');
            $withdrawals = $withdrawals->whereHas('user', function($query) use ($userEmail) {
                $query->where('email', 'LIKE', "%{$userEmail}%");
            });
        }

        $request     = request();

        if ($request->date) {
            $date        = explode('-', $request->date);
            $startDate   = Carbon::parse(trim($date[0]))->format('Y-m-d');
            $endDate     = @$date[1] ? Carbon::parse(trim(@$date[1]))->format('Y-m-d') : $startDate;
            $withdrawals = $withdrawals->whereDate('withdrawals.created_at', '>=', $startDate)->whereDate('withdrawals.created_at', '<=', $endDate);
        }

        if ($request->method) {
            $withdrawals = $withdrawals->where('method_id',$request->method);
        }

        if ($startDate && $endDate) {
            $withdrawals->whereBetween('withdrawals.created_at', [$startDate, $endDate]);
        }

        if (!$summery) {
            return $withdrawals->with(['user','method','wallet.currency'])->orderBy('id','desc')->paginate(getPaginate());
        }else{
            $summationQuery    = (clone $withdrawals)->join('currencies', 'withdrawals.currency', 'currencies.symbol');
            $successfulSummery = (clone $summationQuery)->where('withdrawals.status',Status::PAYMENT_SUCCESS)->sum(DB::raw('currencies.rate * withdrawals.amount'));
            $pendingSummery    = (clone $summationQuery)->where('withdrawals.status',Status::PAYMENT_PENDING)->sum(DB::raw('currencies.rate * withdrawals.amount'));
            $rejectedSummery   = (clone $summationQuery)->where('withdrawals.status',Status::PAYMENT_REJECT)->sum(DB::raw('currencies.rate * withdrawals.amount'));

            return [
                'data'    => $withdrawals->orderBy('id','desc')->paginate(getPaginate()),
                'summery' => [
                    'successful' => $successfulSummery,
                    'pending'    => $pendingSummery,
                    'rejected'   => $rejectedSummery,
                ]
            ];
        }
    }

    public function details($id)
    {
        $withdrawal = Withdrawal::where('id',$id)->where('status', '!=', Status::PAYMENT_INITIATE)->with(['user','method'])->firstOrFail();
        $pageTitle = isset($withdrawal->user) 
            ? $withdrawal->user->username . ' Withdraw Requested ' . showAmount($withdrawal->amount) . ' ' . gs('cur_text') 
            : 'User not found - Withdraw Requested ' . showAmount($withdrawal->amount) . ' ' . gs('cur_text');
        $details    = $withdrawal->withdraw_information ? json_encode($withdrawal->withdraw_information) : null;

        return view('admin.withdraw.detail', compact('pageTitle', 'withdrawal','details'));
    }

    public function approve(Request $request)
    {
        $request->validate(['id' => 'required|integer']);

        $withdraw                 = Withdrawal::where('id',$request->id)->where('status',Status::PAYMENT_PENDING)->with('user','wallet')->firstOrFail();
        $withdraw->status         = Status::PAYMENT_SUCCESS;
        $withdraw->admin_feedback = $request->details;
        $withdraw->save();

        notify($withdraw->user, 'WITHDRAW_APPROVE', [
            'method_name'     => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount'   => showAmount($withdraw->final_amount),
            'amount'          => showAmount($withdraw->amount),
            'charge'          => showAmount($withdraw->charge),
            'rate'            => showAmount($withdraw->rate),
            'trx'             => $withdraw->trx,
            'admin_details'   => $request->details,
            'wallet_name'     => @$withdraw->wallet->symbol
        ]);

        $notify[] = ['success', 'Withdrawal approved successfully'];
        return to_route('admin.withdraw.pending')->withNotify($notify);
    }


    public function reject(Request $request)
    {
        $request->validate(['id' => 'required|integer']);
        $withdraw = Withdrawal::where('id',$request->id)->where('status',Status::PAYMENT_PENDING)->with('user','wallet')->firstOrFail();

        $withdraw->status         = Status::PAYMENT_REJECT;
        $withdraw->admin_feedback = $request->details;
        $withdraw->save();

        $user = $withdraw->user;

        $wallet           = $withdraw->wallet;
        $wallet->balance += $withdraw->amount;
        $wallet->save();

        $transaction               = new Transaction();
        $transaction->user_id      = $withdraw->user_id;
        $transaction->amount       = $withdraw->amount;
        $transaction->post_balance = $wallet->balance;
        $transaction->charge       = 0;
        $transaction->trx_type     = '+';
        $transaction->remark       = 'withdraw_reject';
        $transaction->details      = showAmount($withdraw->amount) . ' ' . gs('cur_text') . ' Refunded from withdrawal rejection';
        $transaction->trx          = $withdraw->trx;
        $transaction->wallet_id    = $wallet->id;
        $transaction->save();

        notify($user, 'WITHDRAW_REJECT', [
            'method_name'     => $withdraw->method->name,
            'method_currency' => $withdraw->currency,
            'method_amount'   => showAmount($withdraw->final_amount),
            'amount'          => showAmount($withdraw->amount),
            'charge'          => showAmount($withdraw->charge),
            'rate'            => showAmount($withdraw->rate),
            'trx'             => $withdraw->trx,
            'post_balance'    => showAmount($user->balance),
            'admin_details'   => $request->details,
            'wallet_name'     => $wallet->symbol
        ]);

        $notify[] = ['success', 'Withdrawal rejected successfully'];
        return to_route('admin.withdraw.pending')->withNotify($notify);
    }

}
