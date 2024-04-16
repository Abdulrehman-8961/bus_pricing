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
                        ->orWhere('firstname', 'LIKE', '%' . $search . '%')
                        ->orWhere('lastname', 'LIKE', '%' . $search . '%')
                        ->orWhere('phone', 'LIKE', '%' . $search . '%')
                        ->orWhere('hinundrueck', 'LIKE', '%' . $search . '%')
                        ->orWhere('pax', 'LIKE', '%' . $search . '%')
                        ->orWhere('grund', 'LIKE', '%' . $search . '%')
                        ->orWhere('customer_number', 'LIKE', '%' . $search . '%');
                }
            })
            ->orderBy('id', 'desc')
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
        $accessToken = "iwnyrX7KxxpmvHDMaJcy60_I7z0TD3J9D2S6jOvxrFbBcQ4E";
        $leadData = DB::table('leads')->where('id', $id)->first();
        $updateField = [];
        if ($request->has('name')) {
            $nameParts = explode(' ', $request->name);
            $updateField['firstname'] = $nameParts[0];
            $updateField['lastname'] = $nameParts[1];

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
            if (!$responseArray['person']) {
                $data = array(
                    'version' => $resuorceVersion,
                    'roles' => array(
                        'customer' => array('number' => $leadData->customer_number)
                    ),
                    'company' => array(
                        'contactPersons' => array(
                            "salutation" => "",
                            'firstName' => $nameParts[0],
                            'lastName' => $nameParts[1],
                        ),
                    ),
                    'note' => 'Notiz2en'
                );
            } else {
                $data = array(
                    'version' => $resuorceVersion,
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
            }
            // dd(json_encode($data));

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://api.lexoffice.io/v1/contacts/' . $leadData->resuorceid);
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
            DB::table('log_history')->insert([
                'lead_id' => $id,
                'description' => 'Kontaktname in Lexoffice auf „' . $nameParts[0] . ' ' . $nameParts[1] . '“ aktualisiert',
                'by_user_id' => Auth::user()->id,
            ]);
        }
        if ($request->has('email')) {
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
                'version' => $resuorceVersion,
                'roles' => array(
                    'customer' => array('number' => $leadData->customer_number)
                ),
                'emailAddresses' => array(
                    "private" => array(
                        $request->input('email')
                    ),
                ),
                'note' => 'Notiz2en'
            );
            // dd(json_encode($data));

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://api.lexoffice.io/v1/contacts/' . $leadData->resuorceid);
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
            $updateField['email'] = $request->input('email');
            DB::table('log_history')->insert([
                'lead_id' => $id,
                'description' => 'E-Mail wurde auf ' . $request->input('email') . ' aktualisiert',
                'by_user_id' => Auth::user()->id,
            ]);
        }
        if ($request->has('phone')) {
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
                'version' => $resuorceVersion,
                'roles' => array(
                    'customer' => array('number' => $leadData->customer_number)
                ),
                'phoneNumbers' => array(
                    "private" => array(
                        $request->input('phone')
                    ),
                ),
                'note' => 'Notiz2en'
            );

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://api.lexoffice.io/v1/contacts/' . $leadData->resuorceid);
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
            $updateField['phone'] = $request->input('phone');
            DB::table('log_history')->insert([
                'lead_id' => $id,
                'description' => 'Telefonnummer aktualisiert auf ' . $request->input('phone'),
                'by_user_id' => Auth::user()->id,
            ]);
        }
        if ($request->has('company_name')) {
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
                'version' => $resuorceVersion,
                'roles' => array(
                    'customer' => array('number' => $leadData->customer_number)
                ),
                'company' => array(
                    "name" => $request->input('company_name'),
                ),
                'note' => 'Notiz2en'
            );
            // dd(json_encode($data));

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://api.lexoffice.io/v1/contacts/' . $leadData->resuorceid);
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
            DB::table('log_history')->insert([
                'lead_id' => $id,
                'description' => 'Der Notizblock wurde auf „' . $request->input('notizer') . '“ aktualisiert',
                'by_user_id' => Auth::user()->id,
            ]);
        }
        if ($request->has('entfernung')) {
            $updateField['entfernung'] = $request->input('entfernung');
            DB::table('log_history')->insert([
                'lead_id' => $id,
                'description' => 'Die Entfernung wurde auf ' . $request->input('entfernung') . ' km aktualisiert',
                'by_user_id' => Auth::user()->id,
            ]);
        }
        if ($request->has('menu_732')) {
            $updateField['menu_732'] = $request->input('menu_732');
        }
        if ($request->has('menu_731')) {
            $updateField['menu_731'] = $request->input('menu_731');
        }
        if ($request->has('ziel')) {
            $updateField['ziel'] = $request->input('ziel');
        }
        if ($request->has('start')) {
            $updateField['start'] = $request->input('start');
        }
        if ($updateField) {
            $update = DB::table('leads')->where('id', $id)->update($updateField);
        }
        // if ($update) {
        //     $lead = DB::table('leads')->where('id', $id)->first();
        // }
        return redirect()->back()->with('success', 'Lead updated');
    }

    public function transferToDeal($id)
    {
        DB::table('leads')->where('id', $id)->update([
            'in_deal' => 1
        ]);
        return redirect()->back()->with('success', 'Lead wurde in Deal konvertiert');
    }
    public function delete($id)
    {
        DB::table('leads')->where('id', $id)->update([
            'is_deleted' => 1
        ]);
        return redirect()->back()->with('success', 'Lead deleted');
    }
    public function updateEmployee(Request $request, $id)
    {
        DB::table('leads')->where('id', $id)->update([
            'kundenbetreuer' => $request->employee
        ]);
        return redirect()->back()->with('success', 'Kundenbetreuer Updated');
    }
    public function updateLabel(Request $request, $id)
    {
        DB::table('leads')->where('id', $id)->update([
            'grund' => $request->label
        ]);
        return redirect()->back()->with('success', 'Label Updated');
    }
    public function updateQuelle(Request $request, $id)
    {
        DB::table('leads')->where('id', $id)->update([
            'quelle' => $request->quelle
        ]);
        return redirect()->back()->with('success', 'Quelle Updated');
    }
    public function upload(Request $request, $id)
    {
        $file = $request->file('upload');
        $name = $file->getClientOriginalName();
        $file->move(public_path('uploads'), $name);
        DB::table('log_history')->insert([
            'lead_id' => $id,
            'description' => $name,
            'file' => 1,
            'by_user_id' => Auth::user()->id,
        ]);
        return redirect()->back()->with('success', 'Image Uploaded');
    }

    public function save(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'label' => 'required',
            'departure_point' => 'required',
            'arrival_point' => 'required'
        ]);
        $accessToken = "iwnyrX7KxxpmvHDMaJcy60_I7z0TD3J9D2S6jOvxrFbBcQ4E";
        $data = array(
            'roles' => array(
                'customer' => array('active' => true)
            ),
            'company' => array(
                'name' => isset($request->firma_name) ? $request->firma_name : '',
                'contactPersons' => array(
                    array(
                        'firstName' => isset($request->name) ? $request->name : '',
                        'lastName' => isset($request->last_name) ? $request->last_name : '',
                        'primary' => true,
                        'emailAddress' => isset($request->email) ? $request->email : '',
                        'phoneNumber' => isset($request->phone) ? $request->phone : '',
                    )
                ),
            ),
            'addresses' => array(
                'billing' => array(
                    array(
                        "supplement" => isset($request->supplement) ? $request->supplement : '',
                        "street" => isset($request->street) ? $request->street : '',
                        "zip" => isset($request->zip_code) ? $request->zip_code : '',
                        "city" => isset($request->city) ? $request->city : '',
                        "countryCode" => isset($request->country_code) ? $request->country_code : ''
                    )
                ),
            ),
            'emailAddresses' => array(
                'private' => array(
                    isset($request->email) ? $request->email : '',
                )
            ),
            'phoneNumbers' => array(
                'private' => array(
                    isset($request->email) ? $request->email : '',
                )
            ),
            'note' => @$request->notizen
        );

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.lexoffice.io/v1/contacts');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization: Bearer ' . $accessToken,
            'Content-Type: application/json',
            'Accept: application/json'
        ));

        $response = curl_exec($ch);



        $responseArray = json_decode($response, true);

        $resuorceid = $responseArray['id'];
        $resourceUri = $responseArray['resourceUri'];
        $createdDate = $responseArray['createdDate'];
        $updatedDate = $responseArray['updatedDate'];
        $version = $responseArray['version'];
        $l_no = DB::table('leads')->latest('created_at')->first();
        if (isset($l_no)) {
            $last_no = $l_no->vnr;
            $new_no = $last_no + 1;
        } else {
            $new_no = '2000';
        }
        if ($resuorceid) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, "https://api.lexoffice.io/v1/contacts/{$resuorceid}");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

            $headers = [
                "Authorization: Bearer " . $accessToken,
                "Accept: application/json"
            ];

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response2 = curl_exec($ch);
            $responseArray2 = json_decode($response2, true);
            $customerNumber = $responseArray2['roles']['customer']['number'];

            DB::table('leads')->insert([
                'vnr' => @$new_no,
                'customer_number' => @$customerNumber,
                'firstname' => @$request->name,
                'lastname' => @$request->last_name,
                'firmaoptional' => @$request->firma_name,
                'email' => @$request->email,
                'phone' => @$request->phone,
                'grund' => @$request->label,
                'hinfahrt' => @$request->hinfahrt,
                'rueckfahrtt' => @$request->rückfahrt,
                'pax' => @$request->pax,
                'kundenbetreuer' => @$request->kundenbetreuer,
                'quelle' => @$request->quelle,
                'notizer' => @$request->notizen,
                'resuorceid' => @$resuorceid,
                'resourceuri' => @$resourceUri,
                'start' => @$request->street . ' ' . @$request->zip_code . ' ' . @$request->city,
                'ziel' => $request->arrival_point,
            ]);
            return redirect()->back()->with('success', 'Lead Added');
        }
        return redirect()->back()->with('error', 'Error!');
    }
}
