<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileSettings extends Controller
{
    public function __construct()
    {
        $this->middleware(["auth"]);
    }
    public function settings()
    {
        $user = DB::table('users')->where('id', Auth::user()->id)->first();
        return view("profile.profileSettings", compact("user"));
    }
    public function update_profile(Request $request)
    {
        $validated = $request->validate([
            "name" => 'required',
            "email" => [
                "required",
                "email",
                Rule::unique('users', 'email')->ignore(Auth::user()->id),
            ]
        ]);
        if($validated)
        {
            DB::table('users')->where('id', Auth::user()->id)->update([
                "name" => $request->input('name'),
                "last_name" => $request->input('last_name'),
                "email" => $request->input('email'),
                "phone" => $request->input('phone'),
                "address" => $request->input('address'),
            ]);
            return redirect()->back()->with('success', "Profile Updated");
        }
    }
    public function update_password(Request $request)
    {
        $validated = $request->validate([
            "password" => 'required|min:6',
            "confirm_password" => 'required|same:password',
        ]);
        if($validated) {
            DB::table('users')->where('id', Auth::user()->id)->update([
                "password" => Hash::make($request->input('password')),
            ]);
            return redirect()->back()->with('success', 'Password updated');
        }
    }
}
