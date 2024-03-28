<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Auth;

class BusPartnerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'verified', 'isAdmin']);
        $this->title = "Buspartner";
    }

    public function view(Request $request)
    {
        $title = $this->title;
        $search = @$request->get('search');
        $data = DB::table('bus_partner as b')
            ->where('b.is_deleted', 0)
            ->leftJoin('bundeslander as bd', function ($join) {
                $join->on('bd.id', '=', 'b.bundesland')->where('bd.is_deleted', 0);
            })
            ->leftJoin('bus_type as bt', function ($join) {
                $join->on('bt.id', '=', 'b.bustype')->where('bt.is_deleted', 0);
            })
            ->select('b.*', 'bt.name as bus_name', 'bd.bundsland as bundsland_name')
            ->where(function ($query) use ($search) {
                if (!empty($search)) {
                    $query->where('lieferanten', 'LIKE', '%' . $search . '%')
                        ->orWhere('firmnname', 'LIKE', '%' . $search . '%')
                        ->orWhere('adresse', 'LIKE', '%' . $search . '%')
                        ->orWhere('stadt', 'LIKE', '%' . $search . '%')
                        ->orWhere('plz', 'LIKE', '%' . $search . '%')
                        ->orWhere('bt.name', 'LIKE', '%' . $search . '%')
                        ->orWhere('bd.bundsland', 'LIKE', '%' . $search . '%');
                }
            })
            ->orderBy('id', 'desc')->paginate(20);
        $data->appends([
            "search" => $search,
        ]);

        return view("buspartner.view", compact("title", "data"));
    }
    public function save(Request $request)
    {
        $validated = $request->validate([
            "lieferanten" => 'required',
            "firmnname" => 'required',
            "adresse" => 'required',
            "stadt" => 'required',
            "bundesland" => 'required',
            "plz" => 'required',
            "bustype" => 'required',
        ]);
        if ($validated) {
            DB::table('bus_partner')->insert([
                "lieferanten" => $request->input('lieferanten'),
                "firmnname" => $request->input('firmnname'),
                "adresse" => $request->input('adresse'),
                "stadt" => $request->input('stadt'),
                "bundesland" => $request->input('bundesland'),
                "plz" => $request->input('plz'),
                "bustype" => $request->input('bustype'),
                "created_at" => date("Y-m-d H:i:s")
            ]);
            return redirect()->back()->with('success', "Bus partner added");
        }
    }
    public function edit($id)
    {
        $title = $this->title;
        $data = DB::table('bus_partner')
            ->where('is_deleted', 0)
            ->where('id', $id)
            ->first();
        return view("buspartner.Edit", compact("data", "title"));
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