<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\PermissionGroup;
use Illuminate\Support\Facades\DB;


class PermissionGroupController extends Controller
{
  // This is Controller for PermissionGroup
  public function index()
  {
    // This function opens View with list of all permission groups
    $pageTitle = 'Permission Groups';

    if (auth()->guard('admin')->user()->id != 1) {
      $groups = PermissionGroup::where('name', '!=', 'Administrator')->get();
    } else {
      $groups = PermissionGroup::all();
    }

    return view('admin.manage_admins.permission_group.index', compact('groups', 'pageTitle'));
  }

  public function getPermissionCollection()
  {
    // This function returns default permissions collection
    $group[1] = 'Post Management';
    $collect = collect([
      [
        'name' => 'manage-order',
        'label' => 'Manage Order',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'permission_groups',
        'label' => 'permission_groups',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'manage_admins',
        'label' => 'manage_admins',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'manage-currency',
        'label' => 'Manage Currency',
        'group' => $group[1],
        'value' => false,
      ],
    //   [
    //     'name' => 'manage-market',
    //     'label' => 'Manage Market',
    //     'group' => $group[1],
    //     'value' => false,
    //   ],
    //   [
    //     'name' => 'manage-coin-pair',
    //     'label' => 'Manage Coin Pair',
    //     'group' => $group[1],
    //     'value' => false,
    //   ],
      [
        'name' => 'manage-users',
        'label' => 'Manage Users',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'access-all-users',
        'label' => 'Access All Users',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'manage-sales-leads',
        'label' => 'Manage Sales Leads',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'change-user-type',
        'label' => 'Change User Type',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'add-remove-user-balance',
        'label' => 'Add/Remove User Balance',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'add-symbol',
        'label' => 'Add Symbol',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'delete-user',
        'label' => 'Delete User',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'payment-gateways',
        'label' => 'Payment Gateways',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'deposits',
        'label' => 'Deposits',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'withdraw',
        'label' => 'Withdrawals',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'support-ticket',
        'label' => 'Support Ticket',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'report',
        'label' => 'Report',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'general-setting',
        'label' => 'General Setting',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'cron-job-setting',
        'label' => 'Cron Job Setting',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'system-configuration',
        'label' => 'System Configuration',
        'group' => $group[1],
        'value' => false,
      ],
    //   [
    //     'name' => 'charge-setting',
    //     'label' => 'Charge Setting',
    //     'group' => $group[1],
    //     'value' => false,
    //   ],
      [
        'name' => 'wallet-setting',
        'label' => 'Wallet Setting',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'logo-favicon',
        'label' => 'Logo & Favicon',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'extensions',
        'label' => 'Extensions',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'kyc-setting',
        'label' => 'KYC Setting',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'notification-setting',
        'label' => 'Notification Setting',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'manage-section',
        'label' => 'Manage Section',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'maintenance-mode',
        'label' => 'Maintenance Mode',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'gdpr-cookie',
        'label' => 'GDPR Cookie',
        'group' => $group[1],
        'value' => false,
      ],
    //   [
    //     'name' => 'system',
    //     'label' => 'System',
    //     'group' => $group[1],
    //     'value' => false,
    //   ],
    //   [
    //     'name' => 'custom-css',
    //     'label' => 'Custom CSS',
    //     'group' => $group[1],
    //     'value' => false,
    //   ],
      [
        'name' => 'delete-admin',
        'label' => 'Delete Admin',
        'group' => $group[1],
        'value' => false,
      ],
      [
        'name' => 'delete-group',
        'label' => 'Delete Group',
        'group' => $group[1],
        'value' => false,
      ],
    ]);
    return $collect;
  }

  public function create()
  {
    // This function opens the View for Create Permission Group page
    // with defaule permissions in the form fields
    $pageTitle = 'Create User Group';
    $permission_collect = $this->getPermissionCollection();
    return view('admin.manage_admins.permission_group.create', compact('permission_collect', 'pageTitle'));
  }

  public function store(Request $request)
  {
    // This function actually Creates PermissionGroup (meaning, user group)
    // with the permissions set from $request
    $this->validate($request, [
      'name' => 'required',
    ]);
    try {
      if (!$request->has('permission')) {
        $request['permission'] = [];
      }
      $permission = $this->getPermissionCollection()->map(function ($item, $key) use ($request) {
        $value = array_key_exists($item['name'], $request->permission) ? true : false;
        $item['value'] = $value;
        return $item;
      });
      $group = new PermissionGroup();
      $group->name = $request->name;
      $group->status = $request->has('status') ? 1 : 0;
      $group->permission = $permission->toJson();
      $group->save();
      return back()->with('success', 'Group Create Successful');
    } catch (\Exception $e) {
      return back()->with('alert', $e->getMessage());
    }
  }

  public function edit($id)
  {
    $pageTitle = 'User Group edit';
    $group = PermissionGroup::findOrFail($id);
    $permission = $this->getPermissionCollection()->map(function ($item, $key) use ($group) {
      $value = false;
      if ($per = $group->permissions()->where('name', $item['name'])->first()) {
        $value = $per['value'];
      };
      $item['value'] = $value;
      return $item;
    });

    if (auth()->guard('admin')->user()->id != 1) {
        $permission = $permission->filter(function ($permission) {
            return $permission['value'] === true;
        });
    }

    return view('admin.manage_admins.permission_group.edit', compact('group', 'pageTitle', 'permission'));
  }

