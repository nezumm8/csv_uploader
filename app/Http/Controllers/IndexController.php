<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jobs\ImportCSVJob;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller
{
    public function show()
    {
        return view('index');
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv'
        ]);


        $filename = $request->file('file')->getClientOriginalName();

        $request->file('file')->storeAs('uploads', $filename);

        importCSVJob::dispatch($filename);

        return redirect('/');
    }
}
