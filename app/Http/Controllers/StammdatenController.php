<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Auth;

class StammdatenController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'isAdmin']);
        $this->title = "Stammdaten";
    }

    public function view(Request $request)
    {
        $title = $this->title;
        $category = @$request->get('category');
        $search = @$request->get('search');
        $leads = DB::table('leads')->where('is_deleted', 0)->where('in_deal', 1)
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

        return view("stammdaten.view", compact("title", "leads"));
    }
    public function add()
    {
        return view("users.UsersAdd");
    }
    public function save(Request $request)
    {
        $validated = $request->validate([
            "name" => 'required',
            "role" => 'required',
            "email" => 'required|email|unique:users',
            "password" => 'required|min:6',
            "confirm_password" => 'required|same:password'
        ]);
        if ($validated) {
            DB::table('users')->insert([
                "name" => $request->input('name'),
                "last_name" => $request->input('last_name'),
                "email" => $request->input('email'),
                "phone" => $request->input('phone_number'),
                "address" => $request->input('address'),
                "role" => $request->input('role'),
                "password" => Hash::make($request->input('password')),
                "created_at" => date("Y-m-d H:i:s")
            ]);
            return redirect()->back()->with('success', "User added");
        }
    }
    public function edit($id)
    {
        $title = $this->title;
        $user = DB::table('users')
            ->where('id', $id)
            ->first();
        return view("users.UsersEdit", compact("user", "title"));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            "name" => 'required',
            "role" => 'required',
            "email" => [
                "required",
                "email",
                Rule::unique('users', 'email')->ignore($id)
            ]
        ]);
        if ($validated) {
            DB::table('users')->where('id', $id)->update([
                "name" => $request->input('name'),
                "last_name" => $request->input('last_name'),
                "email" => $request->input('email'),
                "phone" => $request->input('phone_number'),
                "role" => $request->input('role'),
                "address" => $request->input('address'),
                "updated_at" => date("Y-m-d H:i:s")
            ]);
            return redirect()->back()->with('success', 'User Profile updated');
        }
    }
    public function update_password(Request $request, $id)
    {
        $validated = $request->validate([
            "password" => 'required|min:6',
            "confirm_password" => 'required|same:password',
        ]);
        if ($validated) {
            DB::table('users')->where('id', $id)->update([
                "password" => Hash::make($request->input('password')),
                "updated_at" => date("Y-m-d H:i:s")
            ]);
            return redirect()->back()->with('success', 'User password updated');
        }
    }
    public function delete($id)
    {
        DB::table('users')->where('role', '!=', 'Amdin')->where('id', $id)->delete();
        return redirect()->back()->with('success', 'User deleted');
    }
}
