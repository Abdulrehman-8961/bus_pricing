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
        $title = $this->title;
        // $lead = DB::table('db7_forms')->where('form_id',2085)->first();
        // var_dump($lead->form_value);
        // exit;
        // $leadData = unserialize($lead->form_value);
        // $leadData = [
        //     'firstName' => 'Sara',
        //     'lastName' => 'Blum-Kantarowska'
        // ];

        // $lexofficeApiUrl = 'https://api.lexoffice.io/v1/contacts';

        // $response = Http::withHeaders([
        //     'Authorization' => 'Bearer iwnyrX7KxxpmvHDMaJcy60_I7z0TD3J9D2S6jOvxrFbBcQ4E',
        //     'Content-Type' => 'application/json',
        // ])->post($lexofficeApiUrl, $leadData);
        // dd($response);

        // if ($response->successful()) {
        //     $lexofficeCustomerId = $response->json()['id'];
        //     // Store $lexofficeCustomerId in your local database for future reference
        //     // You can also redirect or return a success response
        // } else {
        //     // Handle error (e.g., log or notify admin)
        //     // You can redirect or return an error response
        // }

        return view("leads.view", compact("title"));
    }
}
