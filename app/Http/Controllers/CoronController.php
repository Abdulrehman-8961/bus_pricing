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
        if($current_leads){
            $Form_id = $current_leads->form_id;
        } else {
            $Form_id = 1;
        }
        $lead = DB::connection('second_database')->table('db7_forms')->where('form_id', '>', $Form_id)->where('form_date','like','%'.date('Y-m-d').'%')->get();

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

                // $ch = curl_init();

                // curl_setopt($ch, CURLOPT_URL, 'https://api.lexoffice.io/v1/contacts');
                // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                // curl_setopt($ch, CURLOPT_POST, true);
                // curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
                // curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                //     'Authorization: Bearer ' . $accessToken,
                //     'Content-Type: application/json',
                //     'Accept: application/json'
                // ));

                // $response = curl_exec($ch);


                $curl = curl_init();

                curl_setopt_array($curl, array(
                    CURLOPT_URL => 'https://api.lexoffice.io/v1/contacts?email=' . @$leadData['E-Mail'],
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_ENCODING => '',
                    CURLOPT_MAXREDIRS => 10,
                    CURLOPT_TIMEOUT => 0,
                    CURLOPT_FOLLOWLOCATION => true,
                    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                    CURLOPT_CUSTOMREQUEST => 'GET',
                    CURLOPT_HTTPHEADER => array(
                        'Authorization: Bearer iwnyrX7KxxpmvHDMaJcy60_I7z0TD3J9D2S6jOvxrFbBcQ4E',
                        'Accept: application/json',
                        'Content-Type: application/json'
                    ),
                ));

                $response = curl_exec($curl);

                curl_close($curl);
                // echo $response;

                $data = json_decode($response, true);
                if (isset($data['content']) && !empty($data['content'])) {
                    $content = $data['content'][0];

                    $resuorceid = $content['id'];
                    $customer_number = $content['roles']['customer']['number'];
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
    }
    public function getLeadsNumber()
    {
    }
}
