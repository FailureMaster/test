<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\NotificationLog;
use App\Models\Transaction;
use App\Models\UserLogin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function transaction(Request $request)
    {
        $pageTitle    = 'Transaction Logs';
        $remarks      = Transaction::distinct('remark')->orderBy('remark')->get('remark');
        $transactions = Transaction::with([
            'wallet.currency',
            'user'
        ])
        ->whereNull('hid_at')
        ->when($request->get('lead_code'), function ($query) use ($request) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('lead_code', $request->lead_code);
            });
        })
        ->when(request()->get('name'), function ($query) use ($request) {
            $names = explode(' ', $request->get('name'));
            $query->whereHas('user', function ($query) use ($names) {
                foreach ($names as $name) {
                    $query->where(function ($query) use ($name) {
                        $query->where('firstname', 'LIKE', "%{$name}%")
                              ->orWhere('lastname', 'LIKE', "%{$name}%");
                    });
                }
            });
        })
        ->searchable(['trx', 'user:username'])
        ->filter([
            'user:email',
            'user:mobile',
            'trx_type',
            'trx',
            'remark',
            'wallet.currency:symbol'
        ])
        ->dateFilter()
        ->orderBy('id', 'desc')
        ->with('user')
        ->paginate(getPaginate());

        $currencies   = Currency::active()->rankOrdering()->get();
        return view('admin.reports.transactions', compact('pageTitle', 'transactions', 'remarks', 'currencies'));
    }

    public function loginHistory(Request $request)
    {
        $pageTitle = 'User Login History';

        $loginLogs = UserLogin::with('user');

        if ($request->filled('ip')) {
            $loginLogs = $loginLogs->where('user_ip', $request->get('ip'));
        }

        if ($request->filled('browser')) {
            $loginLogs = $loginLogs->where('browser', $request->get('browser'));
        }

        if ($request->filled('lead_code')) {
            $loginLogs = $loginLogs->whereHas('user', function ($query) use ($request) {
                $query->where('lead_code', $request->get('lead_code'));
            });
        }

        if ($request->filled('user_name')) {
            $userName = $request->get('user_name');
            $loginLogs = $loginLogs->whereHas('user', function ($query) use ($userName) {
                $query->where('name', 'LIKE', "%$userName%");
            });
        }

        $loginLogs = $loginLogs->orderBy('id', 'desc')->paginate(getPaginate());
    
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs'));
    }

    public function loginIpHistory($ip)
    {
        $pageTitle = 'Login by - ' . $ip;
        $loginLogs = UserLogin::where('user_ip', $ip)->orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        return view('admin.reports.logins', compact('pageTitle', 'loginLogs', 'ip'));
    }

    public function notificationHistory(Request $request)
    {
        $pageTitle = 'Notification History';
        $logs      = NotificationLog::orderBy('id', 'desc')->searchable(['user:username'])->with('user')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle', 'logs'));
    }

    public function emailDetails($id)
    {
        $pageTitle = 'Email Details';
        $email     = NotificationLog::findOrFail($id);
        return view('admin.reports.email_details', compact('pageTitle', 'email'));
    }
    
    public function hideTransaction(Transaction $transaction)
    {
        DB::transaction(
            function () use ($transaction) {
                $transaction->hid_at = now();
                $transaction->save();
            }
        );

        return returnBack('Transaction hide successfully', 'success');
    }
}
