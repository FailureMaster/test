<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Gateway\PaymentController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepositController extends Controller
{
    public function pending(Request $request)
    {
        $pageTitle = 'Pending Deposits';
        $deposits  = $this->depositData($request, 'pending');
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function approved(Request $request)
    {
        $pageTitle = 'Approved Deposits';
        $deposits  = $this->depositData($request, 'approved');
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function successful(Request $request)
    {
        $pageTitle = 'Successful Deposits';
        $deposits  = $this->depositData($request, 'successful');
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function rejected(Request $request)
    {
        $pageTitle = 'Rejected Deposits';
        $deposits  = $this->depositData($request, 'rejected');
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function initiated(Request $request)
    {
        $pageTitle = 'Initiated Deposits';
        $deposits  = $this->depositData($request, 'initiated');
        return view('admin.deposit.log', compact('pageTitle', 'deposits'));
    }

    public function deposit(Request $request)
    {
        $pageTitle   = 'Deposit History';
        $depositData = $this->depositData($request, $scope = null, $summery = true);
        $deposits    = $depositData['data'];
        $summery     = $depositData['summery'];
        $successful  = $summery['successful'];
        $pending     = $summery['pending'];
        $rejected    = $summery['rejected'];
        $initiated   = $summery['initiated'];
        return view('admin.deposit.log', compact('pageTitle', 'deposits', 'successful', 'pending', 'rejected', 'initiated'));
    }

    protected function depositData(Request $request, $scope = null, $summery = false)
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

        if ($scope) {
            $deposits = Deposit::$scope()->with(['user', 'gateway', 'currency','wallet.currency'])->whereHas('user');
        } else {
            $deposits = Deposit::with(['user', 'gateway', 'currency','wallet.currency'])->whereHas('user');
        }

        if ($request->filled('status')) {
            $deposits = $deposits->where('deposits.status', $request->get('status'));
        }

        if ($request->filled('gateway_name')) {
            $gatewayName = $request->get('gateway_name');
            $deposits = $deposits->whereHas('gateway', function($query) use ($gatewayName) {
                $query->where('name', 'LIKE', "%{$gatewayName}%");
            });
        }

        if  ($request->filled('lead_code')) {
            $userID = $request->get('lead_code');
            $deposits = $deposits->whereHas('user', function($query) use ($userID) {
                $query->where('lead_code', $userID);
            });
        }

        if ($request->filled('user_name')) {
            $userName = $request->get('user_name');
            $deposits = $deposits->whereHas('user', function($query) use ($userName) {
                $query->where('firstname', 'LIKE', "%{$userName}%")->orWhere('lastname', 'LIKE', "%{$userName}%");
            });
        }

        if ($request->filled('user_email')) {
            $userEmail = $request->get('user_email');
            $deposits = $deposits->whereHas('user', function($query) use ($userEmail) {
                $query->where('email', 'LIKE', "%{$userEmail}%");
            });
        }

        $request  = request();

        if ($request->date) {
            $date      = explode('-', $request->date);
            $startDate = Carbon::parse(trim($date[0]))->format('Y-m-d');
            $endDate   = @$date[1] ? Carbon::parse(trim(@$date[1]))->format('Y-m-d') : $startDate;
            $deposits  = $deposits->whereDate('deposits.created_at', '>=', $startDate)->whereDate('deposits.created_at', '<=', $endDate);
        }

        //vai method
        if ($request->method) {
            $method   = Gateway::where('alias', $request->method)->firstOrFail();
            $deposits = $deposits->where('method_code', $method->code);
        }

        if ($startDate && $endDate) {
            $deposits->whereBetween('deposits.created_at', [$startDate, $endDate]);
        }

        if (!$summery) {
            return $deposits->orderBy('id', 'desc')->paginate(getPaginate());
        } else {

            $summationQuery    = (clone $deposits)->join('currencies', 'deposits.currency_id', 'currencies.id');
            $successfulSummery = (clone $summationQuery)->where('deposits.status', Status::PAYMENT_SUCCESS)->sum(DB::raw('currencies.rate * deposits.amount'));
            $pendingSummery    = (clone $summationQuery)->where('deposits.status', Status::PAYMENT_PENDING)->sum(DB::raw('currencies.rate * deposits.amount'));
            $rejectedSummery   = (clone $summationQuery)->where('deposits.status', Status::PAYMENT_REJECT)->sum(DB::raw('currencies.rate * deposits.amount'));
            $initiatedSummery  = (clone $summationQuery)->where('deposits.status', Status::PAYMENT_INITIATE)->sum(DB::raw('currencies.rate * deposits.amount'));

            return [
                'data'    => $deposits->orderBy('id', 'desc')->paginate(getPaginate()),
                'summery' => [
                    'successful' => $successfulSummery,
                    'pending'    => $pendingSummery,
                    'rejected'   => $rejectedSummery,
                    'initiated'  => $initiatedSummery,
                ]
            ];

        }
    }
    public function details($id)
    {
        $deposit   = Deposit::where('id', $id)->with(['user', 'gateway'])->firstOrFail();
        $userFullname = optional($deposit->user)->fullname;
        $pageTitle = $userFullname ? $userFullname . ' requested ' . showAmount($deposit->amount) . ' ' . gs('cur_text') : 'Deposit Details';
        $details   = ($deposit->detail != null) ? json_encode($deposit->detail) : null;
        return view('admin.deposit.detail', compact('pageTitle', 'deposit', 'details'));
    }


    public function approve($id)
    {
        $deposit = Deposit::where('id', $id)->where('status', Status::PAYMENT_PENDING)->firstOrFail();

        PaymentController::userDataUpdate($deposit, true);

        $notify[] = ['success', 'Deposit request approved successfully'];

        return to_route('admin.deposit.pending')->withNotify($notify);
    }

    public function reject(Request $request)
    {
        $request->validate([
            'id'      => 'required|integer',
            'message' => 'required|string|max:255'
        ]);

        $deposit = Deposit::where('id', $request->id)->where('status', Status::PAYMENT_PENDING)->firstOrFail();

        $deposit->admin_feedback = $request->message;
        $deposit->status         = Status::PAYMENT_REJECT;
        $deposit->save();

        notify($deposit->user, 'DEPOSIT_REJECT', [
            'method_name'       => $deposit->gatewayCurrency()->name,
            'method_currency'   => $deposit->method_currency,
            'method_amount'     => showAmount($deposit->final_amo),
            'amount'            => showAmount($deposit->amount),
            'charge'            => showAmount($deposit->charge),
            'rate'              => showAmount($deposit->rate),
            'trx'               => $deposit->trx,
            'rejection_message' => $request->message,
            'wallet_name'       => @$deposit->wallet->currency->symbol
        ]);

        $notify[] = ['success', 'Deposit request rejected successfully'];
        return  to_route('admin.deposit.pending')->withNotify($notify);
    }
}
