<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Auth;

class DealController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->title = "Deal";
    }

    public function view(Request $request)
    {
        $title = $this->title;
        $search = @$request->get('search');

        return view("deal.view", compact("title"));
    }
}
