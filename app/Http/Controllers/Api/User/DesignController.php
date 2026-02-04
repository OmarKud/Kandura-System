<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateDesignRequest;
use App\Http\Requests\EditActivateDesignRequest;
use App\Http\Requests\UpdateDesignRequest;
use App\Http\Resources\DesignResource;
use App\Service\User\DesignService;
use Illuminate\Http\Request;

class DesignController extends Controller
{
    protected $designService;

    public function __construct(DesignService $designService)
    {
        $this->designService = $designService;
    }

    public function index(Request $request)
    {
        $designs = $this->designService->index($request);

        return $this->complet(DesignResource::collection($designs));
    }

    public function show($id)
    {
        $design = $this->designService->show($id);

        if ($design == null) {
            return response()->json("the design is not found");
        }

        return $this->complet(new DesignResource($design));
    }

    public function create(CreateDesignRequest $request)
    {
        $data = $request->validated();
        $design = $this->designService->create($data);

        return $this->complet(new DesignResource($design), "created succes");
    }

    public function update(UpdateDesignRequest $request, $id)
    {
        $data['images'] = $request->file('images');
        $design = $this->designService->update($request->validated(), $id);

        if ($design == null) {
            return response()->json("the design is not found");
        }

        return $this->complet(new DesignResource($design), "updated succes");
    }
    public function StatusDesign($id){
      $design = $this->designService->edit( $id);

        if ($design == null) {
            return response()->json("the design is not found");
        }

        return $this->complet(new DesignResource($design), "updated succes");   
    }

    public function delete($id)
    {
        $result = $this->designService->delete($id);

        if ($result == null) {
            return response()->json("the design is not found");
        }

        return $this->complet("", "deleted succes");
    }
}
