<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

class PageController extends Controller
{
    public function index()
    {
        if (Session::has('campaign_id')) {
            return view('campaign', ['campaignId' => Session::get('campaign_id')]);
        } else {
            return redirect()->route('campaigns.index');
        }
    }
}