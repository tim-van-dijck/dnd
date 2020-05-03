<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

class PageController extends Controller
{
    public function index()
    {
        if (Session::has('campaign_id')) {
            return view('campaign');
        } else {
            return redirect()->route('campaigns.index');
        }
    }
}