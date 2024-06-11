<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Auth;

class CoronController extends Controller
{
    public function __construct()
    {
    }

    public function getLeads()
    {
        $accessToken = "iwnyrX7KxxpmvHDMaJcy60_I7z0TD3J9D2S6jOvxrFbBcQ4E";
        $current_leads = DB::table('leads')->latest('form_id')->first();
        if ($current_leads) {
            $Form_id = $current_leads->form_id;
        } else {
            $Form_id = 1;
        }
        $lead = DB::connection('second_database')->table('db7_forms')->where('send_to_lead', '=', 0)->get();

        foreach ($lead as $value) {
            $leadData = @unserialize($value->form_value);
            if ($leadData) {
                // $data = array(

                //     'roles' => array(
                //         'customer' => array('active' => true)
                //     ),
                //     'person' => array(
                //         'firstName' => @$leadData['Vorname'],
                //         'lastName' => @$leadData['Nachname'] || "",
                //         'email' => @$leadData['E-Mail'],
                //         'phone' => @$leadData['Telefon'],
                //         'cfdb7_status' => @$leadData['cfdb7_status'],
                //         'hinundrueck' => @implode(', ', $leadData['hinundrueck']),
                //         'start' => @$leadData['Start'],
                //         'hinfahrt' => @$leadData['Hinfahrt'],
                //         'menu_731' => @implode(', ', $leadData['menu-731']),
                //         'ziel' => @$leadData['Ziel'],
                //         'rueckfahrtt' => @$leadData['Rueckfahrtt'],
                //         'menu_732' => @implode(', ', $leadData['menu-732']),
                //         'pax' => @$leadData['Pax'],
                //         'grund' => @implode(', ', $leadData['Grund']),
                //         'reisebudget' => @$leadData['reisebudget'],
                //         'firmaoptional' => @$leadData['Firmaoptional'],
                //         'schuleUniversitt' => @$leadData['SchuleUniversitt'],
                //         'verein' => @$leadData['Verein'],
                //         'behoerdenname' => @$leadData['Behoerdenname'],
                //         'bemerkung' => @$leadData['Bemerkung'],
                //         'datenschutz' => @$leadData['Datenschutz'],
                //     ),
                //     'note' => 'Notiz2en'
                // );

                $grund = @implode(', ', $leadData['Grund']);

                if ($grund != "Privat") {
                    $companyName = "";
                    if ($grund == "Firma") {
                        $companyName = @$leadData['Firmaoptional'];
                    } elseif ($grund == "Schule/Universität" || $grund == "Schule&#047;Universität") {
                        $companyName = @$leadData['SchuleUniversitt'];
                    } elseif ($grund == "Verein") {
                        $companyName = @$leadData['Verein'];
                    } elseif ($grund == "Behörde") {
                        $companyName = @$leadData['Behoerdenname'];
                    }
                    $data = array(
                        'roles' => array(
                            'customer' => array('active' => true)
                        ),
                        'company' => array(
                            'name' => (isset($companyName) && $companyName != "") ? $companyName : 'Firmenname nicht vorhanden',
                            'contactPersons' => array(
                                array(
                                    'firstName' => (isset($leadData['Vorname']) && $leadData['Vorname'] != "") ? $leadData['Vorname'] : 'Vorname nicht vorhanden',
                                    'lastName' => (isset($leadData['Nachname']) && $leadData['Nachname'] != "") ? $leadData['Nachname'] : 'Nachname nicht vorhanden',
                                    'primary' => true,
                                    'emailAddress' => (isset($leadData['E-Mail']) && $leadData['E-Mail'] != "") ? $leadData['E-Mail'] : 'E-Mail nicht vorhanden',
                                    'phoneNumber' => (isset($leadData['Telefon']) && $leadData['Telefon'] != "") ? $leadData['Telefon'] : 'Telefon nicht vorhanden',
                                )
                            ),
                        ),
                        'emailAddresses' => array(
                            'private' => array(
                                (isset($leadData['E-Mail']) && $leadData['E-Mail'] != "") ? $leadData['E-Mail'] : 'E-Mail nicht vorhanden',
                            )
                        ),
                        'phoneNumbers' => array(
                            'private' => array(
                                (isset($leadData['Telefon']) && $leadData['Telefon'] != "") ? $leadData['Telefon'] : 'Telefon nicht vorhanden',
                            )
                        ),
                        'note' => ''
                    );
                } else {
                    $data = array(
                        'roles' => array(
                            'customer' => array('active' => true)
                        ),
                        'person' => array(
                            'firstName' => (isset($leadData['Vorname']) &&  $leadData['Vorname'] != "") ? $leadData['Vorname'] : 'Vorname nicht vorhanden',
                            'lastName' => (isset($leadData['Nachname']) && $leadData['Nachname'] != "") ? $leadData['Nachname'] : 'Nachname nicht vorhanden',
                        ),
                        'emailAddresses' => array(
                            'private' => array(
                                (isset($leadData['E-Mail']) && $leadData['E-Mail'] != "") ? $leadData['E-Mail'] : 'E-Mail nicht vorhanden',
                            )
                        ),
                        'phoneNumbers' => array(
                            'private' => array(
                                (isset($leadData['Telefon']) && $leadData['Telefon'] != "") ? $leadData['Telefon'] : 'Telefon nicht vorhanden',
                            )
                        ),
                        'note' => ''
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
                    continue;
                }

                $lead_update = DB::connection('second_database')->table('db7_forms')->where('form_id', '=', $value->form_id)->update([
                    'send_to_lead' => 1
                ]);



                $curl_2 = curl_init();
                curl_setopt_array($curl_2, array(
                    CURLOPT_URL => 'https://api.lexoffice.io/v1/contacts/' . $decodedResponse['id'],
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

                $response_1 = curl_exec($curl_2);
                $responseArray = json_decode($response_1, true);
                // dd($responseArray);
                $resuorceid = @$responseArray['id'];
                $customer_number = @$responseArray['roles']['customer']['number'];
                if (isset($resuorceid) && isset($customer_number)) {

                    $l_no = DB::table('leads')->latest('id')->first();
                    if (isset($l_no)) {
                        $last_no = $l_no->vnr;
                        $new_no = $last_no + 1;
                    } else {
                        $new_no = '2000';
                    }
                    if ($resuorceid) {
                        DB::table('leads')->insert([
                            'vnr' => @$new_no,
                            'form_id' => @$value->form_id,
                            'customer_number' => @$customer_number,
                            'quelle' => 'Online',
                            'firstname' => @$leadData['Vorname'],
                            'lastname' => @$leadData['Nachname'],
                            'email' => @$leadData['E-Mail'],
                            'phone' => @$leadData['Telefon'],
                            'cfdb7_status' => @$leadData['cfdb7_status'],
                            'hinundrueck' => @implode(', ', $leadData['hinundrueck']),
                            'start' => @$leadData['Start'],
                            'hinfahrt' => @$leadData['Hinfahrt'],
                            'menu_731' => @implode(', ', $leadData['menu-731']),
                            'ziel' => @$leadData['Ziel'],
                            'rueckfahrtt' => @$leadData['Rueckfahrtt'],
                            'menu_732' => @implode(', ', $leadData['menu-732']),
                            'pax' => @$leadData['Pax'],
                            'grund' => @implode(', ', $leadData['Grund']),
                            'reisebudget' => @$leadData['reisebudget'],
                            'firmaoptional' => @$leadData['Firmaoptional'],
                            'schuleUniversitt' => @$leadData['SchuleUniversitt'],
                            'verein' => @$leadData['Verein'],
                            'behoerdenname' => @$leadData['Behoerdenname'],
                            'bemerkung' => @$leadData['Bemerkung'],
                            'datenschutz' => @$leadData['Datenschutz'],
                            'resuorceid' => @$resuorceid,
                            'created_at' => @$value->form_date,
                        ]);
                    }
                }
            }
        }
        $this->getLeads2();
    }
    public function getLeads2()
    {
        $accessToken = "iwnyrX7KxxpmvHDMaJcy60_I7z0TD3J9D2S6jOvxrFbBcQ4E";
        $current_leads = DB::table('leads')->latest('form_id')->first();
        if ($current_leads) {
            $Form_id = $current_leads->form_id;
        } else {
            $Form_id = 1;
        }
        $lead = DB::connection('third_database')->table('wp_db7_forms')->where('send_to_lead', '=', 0)->get();
        // dd($lead_2);
        // $lead = DB::connection('second_database')->table('db7_forms')->where('send_to_lead', '=', 0)->get();

        foreach ($lead as $value) {
            $leadData = @unserialize($value->form_value);
            if ($leadData) {
                // $data = array(

                    //     'roles' => array(
                        //         'customer' => array('active' => true)
                //     ),
                //     'person' => array(
                //         'firstName' => @$leadData['Vorname'],
                //         'lastName' => @$leadData['Nachname'] || "",
                //         'email' => @$leadData['E-Mail'],
                //         'phone' => @$leadData['Telefon'],
                //         'cfdb7_status' => @$leadData['cfdb7_status'],
                //         'hinundrueck' => @implode(', ', $leadData['hinundrueck']),
                //         'start' => @$leadData['Start'],
                //         'hinfahrt' => @$leadData['Hinfahrt'],
                //         'menu_731' => @implode(', ', $leadData['menu-731']),
                //         'ziel' => @$leadData['Ziel'],
                //         'rueckfahrtt' => @$leadData['Rueckfahrtt'],
                //         'menu_732' => @implode(', ', $leadData['menu-732']),
                //         'pax' => @$leadData['Pax'],
                //         'grund' => @implode(', ', $leadData['Grund']),
                //         'reisebudget' => @$leadData['reisebudget'],
                //         'firmaoptional' => @$leadData['Firmaoptional'],
                //         'schuleUniversitt' => @$leadData['SchuleUniversitt'],
                //         'verein' => @$leadData['Verein'],
                //         'behoerdenname' => @$leadData['Behoerdenname'],
                //         'bemerkung' => @$leadData['Bemerkung'],
                //         'datenschutz' => @$leadData['Datenschutz'],
                //     ),
                //     'note' => 'Notiz2en'
                // );

                $grund = @implode(', ', $leadData['Grund']);

                if ($grund != "Privat") {
                    $companyName = "";
                    if ($grund == "Firma") {
                        $companyName = @$leadData['Firmaoptional'];
                    } elseif ($grund == "Schule/Universität" || $grund == "Schule&#047;Universität") {
                        $companyName = @$leadData['SchuleUniversitt'];
                    } elseif ($grund == "Verein") {
                        $companyName = @$leadData['Verein'];
                    } elseif ($grund == "Behörde") {
                        $companyName = @$leadData['Behoerdenname'];
                    }
                    $data = array(
                        'roles' => array(
                            'customer' => array('active' => true)
                        ),
                        'company' => array(
                            'name' => (isset($companyName) && $companyName != "") ? $companyName : 'Firmenname nicht vorhanden',
                            'contactPersons' => array(
                                array(
                                    'firstName' => (isset($leadData['Vorname']) && $leadData['Vorname'] != "") ? $leadData['Vorname'] : 'Vorname nicht vorhanden',
                                    'lastName' => (isset($leadData['Nachname']) && $leadData['Nachname'] != "") ? $leadData['Nachname'] : 'Nachname nicht vorhanden',
                                    'primary' => true,
                                    'emailAddress' => (isset($leadData['E-Mail']) && $leadData['E-Mail'] != "") ? $leadData['E-Mail'] : 'E-Mail nicht vorhanden',
                                    'phoneNumber' => (isset($leadData['Telefon']) && $leadData['Telefon'] != "") ? $leadData['Telefon'] : 'Telefon nicht vorhanden',
                                )
                            ),
                        ),
                        'emailAddresses' => array(
                            'private' => array(
                                (isset($leadData['E-Mail']) && $leadData['E-Mail'] != "") ? $leadData['E-Mail'] : 'E-Mail nicht vorhanden',
                            )
                        ),
                        'phoneNumbers' => array(
                            'private' => array(
                                (isset($leadData['Telefon']) && $leadData['Telefon'] != "") ? $leadData['Telefon'] : 'Telefon nicht vorhanden',
                            )
                        ),
                        'note' => ''
                    );
                } else {
                    $data = array(
                        'roles' => array(
                            'customer' => array('active' => true)
                        ),
                        'person' => array(
                            'firstName' => (isset($leadData['Vorname']) &&  $leadData['Vorname'] != "") ? $leadData['Vorname'] : 'Vorname nicht vorhanden',
                            'lastName' => (isset($leadData['Nachname']) && $leadData['Nachname'] != "") ? $leadData['Nachname'] : 'Nachname nicht vorhanden',
                        ),
                        'emailAddresses' => array(
                            'private' => array(
                                (isset($leadData['E-Mail']) && $leadData['E-Mail'] != "") ? $leadData['E-Mail'] : 'E-Mail nicht vorhanden',
                            )
                        ),
                        'phoneNumbers' => array(
                            'private' => array(
                                (isset($leadData['Telefon']) && $leadData['Telefon'] != "") ? $leadData['Telefon'] : 'Telefon nicht vorhanden',
                                )
                        ),
                        'note' => ''
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
                    continue;
                }

                $lead_update = DB::connection('third_database')->table('wp_db7_forms')->where('form_id', '=', $value->form_id)->update([
                    'send_to_lead' => 1
                ]);



                $curl_2 = curl_init();
                curl_setopt_array($curl_2, array(
                    CURLOPT_URL => 'https://api.lexoffice.io/v1/contacts/' . $decodedResponse['id'],
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

                $response_1 = curl_exec($curl_2);
                $responseArray = json_decode($response_1, true);
                // dd($responseArray);
                $resuorceid = @$responseArray['id'];
                $customer_number = @$responseArray['roles']['customer']['number'];
                if (isset($resuorceid) && isset($customer_number)) {

                    $l_no = DB::table('leads')->latest('id')->first();
                    if (isset($l_no)) {
                        $last_no = $l_no->vnr;
                        $new_no = $last_no + 1;
                    } else {
                        $new_no = '2000';
                    }
                    // dd($leadData['Bemerkung'],implode(', ', $leadData['Datenschutz']),$resuorceid,$value->form_date);
                    if ($resuorceid) {
                        DB::table('leads')->insert([
                            'vnr' => @$new_no,
                            'form_id' => @$value->form_id,
                            'customer_number' => @$customer_number,
                            'quelle' => 'Online',
                            'firstname' => @$leadData['Vorname'],
                            'lastname' => @$leadData['Nachname'],
                            'email' => @$leadData['E-Mail'],
                            'phone' => @$leadData['Telefon'],
                            'cfdb7_status' => @$leadData['cfdb7_status'],
                            'hinundrueck' => @implode(', ', $leadData['hinundrueck']),
                            'start' => @$leadData['Start'],
                            'hinfahrt' => @$leadData['Hinfahrt'],
                            'menu_731' => @implode(', ', $leadData['menu-731']),
                            'ziel' => @$leadData['Ziel'],
                            'rueckfahrtt' => @$leadData['Rueckfahrtt'],
                            'menu_732' => @implode(', ', $leadData['menu-732']),
                            'pax' => @$leadData['Pax'],
                            'grund' => @implode(', ', $leadData['Grund']),
                            'reisebudget' => @$leadData['reisebudget'],
                            'firmaoptional' => @$leadData['Firmaoptional'],
                            'schuleUniversitt' => @$leadData['SchuleUniversitt'],
                            'verein' => @$leadData['Verein'],
                            'behoerdenname' => @$leadData['Behoerdenname'],
                            'bemerkung' => @$leadData['Bemerkung'],
                            'datenschutz' => @implode(', ', $leadData['Datenschutz']),
                            'resuorceid' => @$resuorceid,
                            'created_at' => @$value->form_date,
                        ]);
                    }
                }
            }
        }
    }
    public function getLeadsNumber()
    {
    }
}