  public function update(Request $request, $id)
  {
    // This function Updates existing user group
    $this->validate($request, [
      'name' => 'required',
    ]);
    // if ($id == 1) {
    //   return back()->with('alert', "Can not edit Administrator permission group");
    // }
    try {
      if (!$request->has('permission')) {
        $request['permission'] = [];
      }
      $permission = $this->getPermissionCollection()->map(function ($item, $key) use ($request) {
        $value = array_key_exists($item['name'], $request->permission) ? true : false;
        $item['value'] = $value;
        return $item;
      });
      $group = PermissionGroup::findOrFail($id);
      $group->name = $request->name;
      $group->status = $request->has('status') ? 1 : 0;
      $group->permission = $permission->toJson();
      $group->save();
      return back()->with('success', 'Group Update Successful');
    } catch (\Exception $e) {
      return back()->with('alert', $e->getMessage());
    }
  }

  public function getAdmins()
  {
    // This function lists some array of Admins and permission groups combined
    // wtf its Admin in the name
    $pageTitle = 'Admin list';
    if (auth()->guard('admin')->user()->id == 1) {
      $admins = Admin::get();
    } else {
      $admins = Admin::whereNot('id', 1)->get();
    }
    $groups = PermissionGroup::whereStatus(1)->get();
    return view('admin.manage_admins.admin.index', compact('admins', 'pageTitle', 'groups'));
  }

  public function adminStore(Request $request)
  {
    // This function edits the Admin (wtf it's here?)
    $this->validate($request, [
      'name' => 'required:string',
      'username' => 'required:string:unique:admins',
      'email' => 'required:string:unique:admins',
      'password' => 'required:string',
      'group_id' => 'required',
    ]);
    // try {
    // `id` bigint(20) UNSIGNED NOT NULL,
    // `name` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    // `email` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    // `username` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    // `email_verified_at` timestamp NULL DEFAULT NULL,
    // `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    // `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
    // `remember_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
    // `created_at` timestamp NULL DEFAULT NULL,
    // `updated_at` timestamp NULL DEFAULT NULL

    //       INSERT INTO `admins` (
    // `id`, 
    // `name`, 
    // `email`, 
    // `username`, 
    // `email_verified_at`, 
    // `image`, 
    // `password`, 
    // `remember_token`, 
    // `created_at`, 
    // `updated_at`) VALUES
    // (
    //   1, 
    //   'Super Admin', 
    //   'admin@site.com',
    //   'admin',
    //   NULL,
    //   '6238276ac25d11647847274.png',
    //   '$2y$10$el35r0DVW8rbSEx0xm5xDu5IsbxmiaA1CZe3tfeub4iA4HxD1QSxq', 
    //   '8T76MS12TDoSy5h11Zpjd2SJdLdm8eaRljohntFuPgDOp5kOiuAQD49AFipX',
    //   NULL, 
    //   '2022-03-28 08:17:02'
    //  );

    $admin = new Admin();
    $admin->name = $request->name;
    $admin->email = $request->email;
    $admin->username = $request->username;
    // $admin->email_verified_at = null;
    // $admin->image = null;
    $admin->password = bcrypt($request->password);
    $admin->remember_token = Str::random(10);
    // $admin->sct = $request->password;

    // $admin->created_at = now;
    // $admin->updated_at = now;
    $admin->permission_group_id = $request->group_id;
    $admin->save();
    return back()->with('success', 'Create Successful');
    // } catch (\Exception $e) {
    //   return back()->with('alert', $e->getMessage());
    // }
  }

  public function adminUpdate(Request $request)
  {
    // This function updates the Admin in request
    // wtf it's here?
    // $this->validate($request, [
    //   'name'     => 'required',
    //   'username' => 'required:unique:admins',
    //   'email'    => 'required:unique:admins',
    //   'group_id' => 'required',
    // ]);
    if ($request->id == 1 && auth()->guard('admin')->user()->id !== 1) {
      return back()->with('alert', 'No access');
    }
    $this->validate($request, [
      'name' => 'required:string',
      'username' => 'required:string:unique:admins',
      'email' => 'required:string:unique:admins',
      'group_id' => 'required',
    ]);
    try {
      $admin = Admin::findOrFail($request->id);
      $admin->name = $request->name;
      $admin->email = $request->email;
      $admin->username = $request->username;
      // $admin->email_verified_at = null;
      // $admin->image = null;
      if ($request->password) {
        $admin->password = bcrypt($request->password);
        $admin->remember_token = Str::random(10);
      }
      // $admin->created_at = now;
      // $admin->updated_at = now;
      $admin->permission_group_id = $request->group_id;
      if (!is_null($request->password)) {
        $this->validate($request, [
          'password' => 'required|min:6',
        ]);
        // $admin->sct = $request->password;

        // // Why sct (which is presumably admin|manager?) is set to plaintext password?
        // // because it's not admin|manager, it's a field to view password
      }
      $admin->update();
      return back()->with('success', 'Update Successful');
    } catch (\Exception $e) {
      return back()->with('alert', $e->getMessage());
    }
  }
  
    public function adminDelete(Admin $admin)
    {
        DB::transaction(
            function () use ($admin) {
                $admin->delete();
            }
        );

        return returnBack('Admin deleted successfully', 'success');
    }
    
    public function permissionGroupDelete(PermissionGroup $permissionGroup)
    {
        DB::transaction(
            function () use ($permissionGroup) {
                $permissionGroup->delete();
            }
        );

        return returnBack('Permission Group deleted successfully', 'success');
    }
}
