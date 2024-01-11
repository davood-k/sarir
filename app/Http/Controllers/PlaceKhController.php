<?php

namespace App\Http\Controllers;

use App\PlaceKh;
use Illuminate\Http\Request;

class PlaceKhController extends Controller
{
    public function index()
    {
        $results = PlaceKh::all();
        return view('edarat/all' , compact('results'));
    }

    public function create()
    {
        $results = PlaceKh::all();
        return view('edarat/create' , compact('results'));
    }

}
