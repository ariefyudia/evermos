<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;

use Validator;
use DB;

class TransactionController extends Controller
{
    public function cart(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'productId' => 'required',
            'qty' => 'required|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $checkProduct = Product::whereRaw('id = :productId', ['productId' => $request->productId])->first();
        if($checkProduct->stock == 0) {
            return response()->json([
                'status' => false,
                'message' => 'Product out of stock'
            ], 404);
        }

        if($checkProduct->stock < $request->qty) {
            return response()->json([
                'status' => false,
                'message' => 'Max stock product '. $checkProduct->stock
            ], 400);
        }

        DB::beginTransaction();

        try {
            $data = [
                'product_id' => $checkProduct->id,
                'status_id' => 1,
                'user_id' => auth()->user()->id,
                'qty' => $request->qty,
                'total_price' => $request->qty * $checkProduct->price,
                'expired_at' => date('Y-m-d H:i:s', strtotime('1 hour'))
            ];

            Transaction::updateOrCreate([
                'product_id' => $checkProduct->id,
                'user_id' => auth()->user()->id
            ], $data);
            
            DB::commit();

            return response()->json($data);
        } catch (\Exception $th) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $th
            ], 500);

        }
    }

    public function checkout(Request $request, $id)
    {
        $getData = Transaction::whereRaw('status_id = 1')
        ->whereRaw('id = :id', ['id' => $id])
        ->whereRaw('user_id = :userid', ['userid' => auth()->user()->id])
        ->first();
        if(!$getData) {
            return response()->json([
                'status' => false,
                'message' => "Not found"
            ], 404);
        }

        if(date('Y-m-d H:i:s') > $getData->expired_at) {
            DB::beginTransaction();
            try {
                $data = [
                    'status_id' => 4,
                    'expired_at' => null
                ];
        
                Transaction::updateOrCreate([
                    'id' => $id,
                ], $data);
    
                return response()->json([
                    'status' => false,
                    'message' => "Oops! transaction expired"
                ], 400);
            } catch (\Exception $th) {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'message' => $th
                ], 500);
    
            }

            
        }

        DB::beginTransaction();
        try {
            $data = [
                'status_id' => 2,
                'expired_at' => date('Y-m-d H:i:s', strtotime('1 hour'))
            ];
    
            Transaction::updateOrCreate([
                'id' => $id,
            ], $data);
    
            return response()->json($data);
        } catch (\Exception $th) {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message' => $th
            ], 500);

        }

        
    }

    public function pay($id)
    {
        $getData = Transaction::whereRaw('status_id IN (1,2)')
        ->whereRaw('id = :id', ['id' => $id])
        ->whereRaw('user_id = :userid', ['userid' => auth()->user()->id])
        ->first();
        if(!$getData) {
            return response()->json([
                'status' => false,
                'message' => "Not found"
            ], 404);
        }

        if(date('Y-m-d H:i:s') > $getData->expired_at) {
            DB::beginTransaction();
            try {
                $data = [
                    'status_id' => 4,
                    'expired_at' => null
                ];
        
                Transaction::updateOrCreate([
                    'id' => $id,
                ], $data);
    
                return response()->json([
                    'status' => false,
                    'message' => "Oops! transaction expired"
                ], 400);
            } catch (\Exception $th) {
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'message' => $th
                ], 500);
    
            }
        }

        $checkProduct = Product::whereRaw('id = :productId', ['productId' => $getData->product_id])->first();
        if($checkProduct->stock == 0) {
            return response()->json([
                'status' => false,
                'message' => 'Product out of stock'
            ], 404);
        }

        if($checkProduct->stock < $getData->qty) {
            return response()->json([
                'status' => false,
                'message' => 'Product only left '.$checkProduct->stock
            ], 400);
        }

        DB::beginTransaction();
        try {
            $data = [
                'status_id' => 3,
                'expired_at' => null
            ];
    
            $trans = Transaction::updateOrCreate([
                'id' => $id,
            ], $data);
            if($trans) {
                $product = Product::select('stock')->where('id', $trans->product_id)->first();
                Product::where('id', $trans->product_id)
                ->update([
                    'stock' => $product->stock - $trans->qty
                ]);
            }
            
            DB::commit();
            return response()->json($data);
        } catch (\Exception $th) {
            return response()->json([
                'status' => false,
                'message' => $th
            ], 500);
        }
        
    }

    public function cancel($id)
    {
        $getData = Transaction::whereRaw('status_id IN (1,2)')
        ->whereRaw('id = :id', ['id' => $id])
        ->whereRaw('user_id = :userid', ['userid' => auth()->user()->id])
        ->first();
        if(!$getData) {
            return response()->json([
                'status' => false,
                'message' => "Not found"
            ], 404);
        }

        DB::beginTransaction();
        try {
            $data = [
                'status_id' => 4,
                'expired_at' => null
            ];
    
            Transaction::updateOrCreate([
                'id' => $id,
            ], $data);
            
            return response()->json($data);
        } catch (\Exception $th) {
            return response()->json([
                'status' => false,
                'message' => $th
            ], 500);
        }

        
    }
}
