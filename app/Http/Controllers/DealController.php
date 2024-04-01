<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Auth;

class DealController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->title = "Deal";
    }

    public function view(Request $request)
    {
        $title = $this->title;
        $search = @$request->get('search');
        $leads = DB::table('leads')->where('is_deleted', 0)->where('in_deal',1)->paginate(20);

        return view("deal.view", compact("title", "leads"));
    }
    public function phase_update(Request $request, $id)
    {
        $phase = $request->get('phase');
        DB::table('leads')->where('id', $id)->update([
            'phase' => $phase
        ]);
        return redirect()->back()->with('success', 'Phase Updated');
    }
    public function edit(Request $request, $id){
        $leads = DB::table('leads')->where('id',$id)->first();
        return view('deal.Edit', compact("leads"));
    }
}
