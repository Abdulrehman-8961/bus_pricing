<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
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
        $category = @$request->get('category');
        $search = @$request->get('search');
        $leads = DB::table('leads')->where('is_deleted', 0)->where('in_deal', 1)
            ->where(function ($query) use ($category, $search) {
                if (empty($category)) {
                    $query->where('customer_number', 'LIKE', '%' . $search . '%')
                        ->orWhere('firstname', 'LIKE', '%' . $search . '%')
                        ->orWhere('lastname', 'LIKE', '%' . $search . '%')
                        ->orWhere('firmaoptional', 'LIKE', '%' . $search . '%')
                        ->orWhere('email', 'LIKE', '%' . $search . '%')
                        ->orWhere('phase', 'LIKE', '%' . $search . '%')
                        ->orWhere('grund', 'LIKE', '%' . $search . '%');
                } elseif (!empty($category)) {
                    if ($category == "kunden_nr") {
                        $query->where('customer_number', 'LIKE', '%' . $search . '%');
                    } elseif ($category == "firmenname") {
                        $query->where('firmaoptional', 'LIKE', '%' . $search . '%');
                    } elseif ($category == "kundenname") {
                        $query->where('firstname', 'LIKE', '%' . $search . '%')->orWhere('lastname', 'LIKE', '%' . $search . '%');
                    } elseif ($category == "label") {
                        $query->where('grund', 'LIKE', '%' . $search . '%');
                    } elseif ($category == "email") {
                        $query->where('email', 'LIKE', '%' . $search . '%');
                    } elseif ($category == "phase") {
                        $query->where('phase', 'LIKE', '%' . $search . '%');
                    }
                }
            })
            ->paginate(20);

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
    public function edit(Request $request, $id)
    {
        $leads = DB::table('leads')->where('id', $id)->first();
        return view('deal.Edit', compact("leads"));
    }


    public function exportToCSV(Request $request)
    {
        $category = $request->input('category');
        $search = $request->input('search');
        $data = DB::table('leads')->where('is_deleted', 0)->where('in_deal', 1)
            ->where(function ($query) use ($category, $search) {
                if (empty($category)) {
                    $query->where('customer_number', 'LIKE', '%' . $search . '%')
                        ->orWhere('firstname', 'LIKE', '%' . $search . '%')
                        ->orWhere('lastname', 'LIKE', '%' . $search . '%')
                        ->orWhere('firmaoptional', 'LIKE', '%' . $search . '%')
                        ->orWhere('email', 'LIKE', '%' . $search . '%')
                        ->orWhere('phase', 'LIKE', '%' . $search . '%')
                        ->orWhere('grund', 'LIKE', '%' . $search . '%');
                } elseif (!empty($category)) {
                    if ($category == "kunden_nr") {
                        $query->where('customer_number', 'LIKE', '%' . $search . '%');
                    } elseif ($category == "firmenname") {
                        $query->where('firmaoptional', 'LIKE', '%' . $search . '%');
                    } elseif ($category == "kundenname") {
                        $query->where('firstname', 'LIKE', '%' . $search . '%')->orWhere('lastname', 'LIKE', '%' . $search . '%');
                    } elseif ($category == "label") {
                        $query->where('grund', 'LIKE', '%' . $search . '%');
                    } elseif ($category == "email") {
                        $query->where('email', 'LIKE', '%' . $search . '%');
                    } elseif ($category == "phase") {
                        $query->where('phase', 'LIKE', '%' . $search . '%');
                    }
                }
            })
            ->get();

        $csvData = [];
        foreach ($data as $row) {
            $csvData[] = [
                'Customer Number' => $row->customer_number,
                'First Name' => $row->firstname,
                'Last Name' => $row->lastname,
                'Firm Name' => $row->firmaoptional,
                'Email' => $row->email,
                'Label' => $row->grund,
                'Phase' => $row->phase,
            ];
        }

        $filename = 'export.csv';

        // Generate CSV file
        $file = fopen('php://temp', 'w');
        fputcsv($file, array_keys($csvData[0])); // Add header row
        foreach ($csvData as $row) {
            fputcsv($file, $row);
        }
        rewind($file);
        $csv = stream_get_contents($file);
        fclose($file);

        // Download CSV file
        return Response::streamDownload(function () use ($csv) {
            echo $csv;
        }, $filename);
    }
}
