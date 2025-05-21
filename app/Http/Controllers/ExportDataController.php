<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ExportDataController extends Controller
{
    public function exportOrder(Request $request) {
        return view('export_data.orders');
    }
}
