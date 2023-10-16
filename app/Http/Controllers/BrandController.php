<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;

class BrandController extends Controller
{
    public function index()
    {
        return Brand::all();
    }
    public function store()
    {
        $brand = Brand::create([
            'name' => request('name'),
            'slug' => request('slug'),
            'description' => request('description'),
            'image' => request('image'),
            'status' => request('status'),
            'parent_id' => request('parent_id') || null,
        ]);
        return $brand;
    }
}
