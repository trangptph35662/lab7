<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data = Order::query()->with(['customer', 'details'])->latest('id')->paginate(1);

        return view('admin.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //

        return view('admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreOrderRequest $request)
    {
        //
       
        $images = [];
        try {

            DB::transaction(function () use ($request, &$images) {
                $customer = Customer::query()->create($request->customer);
                $supplier = Supplier::query()->create($request->supplier);
                // dd($supplier) ;
                $orderDetails = [];
                $totalAmount = 0;
                foreach ($request->products as $key => $product) {
                    $product['supplier_id'] = $supplier->id;

                    if ($request->hasFile("products.$key.image")) {
                        $images[] = $product['image'] = Storage::put('products', $request->file("products.$key.image"));
                    }

                    $tmp = Product::query()->create($product);
                    // dd($tmp) ;
                    $orderDetails[$tmp->id] = [
                        'qty' => $request->order_details[$key]['qty'],
                        'price' => $tmp->price
                    ];
                    // dd($orderDetails) ;
                    $totalAmount += $request->order_details[$key]['qty'] * $tmp->price;
                }
                // hết chạy
                $order = Order::query()->create([
                    'customer_id' => $customer->id,
                    'total_amount' => $totalAmount,
                ]);

                $order->details()->attach($orderDetails);
            }, 3);
            return redirect()->route('orders.index')->with('success', 'Thao tác thành công');
        } catch (\Exception $ex) {
            //throw $th;
            foreach ($images as $image) {
                if (Storage::exists($image)) {
                    Storage::delete($image);
                }
            }
            return back()->with('error', $ex->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
       $order->load(['details','customer']) ;
       return view('admin.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
        $order->load(['details', 'customer']);
        return view('admin.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrderRequest $request, Order $order)
    {
        //
        try {
            DB::transaction(function () use ($request, $order) {
                $order->details()->sync($request->order_details);

                $orderDetails = array_map(function ($item) {
                    return $item['price'] * $item['qty'];
                }, $request->order_details);

                $totalAmount = array_sum($orderDetails);

                $order->update([
                    'total_amount' => $totalAmount,
                ]);
            }, 3);
            return back()->with('success', 'Order updated successfully');
        } catch (\Exception $th) {
            //throw $th;
            return back()->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
        try {

            DB::transaction(function () use ($order) {
                $order->details()->sync([]);
                $order->delete();
            }, 3);
            return back()->with('success', 'Xoá thành công');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
