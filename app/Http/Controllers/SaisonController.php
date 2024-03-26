<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Auth;

class SaisonController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->title = "Saison";
    }

    public function view(Request $request)
    {
        $title = $this->title;
        $startDate = null;
        $endDate = null;
        $search = @$request->get('dateRange');
        if ($search) {
            $dateParts = explode(" - ", $search);
            $startDate = date("Y-m-d", strtotime($dateParts[0]));
            $endDate = date("Y-m-d", strtotime($dateParts[1]));
        }
        $saison = DB::table('saison')
            ->where('is_deleted', '=', 0)
            ->where(function ($query) use ($search, $startDate, $endDate) {
                if (!empty($search)) {
                    $query->where(function ($query) use ($startDate, $endDate) {
                        $query->where('start_zeitraum', '<=', $startDate)
                            ->where('end_zeitraum', '>=', $startDate);
                    })
                        ->orWhere(function ($query) use ($startDate, $endDate) {
                            $query->where('start_zeitraum', '<=', $endDate)
                                ->where('end_zeitraum', '>=', $endDate);
                        });
                }
            })
            ->orderBy('id', 'desc')->paginate(20);
        $saison->appends([
            "search" => $search,
        ]);

        return view("saison.view", compact("saison", "title", "startDate", "endDate"));
    }
    public function add()
    {
        return view("users.UsersAdd");
    }
    public function save(Request $request)
    {
        $request->validate([
            "zeitraum" => 'required',
            "name" => 'required',
            "presierhohung" => 'required',
            "meldung" => 'required',
        ]);
        $dateRange = $request->input('zeitraum');
        $dateParts = explode(" - ", $dateRange);

        $startDate = date("Y-m-d", strtotime($dateParts[0]));
        $endDate = date("Y-m-d", strtotime($dateParts[1]));
        DB::table('saison')->insert([
            "start_zeitraum" => $startDate,
            "end_zeitraum" => $endDate,
            "name" => $request->input('name'),
            "presierhohung" => $request->input('presierhohung'),
            "meldung" => $request->input('meldung')
        ]);
        return redirect()->back()->with('success', "Saison added");
    }
    public function edit($id)
    {
        $title = $this->title;
        $saison = DB::table('saison')
            ->where('id', $id)
            ->first();
        return view("saison.edit", compact("saison", "title"));
    }
    public function update(Request $request, $id)
    {
        // dd(session('selected_id'));
        $request->validate([
            "zeitraum" => 'required',
            "name" => 'required',
            "presierhohung" => 'required',
            "meldung" => 'required',
        ]);
        $dateRange = $request->input('zeitraum');
        $dateParts = explode(" - ", $dateRange);

        $startDate = date("Y-m-d", strtotime($dateParts[0]));
        $endDate = date("Y-m-d", strtotime($dateParts[1]));
        DB::table('saison')->where('id', $id)->update([
            "start_zeitraum" => $startDate,
            "end_zeitraum" => $endDate,
            "name" => $request->input('name'),
            "presierhohung" => $request->input('presierhohung'),
            "meldung" => $request->input('meldung'),
            "updated_at" => date("Y-m-d H:i:s")
        ]);
        return redirect()->back()->with('success', 'Saison updated');
    }
    public function delete($id)
    {
        DB::table('saison')->where('id', $id)->update([
            'is_deleted' => 1
        ]);
        return redirect()->back()->with('success', 'Saison deleted');
    }
}
