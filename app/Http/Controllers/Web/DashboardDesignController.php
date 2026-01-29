<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Design;
use App\Models\DesignOption;
use App\Models\Measurement;
use App\service\Web\DesignService;
use Illuminate\Http\Request;

class DashboardDesignController extends Controller
{
    public function __construct(protected DesignService $designService)
    {
    }

    public function index(Request $request)
{
    $designs = app(\App\service\Web\DesignService::class)->index($request);

    $measurements = \App\Models\Measurement::orderBy('id')->get();
    $designOptions = \App\Models\DesignOption::orderBy('id')->get();

    return view('dashboard.designs.index', compact('designs','measurements','designOptions'));
}

public function show($id)
{
    $design = \App\Models\Design::with([
        'user',
        'images',
        'measurements', // ✅ many-to-many
        'optionSelections.designOption',
    ])->findOrFail($id);

    return view('dashboard.designs.show', compact('design'));
}
}
