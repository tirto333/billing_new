<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleContoller extends Controller
{
    public function index()
    {
        if (\Auth::user()->can('manage role')) {
            $roles = Role::where('created_by', '=', \Auth::user()->creatorId())->where('created_by', '=', \Auth::user()->creatorId())->get();
            return view('role.index')->with('roles', $roles);
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function create()
    {
        if (\Auth::user()->can('create role')) {
            $user = \Auth::user();
            if ($user->type == 'super admin') {
                $permissions = Permission::all()->pluck('name', 'id')->toArray();
            } else {
                $permissions = new Collection();
                foreach ($user->roles as $role) {
                    $permissions = $permissions->merge($role->permissions);
                }
                $permissions = $permissions->pluck('name', 'id')->toArray();
            }

            return view('role.create', ['permissions' => $permissions]);
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }

    public function edit(Role $role)
    {
        if (\Auth::user()->can('edit role')) {

            $user = \Auth::user();
            if ($user->type == 'super admin') {
                $permissions = Permission::all()->pluck('name', 'id')->toArray();
            } else {
                $permissions = new Collection();
                foreach ($user->roles as $role1) {
                    $permissions = $permissions->merge($role1->permissions);
                }
                $permissions = $permissions->pluck('name', 'id')->toArray();
            }

            return view('role.edit', compact('role', 'permissions'));
        } else {
            return redirect()->back()->with('error', 'Permission denied.');
        }
    }
}
