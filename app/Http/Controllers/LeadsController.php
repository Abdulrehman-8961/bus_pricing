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

    public function edit(Request $request, $id){
        $leads = DB::table('leads')->where('id',$id)->first();
        return view('leads.Edit', compact("leads"));
    }


    public function updateLead(Request $request, $id){
        $updateField = [];
        if ($request->has('name')) {
            $nameParts = explode(' ', $request->name);
            $updateField['firstname'] = $nameParts[0];
            $updateField['lastname'] = $nameParts[1];
        }
        if ($request->has('email')) {
            $updateField['email'] = $request->input('email');
        }
        if ($request->has('phone')) {
            $updateField['phone'] = $request->input('phone');
        }
        if ($request->has('hinfahrt')) {
            $updateField['hinfahrt'] = $request->input('hinfahrt');
        }
        if ($request->has('rueckfahrtt')) {
            $updateField['rueckfahrtt'] = $request->input('rueckfahrtt');
        }
        if ($request->has('pax')) {
            $updateField['pax'] = $request->input('pax');
        }
        if ($request->has('hinfahrt_other_stops')) {
            $updateField['hinfahrt_other_stops'] = $request->input('hinfahrt_other_stops');
        }
        if ($request->has('rueckfahrtt_other_stops')) {
            $updateField['rueckfahrtt_other_stops'] = $request->input('rueckfahrtt_other_stops');
        }
        $update = DB::table('leads')->where('id', $id)->update($updateField);
        if ($update) {
            $lead = DB::table('leads')->where('id', $id)->first();
        }
        return redirect()->back()->with('success','Lead updated');
    }

    public function transferToDeal($id){
        DB::table('leads')->where('id',$id)->update([
            'in_deal' => 1
        ]);
        return redirect()->back()->with('success','Transfered to deal');
    }
    public function delete($id){
        DB::table('leads')->where('id',$id)->update([
            'is_deleted' => 1
        ]);
        return redirect()->back()->with('success','Lead deleted');
    }


}
