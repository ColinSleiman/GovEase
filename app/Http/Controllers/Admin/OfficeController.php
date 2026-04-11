<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Municipality;
use App\Models\Office;
use Illuminate\Http\Request;

class OfficeController extends Controller
{
    public function index()
    {
        $data = Office::all();
        return view('admin.offices.index', compact('data'));
    }

    public function create()
    {
        $data = Municipality::all();
        $apiKey = env('GOOGLE_MAPS_API_KEY');

        return view('admin.offices.create', compact('data', 'apiKey'));
    }

    public function store(Request $request)
    {
        Office::create($request->all());
        return redirect()->route('admin.offices.index');
    }

    public function show($id)
    {
        $row = Office::findOrFail($id);
        return view('admin.offices.show', compact('row'));
    }

    public function edit($id)
    {
        $office= Office::findOrFail($id);
        $municipalities = Municipality::all();
        return view('admin.offices.edit', compact('office', 'municipalities'));
    }

    public function update(Request $request, $id)
    {
        $row = Office::findOrFail($id);
        $row->update($request->all());
        return redirect()->route('admin.offices.index');
    }

    public function destroy($id)
    {
        $row = Office::findOrFail($id);
        $row->delete();
        return redirect()->route('admin.offices.index');
    }
}
