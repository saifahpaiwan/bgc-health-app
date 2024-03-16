<?php

namespace App\Http\Controllers;

use App\Models\plants;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    // function __construct()
    // {
    //     $this->middleware('permission:users-list|users-create|users-edit|users-delete', ['only' => ['index', 'store']]);
    //     $this->middleware('permission:users-create', ['only' => ['create', 'store']]);
    //     $this->middleware('permission:users-edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:users-delete', ['only' => ['destroy']]);
    // }

    public function index(Request $request)
    {
        $query = User::query();
        $query->where('id', '!=', 1);
        
        if (isset($request->keyword) && !empty($request->keyword)) {
            $query->where('username', 'like', "%$request->keyword%")
                ->orWhere('email', 'like', "%$request->keyword%")
                ->orWhere('empolyee_code', 'like', "%$request->keyword%")
                ->orWhere('phone', 'like', "%$request->keyword%");
        }

        if (isset($request->status['id']) && !empty($request->status['id'])) {
            $status = ($request->status['id'] == -1) ? '0' : $request->status['id'];
            $query->where('status', $status);
        } 

        if (isset($request->roles['id']) && !empty($request->roles['id'])) {
            $rolesID = $request->roles['id'];
            $query->whereHas('rRoles', function ($QuerySub) use ($rolesID) {
                $QuerySub->where('model_id', $rolesID);
            });
        }

        $data = $query->orderBy('id', 'DESC')->get();

        $users = [];
        if (isset($data) && count($data) > 0) {
            foreach ($data as $row) {
                $users[$row->id]['id'] = $row->id;
                $users[$row->id]['image']    = (!empty($row->images_name)) ?  $row->images_name  : null;
                $users[$row->id]['plant']    = $row->rPlant?->plant;
                $users[$row->id]['username'] = $row->username;
                $users[$row->id]['empolyee_code'] = $row->empolyee_code;
                $users[$row->id]['role_name'] = $row->getRoleNames()->first();
                $users[$row->id]['status'] = $row->status;
                $users[$row->id]['updated_at'] = date("d/m/Y H:i:s", strtotime($row->updated_at));
            }
        }

        $roles = Role::select('id', 'name')->get();
        return Inertia::render('Users/Index', [
            'users' => $users,
            'roles' => $roles
        ]);
    }

    public function create()
    {
        $plants = plants::select('id', 'plant')->where('status', 1)->get();
        $roles = Role::select('id', 'name')->get();
        return Inertia::render('Users/Create', [
            'plants' => $plants,
            'roles' => $roles,
        ]);
    }

    public function store(Request $request)
    {
        $validate = [
            'code' => 'required',
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirmPassword',
            'roles' => 'required',
        ];
        request()->validate($validate);

        $file_name = null;
        if (isset($request->filename) && !empty($request->filename)) {
            $base64_image = $request->filename;
            if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
                $file = substr($base64_image, strpos($base64_image, ',') + 1);
                $file = base64_decode($file);
                $file_name = hexdec(uniqid()) . '.jpg';
                Storage::disk('public')->put('images/users/' . $file_name, $file);
            }
        }

        $input = $request->all();
        $input['empolyee_code'] = $request->code;
        $input['password'] = Hash::make($input['password']);
        $input['images_name'] = $file_name;
        $input['plant_id']    = $request->plant_id['id'];
        $user = User::create($input);
        $user->assignRole($request->roles['id']);
        Log::info('username : ' . auth()->user()->username . ' IP : ' . request()->ip() . ' | User created successfully' . json_encode($request->all(), true));
        return redirect()->route('users.index')->with('success', 'User created successfully');
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        $user = User::find($id);
        $roleID = $user->rRoles?->role_id;
        $plants = plants::select('id', 'plant')->where('status', 1)->get();
        $roles = Role::select('id', 'name')->get();
        return Inertia::render('Users/Edit', [
            'plants' => $plants,
            'roles' => $roles,
            'user'  => $user,
            'roleID' => $roleID,
        ]);
    }

    public function update(Request $request, string $id)
    {
        $validate = [
            'code' => 'required',
            'username' => 'required|string|max:255',
            'email'         => 'required|email|unique:users,email,' . $id,
            'password'      => 'same:confirm-password',
        ];

        if (!empty($request->roles['id'])) {
            $validate['roles'] = 'required';
        }

        request()->validate($validate);
 
        $users = User::find($id); 
        $file_name = $users->images_name;
        if (isset($request->filename) && !empty($request->filename)) {
            if (isset($users->images_name) && !empty($users->images_name)) {
                $uploade_location = 'storage/images/users/';
                if (!empty($users->images_name)) {
                    @unlink($uploade_location . $users->images_name);
                }
            }

            $base64_image = $request->filename;
            if (preg_match('/^data:image\/(\w+);base64,/', $base64_image)) {
                $file = substr($base64_image, strpos($base64_image, ',') + 1);
                $file = base64_decode($file);
                $file_name = hexdec(uniqid()) . '.jpg';
                Storage::disk('public')->put('images/users/' . $file_name, $file);
            }
        }

        $input = $request->all();
        $input['empolyee_code'] = $request->code;
        if (!empty($input['password'])) {
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, array('password'));
        }
        $input['images_name'] = $file_name;
        $input['plant_id']    = $request->plant_id['id'];

        $users->update($input);

        if (!empty($request->roles['id'])) {
            DB::table('model_has_roles')->where('model_id', $id)->delete();
            $users->assignRole($request->roles['id']);
        }
        Log::info('username : ' . auth()->user()->username . ' IP : ' . request()->ip() . ' | User updated successfully : ' . json_encode($request->all(), true)); 
        $redirect = (isset($request->redirect) && !empty($request->redirect)) ? 'home' : 'users.index';
        return redirect()->route($redirect)
            ->with('success', 'User updated successfully');
    }

    public function destroy(string $id)
    {
        $users = User::find($id);
        Log::info('username : ' . auth()->user()->username . ' IP : ' . request()->ip() . ' | users deleted successfully : ' . json_encode($users, true));
        $uploade_location = 'storage/images/users/';
        if (!empty($users->images_name)) {
            @unlink($uploade_location . $users->images_name);
        }
        $users->delete();
        Log::info('username : ' . auth()->user()->username . ' IP : ' . request()->ip() . ' |  User deleted successfully ');
        return redirect()->route('users.index')->with('success', 'User deleted successfully');
    }
}
