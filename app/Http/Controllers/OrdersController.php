<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index( Request $request){
        $orders = Order::query()
        ->with(['user' , 'items.product'])
        ->whereIn('status', ['paid', 'processing', 'shipped'])
        ->when($request->search, function ($query, $search) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', "%{$search}%")
                ->onWhereHas('user' , function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                })
                ->onWhereHas('shipping_address' , 'like', "%{$search}%")
                ->onWhereHas('resi_code' , 'like', "%{$search}%");
            });
        })
        ->when($request->status, function ($query, $status) {
            if(in_array($status, ['paid', 'processing', 'shipped'])){
                $query->where('status', $status);
            }
        })
        ->latest()
        ->paginate(5)
        ->withQueryString();
        
    }
}
