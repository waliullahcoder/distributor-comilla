<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use App\Models\Area;
use App\Models\Order;
use App\Models\CoaSetup;
use App\Models\OrderProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class OrderReturnController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $model = Order::with(['store', 'staff', 'area', 'products'])->orderBy('id', 'desc');
            if (Auth::user()->hasRole('Moderator')) {
                $model->where('created_by', Auth::user()->id);
            }
            if (Auth::user()->hasRole('Store Keeper')) {
                $model->whereIn('store_id', Auth::user()->stores);
            }
            // if (Auth::user()->hasRole('System Admin') || Auth::user()->hasRole('Software Admin') || Auth::user()->hasRole('Basic Admin')) {
            //     $model = Order::with(['store', 'staff', 'area', 'products'])->orderBy('id', 'desc');
            // } else {
            //     $model = Order::with(['store', 'staff', 'area', 'products'])->where('created_by', Auth::user()->id)->orderBy('id', 'desc');
            // }
            $model->whereIn('status', ['Delivered', 'On Route']);
            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return date('d-m-Y', strtotime($row->date));
                })
                ->addColumn('items', function ($row) {
                    $string = '';
                    foreach ($row->products as $key => $item) {
                        $string .= ($key > 0 ? ', ' : '') . @$item->product->name . ' - ' . $item->quantity . ' ' . @$item->product->attribute->name . ' - ' . $item->subtotal . 'Taka ';
                    }
                    return $string;
                })
                ->addColumn('actions', function ($row) {
                    return '<a class="btn btn-sm border-0 px-10px btn-info tt" href="' . Route('admin.order-return.edit', $row->id) . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Return Product">Return</a>';
                })
                ->rawColumns(['checkbox', 'actions'])
                ->make(true);
        }

        $title = "Order Return";
        return view('admin.order_return.index', compact('title'));
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
        //
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
    public function edit(Request $request, string $id)
    {
        $title = "Return Product";
        $data = Order::findOrFail($id);
        $link = Route('admin.order-return.update', $id);
        $areas = Area::where('status', 1)->orderBy('name', 'asc')->get();
        return view('admin.order_return.edit', compact('title', 'data', 'link', 'areas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::transaction(function () use ($request, $id) {
            $data = Order::findOrFail($id);
            $return_charge = 0;
            if (@$data->deliveryPackage->return_charge_type == 'Fixed Charge') {
                $return_charge = $data->deliveryPackage->return_charge;
            }
            if (@$data->deliveryPackage->return_charge_type == 'Delivery Charge') {
                $return_charge = $data->delivery_cost;
            }
            $data->update([
                'status' => 'Returned',
                'collected_at' => date('Y-m-d', strtotime($request->date)),
                'return_at' => date('Y-m-d', strtotime($request->date)),
                'delivered_at' => is_null($data->delivered_at) ? date('Y-m-d', strtotime($request->date)) : date('Y-m-d', $data->delivered_at),
                'total_return' => $request->total_return,
                'return_cost' => $return_charge,
                'receive' => null
            ]);

            foreach ($request->order_product_id as $order_product_id) {
                $item = OrderProduct::findOrFail($order_product_id);
                $item->update([
                    'return_quantity' => @$request->return_quantity[$order_product_id] ?? 0,
                    'damaged_quantity' => @$request->damaged_quantity[$order_product_id] ?? 0,
                    'return_amount' => @$request->return_amount[$order_product_id] ?? 0,
                ]);
            }

            $courier_head = CoaSetup::where('head_name', 'Courier Expense')->where('transaction', true)->where('head_type', 'E')->first();
            $cash_head = CoaSetup::where('head_type', 'A')->where('head_code', '1010203')->first();
            if ($data->return_cost > 0) {
                $headCode = collect([
                    '0' => 30106,
                    '1' => $courier_head->head_code,
                    '2' => $cash_head->head_code,
                ]);

                $debit_amount = collect([
                    '0' => $request->total_return + $data->shipping_cost,
                    '1' => $data->return_cost,
                    '2' => 0.00,
                ]);

                $credit_amount = collect([
                    '0' => 0.00,
                    '1' => 0.00,
                    '2' => $data->return_cost,
                ]);
            }

            $trim = str_replace("STOS", '', $data->invoice);
            $invoice = "STOR" . $trim;

            $countHead = count($headCode);
            $postData = [];
            for ($i = 0; $i < $countHead; $i++) {
                $coa = CoaSetup::where('company_id', Auth::user()->company_id ?? 1)->where('head_code', $headCode[$i])->first();
                $postData[] = [
                    'company_id' => Auth::user()->company_id ?? 1,
                    'voucher_no' => $invoice,
                    'voucher_type' => "Retail Return",
                    'voucher_date' => date('Y-m-d', strtotime($data->return_at)),
                    'coa_setup_id' => $coa->id,
                    'coa_head_code' => $headCode[$i],
                    'narration' => 'Retail Return Against RETURN NO - ' . $invoice,
                    'debit_amount' => $debit_amount[$i],
                    'credit_amount' => $credit_amount[$i],
                    'created_by' => Auth::user()->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ];
            }
            \App\Models\AccountTransactionAuto::insert($postData);
        });

        return redirect()->route('admin.order-return.index')->withSuccessMessage('Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
