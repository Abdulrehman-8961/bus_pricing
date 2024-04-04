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
        $category = @$request->get('category');
        $leads = DB::table('leads')->where('phase', 'Abwicklung')
            ->where(function ($query) use ($category, $search) {
                if (empty($category)) {
                    $query->where('customer_number', 'LIKE', '%' . $search . '%')
                        ->orWhere('firstname', 'LIKE', '%' . $search . '%')
                        ->orWhere('lastname', 'LIKE', '%' . $search . '%')
                        ->orWhere('firmaoptional', 'LIKE', '%' . $search . '%')
                        ->orWhere('email', 'LIKE', '%' . $search . '%')
                        ->orWhere('grund', 'LIKE', '%' . $search . '%');
                } elseif (!empty($category)) {
                    if ($category == "kunden_nr") {
                        $query->where('customer_number', 'LIKE', '%' . $search . '%');
                    } elseif ($category == "firmenname") {
                        $query->where('firmaoptional', 'LIKE', '%' . $search . '%');
                    } elseif ($category == "kundenname") {
                        $query->where('firstname', 'LIKE', '%' . $search . '%')->orWhere('lastname', 'LIKE', '%' . $search . '%');
                    } elseif ($category == "label") {
                        $query->where('grund', 'LIKE', '%' . $search . '%');
                    } elseif ($category == "email") {
                        $query->where('email', 'LIKE', '%' . $search . '%');
                    }
                }
            })
            ->paginate(20);

        return view("abwicklung.view", compact("title", "leads"));
    }
}
