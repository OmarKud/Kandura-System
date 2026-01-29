<?php

namespace App\service\Web;

use App\Models\Design;

class DesignService
{
    public function __construct(){}

  public function index($request)
{
    $locale = app()->getLocale();

    $designs = Design::with([
        'user',
        'images',
        'measurements', // ✅ بدل measurement
        'optionSelections.designOption',
    ]);

    // Search
    if ($search = $request->input('search')) {
        $designs->where(function ($q) use ($search, $locale) {
            $q->where("name->$locale", 'like', "%{$search}%")
              ->orWhere("description->$locale", 'like', "%{$search}%")
              ->orWhereHas('user', function ($u) use ($search) {
                  $u->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
              });
        });
    }

    // Filter by user name
    if ($userName = $request->input('user_name')) {
        $designs->whereHas('user', function ($u) use ($userName) {
            $u->where('name', 'like', "%{$userName}%");
        });
    }

    // ✅ Filter by measurement_id (many-to-many)
    if ($measurementId = $request->input('measurement_id')) {
        $designs->whereHas('measurements', function ($m) use ($measurementId) {
            $m->where('measurements.id', $measurementId);
        });
    }

    // ✅ Filter by size (many-to-many)
    if ($size = $request->input('size')) {
        $designs->whereHas('measurements', function ($m) use ($size) {
            $m->where('size', $size);
        });
    }

    // Price range
    if ($request->filled('min_price')) {
        $designs->where('price', '>=', $request->input('min_price'));
    }
    if ($request->filled('max_price')) {
        $designs->where('price', '<=', $request->input('max_price'));
    }

    // Filter by design option
    if ($designOptionId = $request->input('design_option_id')) {
        $designs->whereHas('optionSelections', function ($q) use ($designOptionId) {
            $q->where('design_option_id', $designOptionId);
        });
    }

    // Sorting
    $sortBy = $request->input('sort_by', 'id');
    $sortDir = $request->input('sort_dir', 'desc');
    if (in_array($sortBy, ['id','price','created_at'])) {
        $designs->orderBy($sortBy, $sortDir === 'asc' ? 'asc' : 'desc');
    } else {
        $designs->orderBy('id', 'desc');
    }

    $perPage = (int) $request->input('per_page', 10);
    return $designs->paginate($perPage)->withQueryString();
}

}
