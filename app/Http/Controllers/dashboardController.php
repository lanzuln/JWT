<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class dashboardController extends Controller
{
    public function dashboardPage(){

        return view('pages.dashboard.dashboard-page');
    }

    public function profilePage(){
        return view('pages.dashboard.profile-page');

    }
}
