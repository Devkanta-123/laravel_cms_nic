<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Auth;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Website;
use Mews\Captcha\Facades\Captcha;
use Hash;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->where([['role_id', '!=', 1], 'is_enabled' => 1])->with('roles')->get();

        return $users;
    }

    public function get_roles()
    {
        $role = Role::where('id', '!=', 1)->get();
        return response()->json($role);
    }

    public function get_user()
    {

        $user = Auth::user()->load('roles');

        $website = Website::all();


        return response()->json(array($user, $website));
    }

    public function get_website_details()
    {
        return Website::all();
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users,email',
            'password' => 'required|min:8'
        ]);

        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'role_id' => $request->role,
            'password' => Hash::make($request->password)
        ]);

    }

    public function storeRoles(Request $request)
    {
        $request->validate([
            'name' => 'required',

        ]);

        return Role::create([
            'role_name' => $request->name,
        ]);

    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'sometimes|min|8'
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $user->password
        ]);

        $user->load('roles');
        return $user;
    }

    public function updateRole(Request $request, Role $role)
    {

        $request->validate([
            'name' => 'required',
        ]);

        $role->update([
            'role_name' => $request->name,
        ]);
        return $role;
    }

    public function destroy(User $user)
    {
        $user->delete();

        return response()->noContent();
    }

    public function change_role(Request $request, User $user)
    {
        $user->update([
            'role' => $request->role
        ]);
        return response()->json(['success' => true]);
    }

    public function generateCaptcha()
    {
        $captcha = Captcha::create('default', true);
        
        return response()->json([
            'captcha_img' => $captcha['img'],
            'captcha_key' => $captcha['key'],
        ]);
    }

    public function updateUserStatus(Request $request)
{
    $request->validate([
        'user_id' => 'required|exists:users,id',
        'is_enabled' => 'required|boolean',
    ]);

    $user = User::find($request->user_id);
    $user->is_enabled = $request->is_enabled;
    $user->save();

    return response()->json([
        'message' => 'User status updated successfully',
        'status' => $user->is_enabled,
    ]);
}
}
