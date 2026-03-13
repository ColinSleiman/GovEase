<?php

namespace App\Http\Controllers;

use App\Models\ServiceCategory;

class ServiceCategoryController extends Controller
{
    public function index()
    {
        return ServiceCategory::all();
    }
}
