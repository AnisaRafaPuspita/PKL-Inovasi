<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_innovations' => 280,
            'total_innovators'  => 179,
            'total_visited'     => 1000,
        ];

        $chartLabels = ['Jan 1','Jan 8','Jan 16','Jan 24','Feb 1','Feb 8','Feb 16','Feb 24'];
        $chartData   = [300, 450, 600, 680, 620, 900, 940, 1000];

        $innovatorOfMonth = [
            'name'       => 'Alisha Fatima Putri Kurniawan',
            'faculty'    => '',
            'innovation' => '',
            'photo'      => null,
        ];

        return view('admin.dashboard', compact(
            'stats',
            'chartLabels',
            'chartData',
            'innovatorOfMonth'
        ));
    }
}
