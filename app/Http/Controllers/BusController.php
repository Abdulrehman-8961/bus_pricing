<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class BusController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->title = "Bustypen (GruppengroBen)";
    }

    public function view(Request $request)
    {
        $title = $this->title;
        $search = @$request->get('search');
        $bus_type = DB::table('bus_type')
            ->where(function ($query) use ($search) {
                if (!empty($search)) {
                    $query->where('name', 'LIKE', '%' . $search . '%')
                        ->orWhere('city_transfer', 'LIKE', '%' . $search . '%')
                        ->orWhere('one_way_transfer', 'LIKE', '%' . $search . '%')
                        ->orWhere('short_usage', 'LIKE', '%' . $search . '%')
                        ->orWhere('half_day_trip', 'LIKE', '%' . $search . '%')
                        ->orWhere('full_day_trip', 'LIKE', '%' . $search . '%');
                }
            })->where('is_deleted', 0)
            ->orderBy('id', 'desc')->paginate(20);
        $bus_type->appends([
            "search" => $search,
        ]);

        return view("bustypes.busTypes", compact("bus_type", "title"));
    }
    public function add()
    {
        return view("bustypes.add");
    }
    public function save(Request $request)
    {
        $insertId = DB::table('bus_type')->insertGetId([
            "name" => $request->input('name'),
            "capacity" => $request->input('capacity'),
            "city_transfer" => $request->input('city_transfer'),
            "one_way_transfer" => $request->input('one_way_transfer'),
            "short_usage" => $request->input('short_usage'),
            "half_day_trip" => $request->input('half_day_trip'),
            "full_day_trip" => $request->input('full_day_trip'),
            "max" => $request->input('max'),
        ]);
        if ($request->has('kilometers') && $request->has('price')) {
            $kilometers = $request->input('kilometers');
            $prices = $request->input('price');
            foreach ($kilometers as $key => $r) {
                DB::table('price_per_km')->insert([
                    'bus_type_id' => $insertId,
                    'kilometers' => $r,
                    'price' => $prices[$key]
                ]);
            }
        }

        return redirect()->back()->with('success', "Bus Added");
    }

    public function edit($id)
    {
        $title = $this->title;
        $row = DB::table('bus_type')->where('id', $id)->where('is_deleted', 0)->first();
        $price = DB::table('price_per_km')->where('bus_type_id', $row->id)->get();
        return view("bustypes.edit", compact("row", "price", "title"));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            "name" => 'required'
        ]);
        if ($validated) {
            $bustype = DB::table('bus_type')->where('id', $id)->update([
                "name" => $request->input('name'),
                "capacity" => $request->input('capacity'),
                "city_transfer" => $request->input('city_transfer'),
                "one_way_transfer" => $request->input('one_way_transfer'),
                "short_usage" => $request->input('short_usage'),
                "half_day_trip" => $request->input('half_day_trip'),
                "full_day_trip" => $request->input('full_day_trip'),
                "max" => $request->input('max'),
                "updated_at" => now()
            ]);
            if ($request->has('kilometers') || $request->has('price')) {
                $kilometers = $request->input('kilometers');
                $data = [];
                foreach ($kilometers as $key => $r) {
                    $data[] = [
                        'bus_type_id' => $id,
                        'kilometers' => $r,
                        'price' => $request->price[$key]
                    ];
                }
                DB::table('price_per_km')->where('bus_type_id', $id)->delete();
                DB::table('price_per_km')->insert($data);
            }
            return redirect()->back()->with('success', 'Bus updated');
        }
    }
    public function delete($id)
    {
        DB::table('bus_type')->where('id', $id)->update([
            'is_deleted' => 1
        ]);
        return redirect()->back()->with('success', 'Bus deleted');
    }
    public function bus_details($id)
    {

        $item = DB::table('bus_type')->where('id', $id)->where('is_deleted', 0)->first();
        $price = DB::table('price_per_km')->where('bus_type_id', $item->id)->get();
        $html = view('component.busDetails', compact('item', 'price'))->render();
        return response()->json(['html' => $html]);
    }
}
