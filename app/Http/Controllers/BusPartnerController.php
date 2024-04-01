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
        // $data = DB::table('bus_partner as b')
        //     ->where('b.is_deleted', 0)
        //     ->leftJoin('bundeslander as bd', function ($join) {
        //         $join->on('bd.id', '=', 'b.bundesland')->where('bd.is_deleted', 0);
        //     })
        //     ->leftJoin('bus_type as bt', function ($join) {
        //         $join->on('bt.id', '=', 'b.bustype')->where('bt.is_deleted', 0);
        //     })
        //     ->select('b.*', 'bt.name as bus_name', 'bd.bundsland as bundsland_name')
        //     ->where(function ($query) use ($search) {
        //         if (!empty($search)) {
        //             $query->where('lieferanten', 'LIKE', '%' . $search . '%')
        //                 ->orWhere('firmnname', 'LIKE', '%' . $search . '%')
        //                 ->orWhere('adresse', 'LIKE', '%' . $search . '%')
        //                 ->orWhere('stadt', 'LIKE', '%' . $search . '%')
        //                 ->orWhere('plz', 'LIKE', '%' . $search . '%')
        //                 ->orWhere('bt.name', 'LIKE', '%' . $search . '%')
        //                 ->orWhere('bd.bundsland', 'LIKE', '%' . $search . '%');
        //         }
        //     })
        //     ->orderBy('id', 'desc')->paginate(20);
        // $data->appends([
        //     "search" => $search,
        // ]);

        $leads = DB::table('leads as l')->where('l.is_deleted', 0)
            ->leftJoin('bundeslander as bd', function ($join) {
                $join->on('bd.id', '=', 'l.bundesland')->where('bd.is_deleted', 0);
            })
            ->leftJoin('bus_type as bt', function ($join) {
                $join->on('bt.id', '=', 'l.bustype')->where('bt.is_deleted', 0);
            })
            ->select('l.*', 'bt.name as bus_name', 'bd.bundsland as bundsland_name')
            ->paginate(20);

        return view("buspartner.view", compact("title", "leads"));
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
                "partner_firmnname" => $request->input('firmnname'),
                "adresse" => $request->input('adresse'),
                "stadt" => $request->input('stadt'),
                "bundesland" => $request->input('bundesland'),
                "plz" => $request->input('plz'),
                "bustype" => $request->input('bustype')
            ]);
            return redirect()->back()->with('success', "Bus partner added");
        }
    }
    public function edit($id)
    {
        $title = $this->title;
        $data = DB::table('leads')
            ->where('is_deleted', 0)
            ->where('id', $id)
            ->first();
        return view("buspartner.Edit", compact("data", "title"));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            "firmnname" => 'required',
            "adresse" => 'required',
            "stadt" => 'required',
            "bundesland" => 'required',
            "plz" => 'required',
            "bustype" => 'required',
        ]);
        if ($validated) {
            DB::table('leads')->where('id', $id)->update([
                "partner_firmnname" => $request->input('firmnname'),
                "adresse" => $request->input('adresse'),
                "stadt" => $request->input('stadt'),
                "bundesland" => $request->input('bundesland'),
                "plz" => $request->input('plz'),
                "bustype" => $request->input('bustype')
            ]);
            return redirect()->back()->with('success', 'Bus partner updated');
        }
    }
    public function delete($id)
    {
        DB::table('bus_partner')->where('id', $id)->update([
            'is_deleted' => 1
        ]);
        return redirect()->back()->with('success', 'Bus partner deleted');
    }
}
