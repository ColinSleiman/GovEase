<?php

namespace App\Http\Controllers;

use App\Models\Municipality;

class MunicipalityController extends Controller
{
    public function index()
    {
        return Municipality::all();
    }
}
