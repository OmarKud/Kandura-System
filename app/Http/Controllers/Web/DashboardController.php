<?php

namespace App\Http\Controllers\Web;

use App\Enum\StatusEnumUser;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{public function welcome()
{
    $totalUsers = User::count();

    $totalAdmins = User::whereHas('roles', function ($q) {
        $q->wherenot('name', 'superadmin')->wherenot('name', 'user');
    })->count();

    $totalSuperAdmins = User::whereHas('roles', function ($q) {
        $q->where('name', 'superadmin');
    })->count();

    $totalNormalUsers = User::whereHas('roles', function ($q) {
        $q->where('name', 'user');
    })->count();

  $totalActiveUsers = User::where('status', StatusEnumUser::Active)->count();
        $totalInactiveUsers = User::where('status', StatusEnumUser::INActive)->count();

        // New this month
        $newUsersThisMonth = User::whereBetween('created_at', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ])->count();

        // Recent Users
        $recentUsers = User::orderBy('created_at', 'desc')->take(6)->get();

        // ===== Chart: last 6 months =====
        $chartLabels = [];
        $chartData = [];

        for ($i = 5; $i >= 0; $i--) {
            $start = now()->subMonths($i)->startOfMonth();
            $end = now()->subMonths($i)->endOfMonth();

            $chartLabels[] = $start->format('Y-m');
            $chartData[] = User::whereBetween('created_at', [$start, $end])->count();
        }

        // ===== Schedule =====
        $schedule = [
            ['time' => 'Today 10:00', 'title' => 'Review new registered users'],
            ['time' => 'Today 14:00', 'title' => 'Check pending orders'],
            ['time' => 'Tomorrow 09:00', 'title' => 'Update design options'],
        ];

        $totalUsers-=$totalSuperAdmins;
        $totalActiveUsers-=$totalSuperAdmins;
    return view('dashboard.welcome', compact(
       'totalUsers',
            'totalAdmins',
            'totalSuperAdmins',
            'totalNormalUsers',
            'totalActiveUsers',
            'totalInactiveUsers',
            'newUsersThisMonth',
            'recentUsers',
            'chartLabels',
            'chartData',
            'schedule'
    ));
}}