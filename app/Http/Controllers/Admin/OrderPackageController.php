<?php

namespace App\Http\Controllers\Admin;

use App\HelperClass;
use App\Models\OrderPackage;
use App\Models\OrderPackageList;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;

class OrderPackageController extends Controller
{
    public $path;
    public $title;
    public $create_title;
    public $edit_title;
    public $model;
    public function __construct()
    {
        $this->path = 'order-package';
        $this->title = 'Order Packages';
        $this->create_title = 'Add Package';
        $this->edit_title = 'Update Package';
        $this->model = OrderPackage::class;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return HelperClass::resourceDataView($this->model::orderBy('id', 'desc'), 'image', NULL, $this->path, $this->title);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = $this->create_title;
        $products = Product::where('status', 1)->orderBy('name', 'asc')->get();
        return view("admin.{$this->path}.create", compact('title', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'total_amount' => 'required',
            'net_amount' => 'required',
            'product_id' => 'required',
        ]);

        DB::transaction(function () use ($request) {
            $slug = Str::slug($request->name);
            $same_slug_count = $this->model::where('slug', 'LIKE', $slug . '%')->count();
            $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
            $slug .= $slug_suffix;

            $data = $this->model::create([
                'name' => $request->name,
                'slug' => $slug,
                'image' => isset($request->image) ? HelperClass::saveImage($request->image, 500, 'media/order-package/') : NULL,
                'shipping_charge' => $request->shipping_charge ?? 0,
                'discount' => $request->discount ?? 0,
                'amount' => $request->total_amount ?? 0,
                'net_amount' => $request->net_amount ?? 0,
                'description' => $request->description,
            ]);

            foreach ($request->product_id as $product_id) {
                OrderPackageList::create([
                    'order_package_id' => $data->id,
                    'product_id' => $product_id,
                    'rate' => @$request->rate[$product_id] ?? 0,
                    'qty' => @$request->qty[$product_id] ?? 0,
                    'amount' => @$request->amount[$product_id] ?? 0,
                ]);
            }
        });

        return redirect()->route("admin.{$this->path}.index")->withSuccessMessage('Created Successfully!');
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
        if (request()->ajax()) {
            $data = $this->model::findOrFail($id);
            $data->update(['status' => !$data->status]);
            return response()->json(['status' => 'success']);
        }

        $title = $this->edit_title;
        $data = $this->model::findOrFail($id);
        $products = Product::where('status', 1)->orderBy('name', 'asc')->get();
        return view("admin.{$this->path}.edit", compact('title', 'data', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'total_amount' => 'required',
            'net_amount' => 'required',
            'product_id' => 'required',
        ]);

        DB::transaction(function () use ($request, $id) {
            $data = $this->model::findOrFail($id);

            $slug = Str::slug($request->name);
            $same_slug_count = $this->model::whereNot('id', $id)->where('slug', 'LIKE', $slug . '%')->count();
            $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
            $slug .= $slug_suffix;

            $data->update([
                'name' => $request->name,
                'slug' => $slug,
                'image' => isset($request->image) ? HelperClass::saveImage($request->image, 500, 'media/order-package/', $data->image) : $data->image,
                'shipping_charge' => $request->shipping_charge ?? 0,
                'discount' => $request->discount ?? 0,
                'amount' => $request->total_amount ?? 0,
                'net_amount' => $request->net_amount ?? 0,
                'description' => $request->description,
            ]);

            OrderPackageList::where('order_package_id', $id)->delete();
            foreach ($request->product_id as $product_id) {
                OrderPackageList::create([
                    'order_package_id' => $data->id,
                    'product_id' => $product_id,
                    'rate' => @$request->rate[$product_id] ?? 0,
                    'qty' => @$request->qty[$product_id] ?? 0,
                    'amount' => @$request->amount[$product_id] ?? 0,
                ]);
            }
        });
        return redirect()->route("admin.{$this->path}.index")->withSuccessMessage('Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return HelperClass::resourceDataDelete($this->model, $id, 'image');
    }
}
