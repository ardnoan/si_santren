<?php

namespace App\Http\Controllers\Pemimpin;

use App\Http\Controllers\Controller;
use App\Models\Santri;
use App\Models\Kas;
use App\Models\Nilai;
use App\Models\Kehadiran;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Redirect to main dashboard controller
        // Karena logic sudah ada di DashboardController utama
        return app(\App\Http\Controllers\DashboardController::class)->index();
    }
}