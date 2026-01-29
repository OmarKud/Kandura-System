<?php

namespace App\service\user;

use App\Models\Address;
use Exception;
use Illuminate\Support\Facades\Auth;

class AddressService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {

    }
    public function create($request)
    {
        $id=Auth::id();
        $address = Address::create(array_merge($request,
       [ "user_id"=>$id]

));
        return $address;

    }
    public function update($request,$id)
    {
        $ID = Auth::id();
        $address = Address::where("user_id", $ID)->find($id);
        $address ->update($request);
        return $address;
    }
    public function delete($id)
    {
          $ID = Auth::id();
        $address = Address::where("user_id", $ID)->find($id);
        if ($address==null){
            return $address;
        }
        return $address->delete();

    }
     public function ListAddress($request)
    {
        $userId = Auth::id();

        $query = Address::where('user_id', $userId);

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('street', 'like', "%{$search}%")
                  ->orWhere('build', 'like', "%{$search}%")
                  ->orWhere('city', 'like', "%{$search}%");
            });
        }

        if ($city = $request->input('city')) {
            $query->where('city', $city);
        }

        $allowedSort = ['city', 'created_at', 'updated_at'];
        $sortBy = $request->input('sort_by', 'created_at');
        if (! in_array($sortBy, $allowedSort)) {
            $sortBy = 'created_at';
        }

        $sortDir = $request->input('sort_dir', 'desc');
        if (! in_array($sortDir, ['asc', 'desc'])) {
            $sortDir = 'desc';
        }

        $query->orderBy($sortBy, $sortDir);

        $perPage = (int) $request->input('per_page', 10);
        if ($perPage > 50) {
            $perPage = 50;
        }

        return $query->paginate($perPage);
    }
}
