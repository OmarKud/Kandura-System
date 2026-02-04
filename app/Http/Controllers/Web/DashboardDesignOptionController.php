<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\DesignOption;
use Illuminate\Http\Request;

class DashboardDesignOptionController extends Controller
{
    public function index(Request $request)
    {
        $query = DesignOption::query();

        
        if ($search = trim((string) $request->input('search', ''))) {
            $query->where(function ($q) use ($search) {
                $q->where('name->en', 'like', "%{$search}%")
                  ->orWhere('name->ar', 'like', "%{$search}%")
                  ->orWhere('type', 'like', "%{$search}%");
            });
        }

        // 2) Filter by type
        if ($type = trim((string) $request->input('type', ''))) {
            $query->where('type', $type);
        }

        // 3) Sort
        $allowedSort = ['id', 'type', 'created_at', 'updated_at'];
        $sortBy  = $request->input('sort_by', 'id');
        $sortDir = strtolower($request->input('sort_dir', 'desc'));

        if (!in_array($sortBy, $allowedSort, true)) {
            $sortBy = 'id';
        }
        if (!in_array($sortDir, ['asc', 'desc'], true)) {
            $sortDir = 'desc';
        }

        $query->orderBy($sortBy, $sortDir);

        // 4) Per page
        $perPage = (int) $request->input('per_page', 10);
        if ($perPage <= 0) $perPage = 10;
        if ($perPage > 50) $perPage = 50;

        // 5) Pagination + keep query string
        $options = $query->paginate($perPage)->withQueryString();

        // 6) Types list (لل dropdown)
        $types = DesignOption::query()
            ->select('type')
            ->whereNotNull('type')
            ->where('type', '!=', '')
            ->distinct()
            ->orderBy('type')
            ->pluck('type')
            ->toArray();

        return view('dashboard.design-options.index', compact('options', 'types'));
    }

    public function create()
    {
        return view('dashboard.design-options.create');
    }

    public function edit($id)
    {
        $option = DesignOption::findOrFail($id);
        return view('dashboard.design-options.edit', compact('option'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'type'    => 'required|string|max:255',
        ]);

        $option = new DesignOption();
        $option->name = [
            'en' => $data['name']['en'],
            'ar' => $data['name']['ar'],
        ];
        $option->type = $data['type'];
        $option->save();

        return redirect()->route('dashboard.design-options.index')
            ->with('success', 'Design option created successfully.');
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name.en' => 'required|string|max:255',
            'name.ar' => 'required|string|max:255',
            'type'    => 'required|string|max:255',
        ]);

        $option = DesignOption::findOrFail($id);
        $option->name = [
            'en' => $data['name']['en'],
            'ar' => $data['name']['ar'],
        ];
        $option->type = $data['type'];
        $option->save();

        return redirect()->route('dashboard.design-options.index')
            ->with('success', 'Design option updated successfully.');
    }

    public function destroy($id)
    {
        $option = DesignOption::findOrFail($id);
        $option->delete();

        return redirect()->route('dashboard.design-options.index')
            ->with('success', 'Design option deleted successfully.');
    }
}
