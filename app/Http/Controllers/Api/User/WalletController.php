<?php

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateWalletRequest;
use App\Http\Resources\WalletResource;
use App\Models\Wallet;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletController extends Controller
{
    public function create($user_id){
       
        $wallet=Wallet::create([
            "user_id"=>$user_id,
        ]);

    }
    public function index(){
        $wallet=Wallet::where("user_id",Auth::id())->first();
        if($wallet==null){
            throw new Exception("create wallet befor");
        }
        return  new WalletResource($wallet);

    }
}
