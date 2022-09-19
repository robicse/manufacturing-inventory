<?php

namespace App\Http\Controllers;

use App\Store;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use DB;

class HomeController extends Controller
{
    public function index()
    {
        $stores = Store::all();
        return view('backend._partial.home', compact('stores'));
    }
}
