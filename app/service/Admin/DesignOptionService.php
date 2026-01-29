<?php

namespace App\service\Admin;

use App\Models\DesignOption;

class DesignOptionService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function index()
    {
        $designOption = DesignOption::get();
        return $designOption;

    }
    public function create($request)
    {
        $designOption = DesignOption::create($request);
        return $designOption;


    }
    public function update($request,$id)
    {
        $designOption = DesignOption::find($id);
        if($designOption==null){
            return $designOption;
        }
        $designOption->update($request);
        return $designOption;


    }
    public function delete($id){
          $designOption = DesignOption::find($id);
        if($designOption==null){
            return $designOption;
        }

        return $designOption->delete();

    }

}
