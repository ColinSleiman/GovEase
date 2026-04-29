<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Office;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

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
        User::create($request->all());
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
        $row->update($request->all());
        return redirect()->route('admin.users.index');
    }

    public function destroy($id)
    {
        $row = User::findOrFail($id);
        $row->delete();
        return redirect()->route('admin.users.index');
    }
}
