<?php

namespace App\Http\Controllers\Admin;

use Exception;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductSku;
use Illuminate\Http\Request;
use App\Models\DeliveryAgent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\DeliveryAgentPackage;
use Illuminate\Support\Facades\Auth;

class OrderAssignController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Bulk Assign';
        $deliveryAgents = DeliveryAgent::where('status', 1)->orderBy('name', 'asc')->get();
        $query = Order::where('status', 'Forward');
        if (Auth::user()->stores) {
            $query->whereIn('store_id', Auth::user()->stores);
        }
        $orders = $query->get();
        $disable_back = true;
        return view('admin.order_assign.create', compact('title', 'deliveryAgents', 'orders', 'disable_back'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'delivery_agent_id' => 'required',
            'area_type' => 'required',
            'order_id' => 'required',
        ]);
        $package = DeliveryAgentPackage::where('delivery_agent_id', $request->delivery_agent_id)->where($request->area_type, true)->first();
        if (is_null($package)) {
            return redirect()->back()->withErrors('No suitable package found!');
        }

        try {
            DB::transaction(function () use ($request, $package) {
                $orders = Order::with('products')->whereIn('id', $request->order_id)->get();
                foreach ($orders as $item) {
                    foreach ($item->products as $product) {
                        $store_id = $item->store_id;
                        $product_id = $product->variant_id ?? $product->product_id;
                        $stock = $this->stock($product_id, $store_id);
                        $productInfo = Product::find($product->product_id);
                        $variant = ProductSku::find($product->variant_id);
                        if ($product->quantity > $stock) {
                            // throw new Exception('stock not available for ' . @$productInfo->name . ' - ' . (@$variant->sku ?? @$productInfo->code));
                        }
                    }

                    $totalWeight = $item->products->sum('quantity');
                    $additionalWeight = 0;
                    if ($totalWeight > $package->base_weight) {
                        $additionalWeight = $totalWeight - $package->base_weight;
                        if ($additionalWeight < 1 && $additionalWeight > 0) {
                            $additionalWeight = 1;
                        }
                    }
                    $deliveryCost = $package->base_rate + $additionalWeight * $package->additional_rate;
                    $item->update(['status' => 'On Route', 'delivery_agent_id' => $request->delivery_agent_id, 'delivery_package_id' => $package->id, 'delivery_cost' => $deliveryCost, 'area_type' => $request->area_type]);
                }
            });
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect()->back()->withSuccessMessage('Assigned Successfully!');
    }

    public static function stock($product_id, $store_id)
    {
        $liftings = DB::table('view_liftings')->where('product_type', 'Consumer')->where('product_id', $product_id)->where('store_id', $store_id)->sum('qty');
        $lifting_returns = DB::table('view_lifting_returns')->where('product_type', 'Consumer')->where('product_id', $product_id)->where('store_id', $store_id)->sum('qty');
        $client_sales = DB::table('view_sales')->where('product_type', 'Consumer')->where('product_id', $product_id)->where('store_id', $store_id)->sum('qty');
        $sales_returns = DB::table('view_sales_returns')->where('product_type', 'Consumer')->where('product_id', $product_id)->where('store_id', $store_id)->sum('qty');
        $online_sales = DB::table('view_online_sales')->where('is_stock', 1)->where('product_type', 'Consumer')->where('product_id', $product_id)->where('store_id', $store_id)->sum('qty');
        $transfers = DB::table('view_transfers')->where('product_type', 'Consumer')->where('product_id', $product_id)->where('host_id', $store_id)->sum('qty');
        $receives = DB::table('view_transfers')->where('product_type', 'Consumer')->where('product_id', $product_id)->where('destination_id', $store_id)->sum('qty');
        return $liftings + $sales_returns + $receives - $lifting_returns - $client_sales - $online_sales - $transfers;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
