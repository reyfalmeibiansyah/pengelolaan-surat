<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TrixController extends Controller
{
    public function index()
    {
        return view('trix');
    }

    public function store(Request $request)
    {
        echo $request->input('content');
    }

    public function upload(Request $request)
{
    if($request->hasFile('file')) {
        $filenamewithextension = $request->file('file')->getClientOriginalName();

        $filename = pathinfo($filenamewithextension, PATHINFO_FILENAME);

        $extension = $request->file('file')->getClientOriginalExtension();

        $filenametostore = $filename.'_'.time().'.'.$extension;

        $request->file('file')->storeAs('public/uploads', $filenametostore);

        $path = asset('storage/uploads/'.$filenametostore);

        echo $path;
        exit;
    }
}
}