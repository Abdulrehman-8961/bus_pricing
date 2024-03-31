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
        $current_leads = DB::table('leads')->latest('created_at')->first();
        $Form_id = $current_leads->form_id;
        $lead = DB::connection('second_database')->table('db7_forms')->where('form_id', '>', $Form_id)->get();

        foreach ($lead as $value) {
            $leadData = @unserialize($value->form_value);
            if ($leadData) {
                $data = array(

                    'roles' => array(
                        'customer' => array('active' => true)
                    ),
                    'person' => array(
                        'firstName' => @$leadData['Vorname'],
                        'lastName' => @$leadData['Nachname'] || "",
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
                    ),
                    'note' => 'Notiz2en'
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
                // dd($resuorceid);
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
                        'form_id' => @$value->form_id,
                        'customer_number' => @$customerNumber,
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
                        'resourceuri' => @$resourceUri,
                    ]);
                }

                // if ($response === false) {
                //     echo 'Curl error: ' . curl_error($ch);
                // } else {
                //     echo 'Response: ' . $response;
                // }

                curl_close($ch);
            }
        }
    }
    public function getLeadsNumber()
    {

    }
}
