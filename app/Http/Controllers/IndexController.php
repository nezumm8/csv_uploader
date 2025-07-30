<?php

namespace App\Http\Controllers;

use App\Models\Data;
use App\Models\Headers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Jobs\ImportCSVJob;

class IndexController extends Controller
{
    public function show()
    {
        $headers = Headers::all();

        return view('index', compact('headers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv'
        ]);

        $filename = $request->file('file')->getClientOriginalName();

        $request->file('file')->storeAs('uploads', $filename);

        $headers = new Headers();
        $headers->filename = $filename;
        $headers->status = 'pending';
        $headers->save();

        importCSVJob::dispatch($filename);

        return redirect('/');
    }
}
