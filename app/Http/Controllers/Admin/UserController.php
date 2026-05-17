<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $data = User::with(['office', 'role'])->get();
        return view('admin.users.index', compact('data'));
    }

    public function create()
    {
        $offices = Office::all();
        $roles = Role::all();

        return view('admin.users.create', compact('offices', 'roles'));
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if (!empty($data['password'])) {
            $data['password'] = \Illuminate\Support\Facades\Hash::make($data['password']);
        }
        User::create($data);
        return redirect()->route('admin.users.index');
    }

    public function show($id)
    {
        $row = User::findOrFail($id);
        return view('admin.users.show', compact('row'));
    }

    public function edit($id)
    {
        $row = User::findOrFail($id);
        $offices = Office::all();
        $roles = Role::all();

        return view('admin.users.edit', compact('row', 'offices', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $row = User::findOrFail($id);
        $data = $request->all();
        
        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }
        
        $row->update($data);
        return redirect()->route('admin.users.index');
    }

    public function destroy($id)
    {
        $row = User::findOrFail($id);
        $row->delete();
        return redirect()->route('admin.users.index');
    }

    public function toggleStatus(User $user)
    {
        if (Auth::id() === $user->id && $user->is_active) {
            return back()->with('error', 'You cannot deactivate your own account.');
        }

        $user->update([
            'is_active' => ! $user->is_active,
        ]);

        $message = $user->is_active
            ? 'User account activated successfully.'
            : 'User account deactivated successfully.';

        return back()->with('success', $message);
    }
}
