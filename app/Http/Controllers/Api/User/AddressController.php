<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAddressRequest;
use App\Http\Requests\UpdateAddressRequest;
use App\Http\Resources\AddressResource;
use App\service\user\AddressService;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    protected $addressservice;
    public function __construct(AddressService $addressservice)
    {
        $this->addressservice = $addressservice;
    }
    public function create(StoreAddressRequest $request)
    {
        $address = $this->addressservice->create($request->validated());
        return $this->complet(new AddressResource($address));

    }
    public function update(UpdateAddressRequest $request, $id)
    {
        $address = $this->addressservice->update($request->validated(), $id);
        return $this->complet(new AddressResource($address));

    }
    public function delete($id)
    {
        $address = $this->addressservice->delete($id);
        if ($address == null) {
            return response()->json("you cant delete this address", 400);
        }
        return response()->json("Address deleted succes");
    }
     public function index(Request $request){
        $address=$this->addressservice->ListAddress($request);
        return $this->complet( AddressResource::collection($address));


}
}