<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Auth;
use Exception;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LeadsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
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

    public function leads(Request $request)
    {
        $search = @$request->input('search');
        $archive = @$request->input('archive');
        $leads = DB::table('leads')->where('is_deleted', 0)->where('in_deal', 0)->where('is_archive', $archive)
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
        return response()->json(view("leads.component.table", compact("leads", "search"))->render());
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
            // $resuorceVersion = $responseArray['version'];
            if (isset($responseArray['company']['contactPersons'][0]['firstName'])) {
                $responseArray['company']['contactPersons'][0]['firstName'] = isset($nameParts[0]) ? $nameParts[0] : '';
            }
            if (isset($responseArray['company']['contactPersons'][0]['lastName'])) {
                $responseArray['company']['contactPersons'][0]['lastName'] = isset($nameParts[1]) ? $nameParts[1] : '';
            }
            if (isset($responseArray['person']['firstName'])) {
                $responseArray['person']['firstName'] = isset($nameParts[0]) ? $nameParts[0] : '';
            }
            if (isset($responseArray['person']['lastName'])) {
                $responseArray['person']['lastName'] = isset($nameParts[1]) ? $nameParts[1] : '';
            }
            curl_close($curl);

            // update
            // if (!$responseArray['person']) {
            //     $data = array(
            //         'version' => $resuorceVersion,
            //         'roles' => array(
            //             'customer' => array('number' => $leadData->customer_number)
            //         ),
            //         'company' => array(
            //             'contactPersons' => array(
            //                 "salutation" => "",
            //                 'firstName' => $nameParts[0],
            //                 'lastName' => $nameParts[1],
            //             ),
            //         ),
            //         'note' => 'Notiz2en'
            //     );
            // } else {
            //     $data = array(
            //         'version' => $resuorceVersion,
            //         'roles' => array(
            //             'customer' => array('number' => $leadData->customer_number)
            //         ),
            //         'person' => array(
            //             "salutation" => "",
            //             'firstName' => $nameParts[0],
            //             'lastName' => $nameParts[1],
            //         ),
            //         'note' => 'Notiz2en'
            //     );
            // }
            // dd(json_encode($data));

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://api.lexoffice.io/v1/contacts/' . $leadData->resuorceid);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($responseArray));
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
            if (isset($responseArray['company']['contactPersons'][0]['emailAddress'])) {
                $responseArray['company']['contactPersons'][0]['emailAddress'] = $request->input('email');
            }
            if (isset($responseArray['emailAddresses']['private'][0])) {
                $responseArray['emailAddresses']['private'][0] = $request->input('email');
            } else {
                if (!isset($responseArray['emailAddresses']['business'][0])) {
                    $responseArray['emailAddresses']['private'][0] = $request->input('email');
                }
            }
            if (isset($responseArray['emailAddresses']['business'][0])) {
                $responseArray['emailAddresses']['business'][0] = $request->input('email');
            }
            curl_close($curl);

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://api.lexoffice.io/v1/contacts/' . $leadData->resuorceid);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($responseArray));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
                'Accept: application/json'
            ));

            $response_2 = curl_exec($ch);
            // dd($response_2);
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
            // dd($responseArray['company']['contactPersons'][0]['phoneNumber']);
            if (isset($responseArray['company']['contactPersons'][0]['phoneNumber'])) {
                $responseArray['company']['contactPersons'][0]['phoneNumber'] = $request->input('phone');
            }
            if (isset($responseArray['phoneNumbers']['private'][0])) {
                $responseArray['phoneNumbers']['private'][0] = $request->input('phone');
            } else {
                if (!isset($responseArray['phoneNumbers']['business'][0])) {
                    $responseArray['phoneNumbers']['private'][0] = $request->input('phone');
                }
            }
            if (isset($responseArray['phoneNumbers']['business'][0])) {
                $responseArray['phoneNumbers']['business'][0] = $request->input('phone');
            }
            curl_close($curl);

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://api.lexoffice.io/v1/contacts/' . $leadData->resuorceid);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($responseArray));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
                'Accept: application/json'
            ));

            $response_2 = curl_exec($ch);
            // dd($response_2);
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
            if (isset($responseArray['company']['name'])) {
                $responseArray['company']['name'] = $request->input('company_name');
            }
            curl_close($curl);

            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://api.lexoffice.io/v1/contacts/' . $leadData->resuorceid);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($responseArray));
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
        sleep(2);
        return redirect()->back()->with('success', 'Lead updated');
    }
    public function updateLeadAddress(Request $request, $id)
    {
        $accessToken = "iwnyrX7KxxpmvHDMaJcy60_I7z0TD3J9D2S6jOvxrFbBcQ4E";
        $leadData = DB::table('leads')->where('id', $id)->first();
        $updateField = [];
        if ($request->has('street')) {
            $street = $request->input('street');
            $zip = $request->input('zip_code');
            $country = $request->input('country');
            $country_code = $request->input('country_code');

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
            // $resuorceVersion = $responseArray['version'];
            if (isset($responseArray['addresses']['billing'][0]['street'])) {
                $responseArray['addresses']['billing'][0]['street'] = isset($street) ? $street : '';
            } else {
                $responseArray['addresses']['billing'][0]['street'] = isset($street) ? $street : '';
            }
            if (isset($responseArray['addresses']['billing'][0]['zip'])) {
                $responseArray['addresses']['billing'][0]['zip'] = isset($zip) ? $zip : '';
            } else {
                $responseArray['addresses']['billing'][0]['zip'] = isset($zip) ? $zip : '';
            }
            if (isset($responseArray['addresses']['billing'][0]['city'])) {
                $responseArray['addresses']['billing'][0]['city'] = isset($country) ? $country : '';
            } else {
                $responseArray['addresses']['billing'][0]['city'] = isset($country) ? $country : '';
            }
            if (isset($responseArray['addresses']['billing'][0]['countryCode'])) {
                $responseArray['addresses']['billing'][0]['countryCode'] = isset($country_code) ? $country_code : '';
            } else {
                $responseArray['addresses']['billing'][0]['countryCode'] = isset($country_code) ? $country_code : '';
            }
            curl_close($curl);


            $ch = curl_init();

            curl_setopt($ch, CURLOPT_URL, 'https://api.lexoffice.io/v1/contacts/' . $leadData->resuorceid);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($responseArray));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: application/json',
                'Accept: application/json'
            ));

            $response_2 = curl_exec($ch);
            curl_close($ch);
        }
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
        dd($id);
        DB::table('leads')->where('id', $id)->update([
            'kundenbetreuer' => $request->employee
        ]);
        return redirect()->back()->with('success', 'Kundenbetreuer Updated');
    }
    public function updateEmployee_(Request $request)
    {
        $updateId = $request->updateId;
        DB::table('leads')->where('id', $updateId)->update([
            'kundenbetreuer' => $request->employee_id
        ]);
        return redirect()->back()->with('success', 'Kundenbetreuer Updated');
    }
    public function archive(Request $request, $id)
    {
        DB::table('leads')->where('id', $id)->update([
            'is_archive' => 1
        ]);
        return redirect()->back()->with('success', 'Lead erfolgreich archiviert');
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
            // 'file' => 1,
            'by_user_id' => Auth::user()->id,
        ]);
        return redirect()->back()->with('success', 'File Uploaded');
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
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://api.lexoffice.io/v1/contacts?email=' . @$request->email,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer iwnyrX7KxxpmvHDMaJcy60_I7z0TD3J9D2S6jOvxrFbBcQ4E',
                'Accept: application/json',
                'Content-Type: application/json',
            ],
        ]);

        $previousData = curl_exec($curl);

        curl_close($curl);
        // echo $response;

        $data1 = json_decode($previousData, true);


        if (empty($data1['content'])) {
            $accessToken = "iwnyrX7KxxpmvHDMaJcy60_I7z0TD3J9D2S6jOvxrFbBcQ4E";
            try {
                if ($request->label != "Privat") {
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
                                isset($request->phone) ? $request->phone : '',
                            )
                        ),
                        'note' => @$request->notizen
                    );
                } else {
                    $data = array(
                        'roles' => array(
                            'customer' => array('active' => true)
                        ),
                        'person' => array(
                            'firstName' => isset($request->name) ? $request->name : '',
                            'lastName' => isset($request->last_name) ? $request->last_name : '',
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
                                isset($request->phone) ? $request->phone : '',
                            )
                        ),
                        'note' => @$request->notizen
                    );
                }

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

                $decodedResponse = json_decode($response, true);
                if (isset($decodedResponse['IssueList'])) {
                    $errorMessages = [];
                    foreach ($decodedResponse['IssueList'] as $issue) {
                        $errorMessages[] = $issue['i18nKey'];
                    }
                    throw new Exception(implode(', ', $errorMessages));
                }
            } catch (Exception $e) {
                // echo "Error: " . $e->getMessage();
                return redirect()->back()->with('error', 'Etwas ist schief gelaufen. Bitte versuche es erneut');
            }


            // dd($response);

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

                $hinfahrtParts = explode('T', @$request->hinfahrt);
                $hinfahrt = @$hinfahrtParts[0];
                $menu_731 = @$hinfahrtParts[0];

                $rückfahrtParts = explode('T', @$request->rückfahrt);
                $rückfahrt = @$rückfahrtParts[0];
                $menu_732 = @$rückfahrtParts[0];

                // dd($hinfahrtParts,$rückfahrtParts);

                DB::table('leads')->insert([
                    'vnr' => @$new_no,
                    'customer_number' => @$customerNumber,
                    'firstname' => @$request->name,
                    'lastname' => @$request->last_name,
                    'firmaoptional' => @$request->firma_name,
                    'email' => @$request->email,
                    'phone' => @$request->phone,
                    'grund' => @$request->label,
                    'hinfahrt' => @$hinfahrt,
                    'menu_731' => @$menu_731,
                    'rueckfahrtt' => @$rückfahrt,
                    'menu_732' => @$menu_732,
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
        return redirect()->back()->with('error', 'Kontakt existiert bereits');
    }

    public function download_file(Request $request)
    {
        $accessToken = "iwnyrX7KxxpmvHDMaJcy60_I7z0TD3J9D2S6jOvxrFbBcQ4E"; // Replace with your access token
        $v_id = $request->v_id;
        $v_type = $request->v_type;

        if ($v_type == 'invoice') {
            $url = "https://api.lexoffice.io/v1/invoices/{$v_id}/document";
        }
        if ($v_type == 'quotation') {
            $url = "https://api.lexoffice.io/v1/quotations/{$v_id}/document";
        }
        if ($v_type == 'orderconfirmation') {
            $url = "https://api.lexoffice.io/v1/order-confirmations/{$v_id}/document";
        }

        if ($url) {

            $ch = curl_init($url);

            // Set cURL options
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer {$accessToken}",
                "Accept: application/json"
            ]);

            // Execute the GET request
            $response = curl_exec($ch);
            $data = json_decode($response, true);
            // dd($data['documentFileId']);

            if (curl_errno($ch)) {
                echo 'Error:' . curl_error($ch);
            }

            curl_close($ch);

            // The API endpoint and file ID
            $fileId = $data['documentFileId']; // Replace with the actual file ID


            try {
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Accept' => '*/*',
                ])->get("https://api.lexoffice.io/v1/files/{$fileId}");

                if ($response->successful()) {
                    $fileData = $response->body();
                    $contentType = $response->header('Content-Type');
                    $contentDisposition = $response->header('Content-Disposition');

                    // Extract file name from Content-Disposition header
                    $fileName_ = 'file_' . now();
                    if ($contentDisposition) {
                        preg_match('/filename=("?)([^"\s;]+)\1?/i', $contentDisposition, $matches);
                        if (isset($matches[2])) {
                            $fileName_ = $matches[2];
                        }
                    }
                    // if ($contentDisposition && preg_match('/filename="(.+)"/', $contentDisposition, $matches)) {
                    //     dd('');
                    //     $fileName = $matches[1];
                    // }

                    // Return file as downloadable response
                    // dd($fileName_);
                    return response()->make($fileData)
                        ->header('Content-Type', $contentType)
                        ->header('Content-Disposition', 'inline; filename="' . $fileName_ . '"');
                    // "inline; filename=Angebot_AG6599.pdf;"
                } else {
                    // Handle unsuccessful response
                    // return response()->json(['error' => 'Failed to download file: ' . $response->status()], $response->status());
                }
            } catch (\Exception $e) {
                // Handle exception
                // return response()->json(['error' => 'Failed to download file: ' . $e->getMessage()], 500);
            }

            // // Initialize a cURL session
            // $curl_2 = curl_init();

            // // Set the URL
            // $url_2 = "https://api.lexoffice.io/v1/files/" . $fileId;
            // curl_setopt($curl_2, CURLOPT_URL, $url_2);

            // // Set the headers
            // $headers = [
            //     "Accept: */*",
            //     "Authorization: Bearer " . $accessToken
            //     ];
            //     curl_setopt($curl_2, CURLOPT_HTTPHEADER, $headers);

            //     // Return the response as a string instead of outputting it
            //     curl_setopt($curl_2, CURLOPT_RETURNTRANSFER, true);

            //     // Execute the request
            //     $fileData = curl_exec($curl_2);

            //     if (curl_errno($curl_2)) {
            //         // Handle cURL error
            //         $errorMessage = curl_error($curl_2);
            //         curl_close($curl_2);
            //         return response()->json(['error' => 'Failed to download file: ' . $errorMessage], 500);
            //     }

            //     // Get content type and suggested file name from headers
            //     $contentType = curl_getinfo($curl_2, CURLINFO_CONTENT_TYPE);
            //     $contentDisposition = curl_getinfo($curl_2, CURLINFO_CONTENT_DISPOSITION);
            //     dd($contentDisposition);
            //     curl_close($curl_2);

            //     // Return file as downloadable response
            //     if ($fileData) {
            //         $response = response()->make($fileData);
            //         $response->header('Content-Type', $contentType);
            //         $response->header('Content-Disposition', 'attachment; filename="file_name.pdf"');
            //         return $response;
            //     } else {
            //         return response()->json(['error' => 'Failed to download file: Invalid file data'], 500);
            //     }
            return redirect()->back();
        }

        return redirect()->back()->with('error', 'Etwas ist schief gelaufen. Bitte versuche es erneut');
    }
}
