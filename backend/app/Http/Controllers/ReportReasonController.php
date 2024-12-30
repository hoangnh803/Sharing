<?php

namespace App\Http\Controllers;

use App\Models\ReportReason;
use Illuminate\Http\Request;

class ReportReasonController extends Controller
{
    public function index()
    {
        $reportReasons = ReportReason::all();
        return response()->json($reportReasons);
    }
}