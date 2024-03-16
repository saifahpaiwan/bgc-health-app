<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Inertia\Inertia;

class RoleController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware('permission:role-list|role-create|role-edit|role-delete', ['only' => ['index', 'store']]);
    //     $this->middleware('permission:role-create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:role-edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:role-delete', ['only' => ['destroy']]);
    // }

    public function index(Request $request)
    {
        $query = Role::query();
        $query->where('id', '!=', 1);
        if (isset($request->keyword) && !empty($request->keyword)) {
            $query->where('name', 'like', "%$request->keyword%");
        }

        $roles = $query->orderBy('id', 'DESC')->get();
        return Inertia::render('Roles/Index', [
            'roles' => $roles
        ]);
    }

    public function create()
    {
        $permission = Permission::get();
        $permissionArr = [];
        if (isset($permission) && count($permission) > 0) {
            foreach ($permission as $row) {
                $permissionArr[$row->group]['name'] = $row->group;
                $permissionArr[$row->group]['list'][$row->id]['id'] = $row->id;
                $permissionArr[$row->group]['list'][$row->id]['name'] = $row->name;
                $permissionArr[$row->group]['list'][$row->id]['description'] = $row->description;
            }
        }

        return Inertia::render('Roles/Create', [
            'permissionArr' => $permissionArr,
        ]);
    }

    public function edit($id)
    {
        $role = Role::find($id);
        $permission = Permission::get();
        $permissionArr = [];
        foreach ($permission as $row) {
            $permissionArr[$row->group]['name'] = $row->group;
            $permissionArr[$row->group]['list'][$row->id]['id']          = $row->id;
            $permissionArr[$row->group]['list'][$row->id]['name']        = $row->name;
            $permissionArr[$row->group]['list'][$row->id]['description'] = $row->description;
        }

        $rolePermissions = DB::table("role_has_permissions")->where("role_has_permissions.role_id", $id)
        ->pluck('role_has_permissions.permission_id', 'role_has_permissions.permission_id')->all();
 
        return Inertia::render('Roles/Edit', [
            'role' => $role,
            'permissionArr' => $permissionArr,
            'rolePermissions' => $rolePermissions
        ]);
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:roles,name',
            'permission' => 'required',
        ]);
        $role = Role::create(['name' => $request->input('name')]);
        $role->syncPermissions($request->input('permission'));
        Log::info('username : ' . auth()->user()->username . ' IP : ' . request()->ip() . ' | Role created successfully : ' . $request->input('name') . ' | ' . implode(",", $request->input('permission')));
        return redirect()->route('roles.index')->with('success', 'Role created successfully');
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'permission' => 'required',
        ]);

        $role = Role::find($id); 
        if (isset($role->id) && !empty($role->id)) {
            $role->name = $request->name;
            $role->save(); 
            $role->syncPermissions($request->input('permission'));
            Log::info('username : ' . auth()->user()->username . ' IP : ' . request()->ip() . ' | Role updated successfully : ' . json_encode($request->all(), true));
            return redirect()->route('roles.index')->with('success', 'Role updated successfully');
        } 
    }

    public function destroy($id)
    {
        DB::table("roles")->where('id', $id)->delete();
        Log::info('username : ' . auth()->user()->username . ' IP : ' . request()->ip() . ' | Role deleted successfully ');
        return redirect()->route('roles.index')
            ->with('success', 'Role deleted successfully');
    }
}
