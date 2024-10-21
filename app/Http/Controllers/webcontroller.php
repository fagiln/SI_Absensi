<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

Class WebController extends Controller
{
    public function index()
    {

        $data =[
            'title' => 'Pemetaan',
        ];
        return view('layouts.v_web',$data);
    }

    public function map()
    {
        return view('maps');
    }
}