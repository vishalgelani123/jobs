<?php

namespace App\Http\Controllers\Backend;

use App\DataTables\UserDataTable;
use App\Helpers\AuditLogHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserStoreRequest;
use App\Http\Requests\User\UserUpdateRequest;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(UserDataTable $dataTable)
    {
        $roles = Role::all();
        $admins = User::role('admin')->whereDoesntHave('roles', function ($query) {
            $query->where('name', 'approver');
        })->get();
        return $dataTable->render('backend.user.index', compact('roles', 'admins'));
    }

    public function store(UserStoreRequest $request)
    {
        try {
            $user = new User;
            $user->user_id = Auth::id();
            $user->name = $request->name;
            $user->branch_name = $request->branch_name;
            $user->designation = $request->designation;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            $user->password = Hash::make($request->password);

            if ($request->type == 'drafter') {
                $user->user_id = $request->admin;
            }
            $user->save();

            if ($request->type == 'approver_with_admin') {
                $user->assignRole(['admin', 'approver']);
            } else {
                $user->assignRole($request->type);
            }

            AuditLogHelper::storeLog('created', 'user', $user->id, [], $user);

            return response()->json([
                'status'  => true,
                'message' => 'User store successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit(Request $request)
    {
        try {
            $user = User::find($request->id);

            $role = '';
            if (isset($user->roles[0]->name)) {
                $role = $user->roles[0]->name;
            }
            if ($user->hasRole('approver') && $user->hasRole('admin')) {
                $user->role = 'approver_with_admin';
            } else {
                $user->role = $role;
            }

            return response()->json([
                'status'  => true,
                'data'    => $user,
                'message' => 'User fetched successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(UserUpdateRequest $request, User $user)
    {
        try {
            $user->name = $request->name;
            $user->branch_name = $request->branch_name;
            $user->designation = $request->designation;
            $user->email = $request->email;
            $user->mobile = $request->mobile;
            if ($request->password != "") {
                $user->password = Hash::make($request->password);
            }

            $user->user_id = Auth::id();
            if ($request->type == 'drafter') {
                $user->user_id = $request->admin;
            }
            $updatedValues = $user->getDirty();
            $oldValues = [];
            foreach ($updatedValues as $field => $newValue) {
                $oldValues[$field] = $user->getOriginal($field);
            }

            $user->save();

            $oldRoles = $user->roles->pluck('name')->toArray(); // Old roles before detaching

            if ($request->type == 'approver_with_admin') {
                $newRoles = ['admin', 'approver'];
            } else {
                $newRoles = $request->type;
            }

            $user->syncRoles([]); // Remove previous roles
            $user->assignRole($newRoles);

            if (!empty($oldRoles) || !empty($newRoles)) {
                $oldValues['roles'] = $oldRoles;
                $updatedValues['roles'] = $newRoles;
            }

            if (!empty($updatedValues)) {
                AuditLogHelper::storeLog('updated', 'User', $user->id, $oldValues, $updatedValues);
            }

            return response()->json([
                'status'  => true,
                'message' => 'User update successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function delete(User $user)
    {
        try {
            /*PreVendorDetail::where('id', $user->invite_vendor_id)->delete();
            $vendor = Vendor::where('user_id', $user->id)->first();
            if (!empty($vendor)) {
                Branch::where('vendor_id', $vendor->id)->delete();
                Vendor::where('user_id', $user->id)->delete();
            }*/

            $oldValues = [];
            $updatedValues = ['user_deleted' => $user->name . " user deleted"];

            AuditLogHelper::storeLog('deleted', 'user', $user->id, $oldValues, $updatedValues);

            if ($user->delete()) {
                return response()->json([
                    'status'  => true,
                    'message' => 'User deleted successfully'
                ]);
            } else {
                return response()->json([
                    'status'  => false,
                    'message' => "User not found!"
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status'  => false,
                'message' => $e->getMessage()
            ]);
        }
    }
}
