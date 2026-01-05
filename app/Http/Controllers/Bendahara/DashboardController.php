<?php

namespace App\Http\Controllers\Bendahara;

use App\Http\Controllers\Controller;
use App\Models\Pembayaran;
use App\Models\Pengeluaran;
use App\Models\Kas;
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