<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Auth;

class AbwicklungController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'isAdmin']);
        $this->title = "Abwicklung";
    }

    public function view(Request $request)
    {
        $title = $this->title;
        $search = @$request->get('search');

        return view("abwicklung.view", compact("title"));
    }
}
