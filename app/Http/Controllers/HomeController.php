<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Redirect;
use DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->title = "Dashboard";
        $this->sessionID = session('selected_id');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $title = $this->title;
        $currentDate = date('Y-m-d');
        $nextSeasonData = DB::table('saison')
            ->whereDate('start_zeitraum', '>=', $currentDate)
            ->whereDate('start_zeitraum', '<=', date('Y-m-d', strtotime('+90 days')))
            ->where('is_deleted', 0)
            ->get();
        // dd($nextSeasonData);
        return view('home', compact("title", "nextSeasonData"));
    }

    public function setting()
    {
        $setting = DB::table('support_setting')->where('id', 1)->first();
        return view('setting', compact("setting"));
    }

    public function settingSave(Request $request)
    {
        $request->validate([
            "link" => 'required'
        ]);
        DB::table('support_setting')->where('id', 1)->update([
            'link' => $request->input('link')
        ]);
        return redirect()->back()->with('success', 'Link updated');
    }
}
