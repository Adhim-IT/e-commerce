<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use Exception;
use Illuminate\Http\Request;
use Midtrans\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Midtrans\Snap;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserCheckoutController extends Controller
{
    public function __construct()
    {
        try {
            $serverKey = config('midtrans.server_key');
            if (empty($serverKey)) {
                throw new Exception('Please difine your midtrans servew key');
            }
            Config::$serverKey = $serverKey;
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.enable_3ds');

            Log::info('Midtrans Configuration Load', [
                'isProduction' => Config::$isProduction,
                'isSanitized' => Config::$isSanitized,
                'is3ds' => Config::$is3ds,
            ]);
        } catch (Exception $e) {
            Log::error('Midtrans Config error :' . $e->getMessage());
            throw $e;
        }
    }

    public function process(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => ['required', 'string'],
                'phone' => ['required', 'string'],
                'shipping_address' => ['required', 'string'],
                'notes' => ['required', 'string'],
                'cart' => ['required', 'array'],
            ]);

            DB::beginTransaction();
            $order = Order::create([
                'user_id' => auth()->id(),
                'shipping_address' => $request->shipping_address,
                'total_amout' => 0,
                'status' => 'pending',
                'notes' => $request->notes,
            ]);


            $totalAmout = 0;
            $items = [];

            foreach ($request->cart as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                ]);

                $totalAmout += $item['price'] * $item['quantity'];

                $items[] = [
                    'id' => (string) $item['id'],
                    'price' => (int) $item['price'],
                    'quantity' => (int) $item['quantity'],
                    'name' => $item['name'],

                ];
            }

            $shippingCost = 20000;
            $totalAmout += $shippingCost;

            $order->update(['total_amout' => $totalAmout]);

            $params = [
                'transaction_details' => [
                    'order_id' => (string) $order->id,
                    'gross_amount' => (int) $totalAmout,
                ],
                'item_details' => array_merge($items, [
                    [
                        'id' => 'shipping',
                        'price' => $shippingCost,
                        'quantity' => 1,
                        'name' => 'Shipping Cost',
                    ]
                    ]),
                    'customer_details' => [
                        'first_name' => $request->name,
                        'email' => auth()->user()->email,
                        'phone' => $request->phone,
                        'billing_address' => [
                            'address' => $request->shipping_address
                        ],
                        'shipping_address' => [
                            'address' => $request->shipping_address
                        ]
                    ]
            ];

            $snapToken = Snap::getSnapToken($params);
            if(empty($snapToken)){
                throw new Exception('Snap Token is empty');
            }

            $order->update(['snap_token' => $snapToken]);

            DB::commit();

            return response()->json( data:[
                'status' => 'success',
                'snap_token' => $snapToken,
                'order_id' => $order->id
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error( 'checkout porses error'.$e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json( data:[
                'status' => 'failed',
                'message' => 'Error payment proses'.$e->getMessage()
            ], status: 442);
        }
    }


//     public function updateStatus(Request $request): JsonResponse|mixed
//     {
//         try{}
//     }
}
