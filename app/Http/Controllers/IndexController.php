<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(){

        return view('index');
    }
    public function imageAdd(Request $request){
        return view('image-file');

    }
    public function xmlAdd(Request $request){
        return view('xml-file');
    }
}
