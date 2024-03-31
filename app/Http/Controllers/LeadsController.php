<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Auth;

class LeadsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'isAdmin']);
        $this->title = "Leads";
    }

    public function view(Request $request)
    {
        $search = @$request->input('search');
        $title = $this->title;
        $leads = DB::table('leads')->where('is_deleted', 0)
            ->where(function ($query) use ($search) {
                if (!empty($search)) {
                    $query->where('vnr', 'LIKE', '%' . $search . '%')
                        ->orWhere('customer_number', 'LIKE', '%' . $search . '%');
                }
            })
            ->paginate(20);

        return view("leads.view", compact("title", "leads"));
    }
}
