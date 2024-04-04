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

    public function edit(Request $request, $id)
    {
        $leads = DB::table('leads')->where('id', $id)->first();
        return view('leads.Edit', compact("leads"));
    }


    public function updateLead(Request $request, $id)
    {
        $updateField = [];
        if ($request->has('name')) {
            $nameParts = explode(' ', $request->name);
            $updateField['firstname'] = $nameParts[0];
            $updateField['lastname'] = $nameParts[1];
            $leadData = DB::table('leads')->where('id', $id)->first();

            $accessToken = "iwnyrX7KxxpmvHDMaJcy60_I7z0TD3J9D2S6jOvxrFbBcQ4E";
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.lexoffice.io/v1/contacts/' . $leadData->resuorceid,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Authorization: Bearer ' . $accessToken,
                    'Accept: application/json'
                ),
            ));

            $response = curl_exec($curl);
            $responseArray = json_decode($response, true);
            $resuorceVersion = $responseArray['version'];
            curl_close($curl);

            // update
            $data = array(
                'version'=>$resuorceVersion,
                'roles' => array(
                    'customer' => array('number' => $leadData->customer_number)
                ),
                'person' => array(
                    "salutation" => "",
                    'firstName' => $nameParts[0],
                    'lastName' => $nameParts[1],
                ),
                'note' => 'Notiz2en'
            );
            // dd(json_encode($data));

            $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, 'https://api.lexoffice.io/v1/contacts/'.$leadData->resuorceid);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                    'Authorization: Bearer ' . $accessToken,
                    'Content-Type: application/json',
                    'Accept: application/json'
                ));

                $response_2 = curl_exec($ch);
            curl_close($ch);
        }
        if ($request->has('email')) {
            $updateField['email'] = $request->input('email');
        }
        if ($request->has('phone')) {
            $updateField['phone'] = $request->input('phone');
        }
        if ($request->has('hinfahrt')) {
            $date = date('Y-m-d', strtotime($request->input('hinfahrt')));
            $updateField['hinfahrt'] = $date;
        }
        if ($request->has('rueckfahrtt')) {
            $date = date('Y-m-d', strtotime($request->input('rueckfahrtt')));
            $updateField['rueckfahrtt'] = $date;
        }
        if ($request->has('pax')) {
            $updateField['pax'] = $request->input('pax');
        }
        if ($request->has('notizer')) {
            $updateField['notizer'] = $request->input('notizer');
        }
        if ($request->has('entfernung')) {
            $updateField['entfernung'] = $request->input('entfernung');
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
        return redirect()->back()->with('success', 'Lead updated');
    }

    public function transferToDeal($id)
    {
        DB::table('leads')->where('id', $id)->update([
            'in_deal' => 1
        ]);
        return redirect()->back()->with('success', 'Transfered to deal');
    }
    public function delete($id)
    {
        DB::table('leads')->where('id', $id)->update([
            'is_deleted' => 1
        ]);
        return redirect()->back()->with('success', 'Lead deleted');
    }
    public function updateEmployee(Request $request, $id){
        DB::table('leads')->where('id',$id)->update([
            'kundenbetreuer' => $request->employee
        ]);
        return redirect()->back()->with('success', 'Kundenbetreuer Updated');
    }
    public function updateLabel(Request $request, $id){
        DB::table('leads')->where('id',$id)->update([
            'grund' => $request->label
        ]);
        return redirect()->back()->with('success', 'Label Updated');
    }
    public function updateQuelle(Request $request, $id){
        DB::table('leads')->where('id',$id)->update([
            'quelle' => $request->quelle
        ]);
        return redirect()->back()->with('success', 'Quelle Updated');
    }
}
