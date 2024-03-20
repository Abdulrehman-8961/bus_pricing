<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Auth;
use ZipArchive;

class BundeslanderController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->title = "Bundesländer";
    }

    public function view(Request $request)
    {
        $title = $this->title;
        $search = @$request->get('search');
        $data = DB::table('bundeslander')
        ->where('is_deleted','=',0)
        ->where(function ($query) use ($search) {
            // if(!empty($search)) {
            //     $query->where('name', 'LIKE', '%'.$search.'%')
            //     ->orWhere('last_name', 'LIKE', '%'.$search.'%')
            //     ->orWhere('email', 'LIKE', '%'.$search.'%')
            //     ->orWhere('phone', 'LIKE', '%'.$search.'%')
            //     ->orWhere('address', 'LIKE', '%'.$search.'%');
            // }
        })
        ->orderBy('id', 'desc')->paginate(20);
        $data->appends([
          "search" => $search,
        ]);

        return view("bundeslander.view", compact("data","title"));
    }
    public function save(Request $request)
    {
        $request->validate([
            "bundsland" => 'required',
            "presierhohung" => 'required',
        ]);

        $insertId = DB::table('bundeslander')->insertGetId([
            "bundsland" => $request->input('bundsland'),
            "presierhohung" => $request->input('presierhohung'),
            "meldung" => $request->input('meldung')
        ]);
        if($request->hasFile('file')){
            $fileNames = [];
            $file = $request->file('file');
            foreach ($file as $f){
                $filePath = $f->getClientOriginalName();
                $fileType = $f->getMimeType();
                $f->move(public_path('uploads'), $filePath);
                $fileNames[] = [
                    'name' => $filePath,
                    'type' => $fileType,
                    'bundeslander_id' => $insertId
                ];
            }
            DB::table('bundeslander_files')->insert($fileNames);
        }
        return redirect()->back()->with('success', "bundsland added");
    }
    public function edit($id)
    {
        $title = $this->title;
        $data = DB::table('bundeslander')
        ->where('id', $id)
        ->first();
        $files = DB::table('bundeslander_files')->where('bundeslander_id', $id)->get();

        return view("bundeslander.edit", compact("data","files","title"));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            "bundsland" => 'required',
            "presierhohung" => 'required',
            "meldung" => 'required',
        ]);

        DB::table('bundeslander')->where('id', $id)->update([
            "bundsland" => $request->input('bundsland'),
            "presierhohung" => $request->input('presierhohung'),
            "meldung" => $request->input('meldung'),
            "updated_at" => date("Y-m-d H:i:s")
        ]);

        if($request->hasFile('file')){
            $fileNames = [];
            $file = $request->file('file');
            foreach ($file as $f){
                $filePath = $f->getClientOriginalName();
                $fileType = $f->getMimeType();
                $f->move(public_path('uploads'), $filePath);
                $fileNames[] = [
                    'name' => $filePath,
                    'type' => $fileType,
                    'bundeslander_id' => $id
                ];
            }
            DB::table('bundeslander_files')->insert($fileNames);
        }
        return redirect()->back()->with('success', 'bundsland updated');
    }
    public function delete($id)
    {
        DB::table('bundeslander')->where('id', $id)->update([
            'is_deleted' => 1
        ]);
        return redirect()->back()->with('success', 'bundsland deleted');
    }


    public function updateImg(Request $request)
    {
        $id = $request->input('update_id');
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            DB::table('bundeslander_files')->where('id', $id)->update([
                'name' => $filename
            ]);
            return redirect()->back()->with('success', "Image Updated Succesfully");
        } else {
            return redirect()->back()->with('error', "Something went wrong");
        }
    }
    public function deleteImg($id)
    {
        DB::table('bundeslander_files')->where('id', $id)->delete();
        return redirect()->back()->with('success', "Image Deleted Succesfully");
    }

    public function downloadFiles($id)
{
    $fileNames = DB::table('bundeslander_files')->where('bundeslander_id', $id)->pluck('name');

    $zip = new ZipArchive;
    $zipFileName = 'files.zip';
    $zipFilePath = sys_get_temp_dir() . '/' . $zipFileName;

    if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
        foreach ($fileNames as $fileName) {
            $fileContent = public_path("uploads/{$fileName}");
            $zip->addFile($fileContent, $fileName);
        }

        $zip->close();

        // Stream the zip file to the user for download
        return response()->download($zipFilePath)->deleteFileAfterSend(true);
    } else {
        return response()->json(['message' => 'Failed to create zip archive'], 500);
    }
}
}
