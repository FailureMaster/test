<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Defaults;
use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\SalesStatus\StoreRequest as SalesStatusStoreRequest;
use App\Models\Admin;
use App\Models\Comment;
use App\Models\Currency;
use App\Models\Deposit;
use App\Models\NotificationLog;
use App\Models\Order;
use App\Models\SalesStatus;
use App\Models\Trade;
use App\Models\Transaction;
use App\Models\User;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ManageUsersController extends Controller
{
    protected function getAdmins()
    {
        return Admin::whereNot('permission_group_id', 1)->whereNot('id', 2)->get();
    }

    public function userList(Request $request, $pageTitle, $userType, $history = 'clients')
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
        
        $perPage = $request->get('per_page', 25);

        $users = $this->userData($userType, $startDate, $endDate)->paginate($perPage);
        $admins = $this->getAdmins();
        $salesStatuses = SalesStatus::all();
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        if ($userType == 'active') {
            $leadSources = User::where('account_type', 'real')->whereNotNull('lead_source')->distinct()->pluck('lead_source');
        }else{
            $leadSources = User::where('account_type', 'demo')->whereNotNull('lead_source')->distinct()->pluck('lead_source');
        }
        $totalRecords = $users->total();
    
        return view('admin.users.list', compact('pageTitle', 'users', 'admins', 'salesStatuses', 'countries', 'history', 'totalRecords', 'perPage', 'leadSources'));
    }

    public function allUsers(Request $request)
    {
        $pageTitle = 'All Leads';
        return $this->userList($request, $pageTitle, 'inactive', 'leads', 'lead_source');
    }

    public function activeUsers(Request $request)
    {
        $pageTitle = 'Active Users';
        return $this->userList($request, $pageTitle, 'active');
    }

    public function bannedUsers(Request $request)
    {
        $pageTitle = 'Banned Users';
        return $this->userList($request, $pageTitle, 'banned');
    }

    public function emailUnverifiedUsers(Request $request)
    {
        $pageTitle = 'Email Unverified Users';
        return $this->userList($request, $pageTitle, 'emailUnverified');
    }

    public function kycUnverifiedUsers(Request $request)
    {
        $pageTitle = 'KYC Unverified Users';
        return $this->userList($request, $pageTitle, 'kycUnverified');
    }

    public function kycPendingUsers(Request $request)
    {
        $pageTitle = 'KYC Pending Users';
        return $this->userList($request, $pageTitle, 'kycPending');
    }

    public function emailVerifiedUsers(Request $request)
    {
        $pageTitle = 'Email Verified Users';
        return $this->userList($request, $pageTitle, 'emailVerified');
    }

    public function mobileUnverifiedUsers(Request $request)
    {
        $pageTitle = 'Mobile Unverified Users';
        return $this->userList($request, $pageTitle, 'mobileUnverified');
    }

    public function mobileVerifiedUsers(Request $request)
    {
        $pageTitle = 'Mobile Verified Users';
        return $this->userList($request, $pageTitle, 'mobileVerified');
    }

    protected function userData($scope = null, $startDate = null, $endDate = null)
    {
        if ($scope) {
            $users = User::$scope();
        } else {
            $users = User::query();
        }
        if (!can_access('access-all-users')) {
            if (can_access('manage-sales-leads')) {
                $users->whereDoesntHave('wallets', function ($query) {
                    $query->where('balance', '!=', 0);
                });
            } else if (can_access('manage-retention-leads')) {
                $users->whereHas('wallets', function ($query) {
                    $query->where('balance', '!=', 0);
                });
            } else {
                $users->where(['owner_id' => auth()->guard('admin')->user()->id]);
            }
        }

        if ($startDate && $endDate) {
            $users->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $users->with('owner')
            ->with('comments')
            ->when(request()->get('name'), function ($query, $name) {
                $query->where(function ($query) use ($name) {
                    $query->where('firstname', 'LIKE', "%{$name}%")
                          ->orWhere('lastname', 'LIKE', "%{$name}%");
                });
            })
            ->when(request()->get('mobile'), function ($query, $mobile) {
                $query->where('mobile', 'LIKE', "%{$mobile}%");
            })
            ->searchable([
                'id',
                'email',
                'firstname',
                'lastname',
                'mobile',
                'country_code',
                'account_type', 
                
            ])
            ->filter([
                'lead_code',
                'email',
                'sales_status',
                'owner_id',
                'country_code',
                'lead_source'
                
            ])
            ->orderBy('id', 'desc');
        
        // ->paginate(getPaginate());
    }


    public function detail($id)
    {
        $user = User::findOrFail($id);
        $previousUser = User::where('id', '>', $id)->orderBy('id', 'desc')->first() ?? null;
        $nextUser = User::where('id', '<', $id)->orderBy('id', 'desc')->first() ?? null;

        $pageTitle = 'User Detail - ' . $user->fullname;

        $widget = [];
        $widget['total_trade'] = Trade::where('trader_id', $user->id)->count();
        $widget['total_order'] = Trade::where('order_id', $user->id)->count();
        $widget['total_deposit'] = Deposit::where('user_id', $user->id)->where('status', Status::PAYMENT_SUCCESS)->count();
        $widget['total_transaction'] = Transaction::where('user_id', $user->id)->count();

        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $currencies = Currency::active()->get();

        $marketCurrencyWallet = Wallet::where('user_id', $user->id)->where('currency_id', Defaults::DEF_WALLET_CURRENCY_ID /* $pair->market->currency->id */)->spot()->first();

        $requiredMarginTotal = Order::where('user_id', $user->id)->open()->sum('required_margin');

        return view('admin.users.detail', compact('pageTitle', 'user', 'previousUser', 'nextUser', 'widget', 'countries', 'currencies', 'marketCurrencyWallet', 'requiredMarginTotal'));
    }


    public function kycDetails($id)
    {
        $pageTitle = 'KYC Details';
        $user = User::findOrFail($id);
        return view('admin.users.kyc_detail', compact('pageTitle', 'user'));
    }

    public function kycApprove($id)
    {
        $user = User::findOrFail($id);
        $user->kv = 1;
        $user->save();

        notify($user, 'KYC_APPROVE', []);

        $notify[] = ['success', 'KYC approved successfully'];
        return to_route('admin.users.kyc.pending')->withNotify($notify);
    }

    public function kycReject($id)
    {
        $user = User::findOrFail($id);
        foreach ($user->kyc_data as $kycData) {
            if ($kycData->type == 'file') {
                fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
            }
        }
        $user->kv = 0;
        $user->kyc_data = null;
        $user->save();

        notify($user, 'KYC_REJECT', []);

        $notify[] = ['success', 'KYC rejected successfully'];
        return to_route('admin.users.kyc.pending')->withNotify($notify);
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countryArray = (array) $countryData;
        $countries = implode(',', array_keys($countryArray));

        $countryCode = $request->country;
        $country = $countryData->$countryCode->country;
        $dialCode = $countryData->$countryCode->dial_code;

        $request->validate([
            'firstname' => 'required|string|max:40',
            'lastname' => 'required|string|max:40',
            'email' => 'required|email|string|max:40|unique:users,email,' . $user->id,
            'mobile' => 'required|string|max:40|unique:users,mobile,' . $user->id,
            'country' => 'required|in:' . $countries,
            'status' => 'nullable|in:NEW,CALLBACK,NA,UNDER_AGE,DENY_REGISTRATION,DEPOSIT,NOT_INTERESTED,VOICE_MAIL',
            'comment' => 'nullable|string|max:1024',
            'password' => 'sometimes',
        ]);

        Comment::create([
            'user_id' => $id,
            'comment' => $request->comment,
            'commented_by' => auth()->guard('admin')->user()->id,
        ]);

        // $user->comment = $request->comment;
        $user->sales_status = $request->status;
        $user->mobile = $dialCode . $request->mobile;
        $user->country_code = $countryCode;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->address = [
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => @$country,
        ];
        $user->lead_source = $request->source;
        $user->ev = $request->ev ? Status::VERIFIED : Status::UNVERIFIED;
        $user->sv = $request->sv ? Status::VERIFIED : Status::UNVERIFIED;
        $user->ts = $request->ts ? Status::ENABLE : Status::DISABLE;
        if (!$request->kv) {
            $user->kv = 0;
            if ($user->kyc_data) {
                foreach ($user->kyc_data as $kycData) {
                    if ($kycData->type == 'file') {
                        fileManager()->removeFile(getFilePath('verify') . '/' . $kycData->value);
                    }
                }
            }
            $user->kyc_data = null;
        } else {
            $user->kv = 1;
        }
        $user->save();

        $notify[] = ['success', 'User details updated successfully'];
        return back()->withNotify($notify);
    }

    public function addSubBalance(Request $request, $id)
    {
        $request->validate([
            'amount' => 'nullable|numeric|gt:0',
            'bonus' => 'nullable|numeric|gt:0',
            'credit' => 'nullable|numeric|gt:0',
            'wallet' => 'required|integer',
            'act' => 'required|in:add,sub',
            'remark' => 'required|string|max:255',
            'wallet_type' => 'required|in:' . implode(',', array_keys((array) gs('wallet_types')))
        ]);

        $user = User::findOrFail($id);
        $walletScope = $request->wallet_type;
        $wallet = Wallet::where('user_id', $user->id)->$walletScope()->where('currency_id', $request->wallet)->firstOrFail();

        $amount = $request->amount;
        $bonus = $request->bonus;
        $credit = $request->credit;
        $totalTransactionAmount = $amount + $bonus + $credit;
        $trx = getTrx();


        $transaction = new Transaction();

        if ($request->act == 'add') {

            $wallet->balance += $amount;
            $wallet->bonus += $bonus;
            $wallet->credit += $credit;
            $wallet->save();

            $transaction->trx_type = '+';
            $transaction->remark = 'balance_add';
            $notifyTemplate = 'BAL_ADD';

            $notify[] = ['success', gs('cur_sym') . $amount . ' added successfully'];
        } else {
            if ($amount > $wallet->balance) {
                $notify[] = ['error', $user->fullname . ' doesn\'t have sufficient balance.'];
                return back()->withNotify($notify);
            }

            $wallet->balance -= $amount;
            $wallet->bonus = max(0, $wallet->bonus - $bonus);
            $wallet->credit = max(0, $wallet->credit - $credit);
            $wallet->save();

            $transaction->trx_type = '-';
            $transaction->remark = 'balance_subtract';

            $notifyTemplate = 'BAL_SUB';
            $notify[] = ['success', gs('cur_sym') . $amount . ' subtracted successfully'];
        }

        $user->save();

        $transaction->user_id = $user->id;
        $transaction->wallet_id = $wallet->id;
        $transaction->amount = $totalTransactionAmount;
        $transaction->post_balance = $wallet->balance;
        $transaction->charge = 0;
        $transaction->trx = $trx;
        $transaction->details = $request->remark;
        $transaction->save();


        notify($user, $notifyTemplate, [
            'trx' => $trx,
            'amount' => showAmount($amount),
            'remark' => $request->remark,
            'post_balance' => showAmount($user->balance),
            'wallet_currency' => @$wallet->currency->symbol,
        ]);

        return back()->withNotify($notify);
    }

    public function login($id)
    {
        Auth::loginUsingId($id);
        return to_route('user.home');
    }

    public function status(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($user->status == Status::USER_ACTIVE) {
            $request->validate([
                'reason' => 'required|string|max:255'
            ]);
            $user->status = Status::USER_BAN;
            $user->ban_reason = $request->reason;
            $notify[] = ['success', 'User banned successfully'];
        } else {
            $user->status = Status::USER_ACTIVE;
            $user->ban_reason = null;
            $notify[] = ['success', 'User unbanned successfully'];
        }
        $user->save();
        return back()->withNotify($notify);
    }


    public function showNotificationSingleForm($id)
    {
        $user = User::findOrFail($id);
        $general = gs();
        if (!$general->en && !$general->sn) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.users.detail', $user->id)->withNotify($notify);
        }
        $pageTitle = 'Send Notification to ' . $user->fullname;
        return view('admin.users.notification_single', compact('pageTitle', 'user'));
    }

    public function sendNotificationSingle(Request $request, $id)
    {

        $request->validate([
            'message' => 'required|string',
            'subject' => 'required|string',
        ]);

        $user = User::findOrFail($id);
        notify($user, 'DEFAULT', [
            'subject' => $request->subject,
            'message' => $request->message,
        ]);
        $notify[] = ['success', 'Notification sent successfully'];
        return back()->withNotify($notify);
    }

    public function showNotificationAllForm()
    {
        $general = gs();
        if (!$general->en && !$general->sn) {
            $notify[] = ['warning', 'Notification options are disabled currently'];
            return to_route('admin.dashboard')->withNotify($notify);
        }
        $notifyToUser = User::notifyToUser();
        $users = User::active()->count();
        $pageTitle = 'Notification to Verified Users';
        return view('admin.users.notification_all', compact('pageTitle', 'users', 'notifyToUser'));
    }

    public function sendNotificationAll(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'message' => 'required',
            'subject' => 'required',
            'start' => 'required',
            'batch' => 'required',
            'being_sent_to' => 'required',
            'user' => 'required_if:being_sent_to,selectedUsers',
            'number_of_top_deposited_user' => 'required_if:being_sent_to,topDepositedUsers|integer|gte:0',
            'number_of_days' => 'required_if:being_sent_to,notLoginUsers|integer|gte:0',
        ], [
            'number_of_days.required_if' => "Number of days field is required",
            'number_of_top_deposited_user.required_if' => "Number of top deposited user field is required",
        ]);

        if ($validator->fails())
            return response()->json(['error' => $validator->errors()->all()]);

        $scope = $request->being_sent_to;
        $users = User::oldest()->active()->$scope()->skip($request->start)->limit($request->batch)->get();
        foreach ($users as $user) {
            notify($user, 'DEFAULT', [
                'subject' => $request->subject,
                'message' => $request->message,
            ]);
        }
        return response()->json([
            'total_sent' => $users->count(),
        ]);
    }

    public function notificationLog($id)
    {
        $user = User::findOrFail($id);
        $pageTitle = 'Notifications Sent to ' . $user->fullname;
        $logs = NotificationLog::where('user_id', $id)->with('user')->orderBy('id', 'desc')->paginate(getPaginate());
        return view('admin.reports.notification_history', compact('pageTitle', 'logs', 'user'));
    }

    public function list()
    {
        $query = User::active();

        if (request()->search) {
            $query->where(function ($q) {
                $q->where('email', 'like', '%' . request()->search . '%');
            });
        }
        $users = $query->orderBy('id', 'desc')->paginate(getPaginate());
        return response()->json([
            'success' => true,
            'users' => $users,
            'more' => $users->hasMorePages()
        ]);
    }
    public function toggleFavorite($id)
    {
        $user = User::findOrFail($id);
        $user->favorite = !$user->favorite;
        $user->save();
        return back();
    }
    public function toggleType($id)
    {
        $user = User::findOrFail($id);

        if ($user->account_type == 'demo') {
            $user->balance = 0;
            $user->save();

            Wallet::where('user_id', $user->id)->update(['balance' => $user->balance]);
        }

        $user->account_type = $user->account_type == 'demo' ? 'real' : 'demo';
        $user->save();
        return back();
    }
    public function updateComment(Request $request, $id)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'comment' => 'required|string|max:1024',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $user = User::find($id);
        if (!$user) {
            $notify[] = ['error', 'User not found'];
            return back()->withNotify($notify);
        }

        // Update the user's comment
        $user->comment = $request->comment;
        $user->save();

        $notify[] = ['success', 'Comment updated successfully'];
        return back()->withNotify($notify);
    }

    public function updateOwner(Request $request, $id)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'owner' => [
                'required',
                function ($attribute, $value, $fail) {
                    if ($value != 0 && !Admin::where('id', $value)->exists()) {
                        $fail('Selected owner not found');
                    }
                },
            ],
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $owner = Admin::find($request->owner);
        if (!$owner && $request->owner != 0) {
            $notify[] = ['error', 'Selected owner not found'];
            return back()->withNotify($notify);
        }
        $user = User::find($id);
        if (!$user) {
            $notify[] = ['error', 'User not found'];
            return back()->withNotify($notify);
        }

        // Update the user's comment
        $user->owner_id = $request->owner == 0 ? null : $owner->id;
        $user->save();

        $notify[] = ['success', 'User assigned to owner' . ($owner ? (' ' . $owner->name) : '')];
        return back()->withNotify($notify);
    }

    public function updateSalesStatus(Request $request, $id)
    {

        $salesStatuses = SalesStatus::all()
            ->pluck('name')
            ->toArray();

        $statusList = implode(',', $salesStatuses);

        // 2. Validation
        $validatorSettings = [
            'status' => 'required|in:' . $statusList
        ];
        $validator = Validator::make($request->all(), $validatorSettings);
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $validatedData = $request->validate($validatorSettings);
        $user = User::find($id);
        if (!$user) {
            $notify[] = ['error', 'User not found'];
            return back()->withNotify($notify);
        }

        $user->sales_status = $validatedData['status'];
        $user->save();

        $notify[] = ['success', 'Sales status updated successfully!'];
        return back()->withNotify($notify);
    }

    public function create()
    {
        $pageTitle = 'New Lead';
        $info       = json_decode(json_encode(getIpInfo()), true);
        $mobileCode = @implode(',', $info['code']);
        $countries  = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.users.create', compact('pageTitle', 'mobileCode', 'countries'));
    }

    public function store(StoreRequest $request)
    {
        $user = DB::transaction(
            function () use ($request) {
                $user = User::create(
                    array_merge(
                        [
                            'password' => Hash::make('123456'),
                            'mobile' => $request->get('mobile_code') . $request->get('mobile'),
                            'address' => [
                                'address' => '',
                                'state' => '',
                                'zip' => '',
                                'country' => $request->get('country') ?? null,
                                'city' => ''
                            ],
                            'ev' => 1,
                            'sv' => 1,
                            'kv' => 1,
                            'profile_complete' => 1,
                            'account_type' => $request->account_type,
                            'lead_source' => $request->source ?? 'No source',
                            'owner_id' => 19
                        ],
                        $request->safe()->except(['mobile_code', 'country'])
                    )
                );

                return $user;
            }
        );

        if ($user->wasRecentlyCreated) {
            $currencies = Currency::active()
                ->leftJoin('wallets', function ($q) use ($user) {
                    $q->on('currencies.id', '=', 'wallets.currency_id')->where('user_id', $user->id);
                })
                ->whereNull('wallets.currency_id')
                ->select('currencies.*')
                ->get();

            $wallets = [];
            $now = now();
            $walletTypes = gs('wallet_types');

            foreach ($currencies as $currency) {
                foreach ($walletTypes as $walletType) {
                    $wallets[] = [
                        'user_id' => $user->id,
                        'currency_id' => $currency->id,
                        'balance' => allowsDemoAccount() ? 1000 : 0,
                        'wallet_type' => $walletType->type_value,
                        'created_at' => $now,
                        'updated_at' => $now
                    ];
                }
            }

            if (count($wallets)) {
                DB::table('wallets')->insert($wallets);
            }
        }

        return returnBack('Lead created successfully!', 'success');
    }

    public function destroy(User $user)
    {
        DB::transaction(
            function () use ($user) {
                $user->delete();
            }
        );

        return returnBack('User delete successfully', 'success');
    }

    public function importView()
    {
        $pageTitle = 'Import Leads';
    
        return view('admin.users.import_view', compact('pageTitle'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'filepond' => 'required|mimes:csv,txt'
        ]);

        $path = $request->file('filepond')->getRealPath();
        $data = array_map('str_getcsv', file($path));
        
        $data = array_slice($data, 1); 
        $columnNames    = ['First Name', 'Last Name', 'Email', 'Mobile', 'Country Code', 'Account Type', 'Lead Source'];
        $errors         = [];
        $rowNumber      = 2;

        foreach ($data as $row) {
            if (count($row) < 7) {
                $errors[] = "Row $rowNumber: Missing data in one or more columns.";
            } else {
                foreach ($row as $key => $value) {
                    if (empty($value)) {
                        $errors[] = "Row $rowNumber: Missing data in column '{$columnNames[$key]}'.";
                    }
                }
            }
            $rowNumber++;
        }

        if (!empty($errors)) {
            return response()->json(['Success' => false, 'errors' => $errors]);
        }

        foreach ($data as $row) {
            User::create([
                'firstname'         => $row[0],
                'lastname'          => $row[1],
                'email'             => $row[2],
                'mobile'            => $row[3],
                'country_code'      => $row[4],
                'account_type'      => $row[5],
                'lead_source'       => $row[6],
            ]);
        }

        return response()->json(['Success' => true, 'rowCount' => count($data)]);
    }

    public function export()
    {
        $filename = 'leads-template.csv';

        if (!Storage::disk('public')->exists($filename)) {
            abort(404);
        }

        return response()->download(storage_path('app/public/' . $filename), $filename);
    }

    public function salesStatus()
    {
        $pageTitle = 'Sales Statutes';
        $salesStatuses = SalesStatus::all();
    
        return view('admin.users.sales_status.index', compact('pageTitle', 'salesStatuses')); 
    }

    public function salesStatusCreateView()
    {
        $pageTitle = 'Create Sales Status';
    
        return view('admin.users.sales_status.create', compact('pageTitle')); 
    }

    public function salesStatusStore(SalesStatusStoreRequest $request)
    {
        DB::transaction(
            function () use ($request) {
                SalesStatus::create($request->validated());
            }
        );

        return returnBack('Sales status created successfully!', 'success');
    }

    public function salesStatusDelete(SalesStatus $status)
    {
        DB::transaction(
            function () use ($status) {
                $status->delete();
            }
        );

        return returnBack('Sales status deleted successfully', 'success');
    }
    
    public function bulkRecordUpdate(Request $request)
    {
        // Retrieve the validated data
        $data = $request->only('owner_id', 'sales_status', 'account_type', 'selected_ids');
        
        // Prepare the update data
        $updateData = [];
        if ($data['owner_id'] !== null) {
            $updateData['owner_id'] = $data['owner_id'];
        }
        if ($data['sales_status'] !== null) {
            $updateData['sales_status'] = $data['sales_status'];
        }
        if ($data['account_type'] !== null) {
            $updateData['account_type'] = $data['account_type'];
        }

        // Perform the bulk update
        User::whereIn('id', $data['selected_ids'])->update($updateData);

        // Return a success response
        return returnBack('Bulk update finished', 'success');
    }



    public function showComment(User $user){
        dd($user);
    }
}