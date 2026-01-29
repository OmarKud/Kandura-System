<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDesignOptionRequest;
use App\Http\Requests\UpdateDesignOptionRequest;
use App\Http\Resources\DesignOptionResource;
use App\service\Admin\DesignOptionService;
use Illuminate\Http\Request;

class DesignOptionController extends Controller
{
    protected $designOptionService;
    public function __construct(DesignOptionService $designOptionService)
    {
        $this->designOptionService = $designOptionService;

    }
    public function index()
    {
        $designOption = $this->designOptionService->index();
        return $this->complet( DesignOptionResource::collection($designOption));

    }
    public function create(CreateDesignOptionRequest $request)
    {
        $designOption = $this->designOptionService->create($request->validated());
        return $this->complet( new DesignOptionResource($designOption));

    }

    public function update(UpdateDesignOptionRequest $request, $id)
    {
        $designOption = $this->designOptionService->update($request->validated(), $id);
        if ($designOption == null) {
            return response()->json("the designOption is not found");
        }
 return $this->complet( new DesignOptionResource($designOption),"updated sussec");

    }
    public function delete($id)
    {
        $designOption = $this->designOptionService->delete($id);
        if ($designOption == null) {
            return response()->json("the designOption is not found");
        }


        return $this->complet("", "deleted succes");

    }
}
