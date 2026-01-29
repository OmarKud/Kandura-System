<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use App\service\User\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $orderservice;
    public function __construct(OrderService $orderservice)
    {
        $this->orderservice = $orderservice;
    }
    public function index()
    {
        $order = Order::where("user_id", Auth::id())->with(
            "user",
            "address",
            'designOrders.design',
            'designOrders.options',
        )->get();
        if($order==null){
            return response()->json("you dont have any order");
        }
        return $this->complet( OrderResource::collection($order));

    }

    /**
     * Show the form for creating a new resource.
     */
public function create(CreateOrderRequest $request)
{
    $order = $this->orderservice->create($request->validated());
    return $this->complet(new OrderResource($order));
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request,Order $order)
    {
       $order=$this->orderservice->CancelOrder($request->validated(),$order);
           return $this->complet(new OrderResource($order),"order cancelled Succes");

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
