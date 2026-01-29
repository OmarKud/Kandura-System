<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Address;
use Illuminate\Http\Request;

class DashboardAddressController extends Controller
{
    public function index(Request $request)
    {
        $query = Address::with('user');

        // 🔍 search عام (city / build / user name)
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('city', 'like', "%{$search}%")
                    ->orWhere('build', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($uq) use ($search) {
                        $uq->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // 🎯 filter by city
        if ($city = $request->input('city')) {
            $query->where('city', 'like', "%{$city}%");
        }

        // 🎯 filter by user name
        if ($userName = $request->input('user_name')) {
            $query->whereHas('user', function ($uq) use ($userName) {
                $uq->where('name', 'like', "%{$userName}%");
            });
        }

        // 🔃 sort (نفس اللي بالواجهة)
        $allowedSort = ['city', 'build', 'latitude', 'longitude'];
        $sortBy  = $request->input('sort_by', 'city');
        $sortDir = $request->input('sort_dir', 'asc');

        if (!in_array($sortBy, $allowedSort)) {
            $sortBy = 'city';
        }
        if (!in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'asc';
        }

        $query->orderBy($sortBy, $sortDir);

        // 📄 pagination
        $perPage = (int) $request->input('per_page', 10);
        if ($perPage > 50) $perPage = 50;

        $locations = $query->paginate($perPage)->withQueryString();

        return view('dashboard.addresses.index', compact('locations'));
    }
}
