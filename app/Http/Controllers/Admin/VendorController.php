<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vendor;
use App\Models\CoaSetup;
use Illuminate\Http\Request;
use App\Models\LiftingProduct;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Services\ActionButtons\ActionButtons;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $model = Vendor::with(['company']);
            $type = request('type');
            if (!empty($type) && $type == 'trash') {
                $model->onlyTrashed();
            }
            return DataTables::eloquent($model)
                ->addColumn('checkbox', function ($row) {
                    if (count($row->liftings) == 0 && count($row->payments) == 0) {
                        return '<div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input ' . (!empty(request('type')) && request('type') == "trash" ? 'trash_multi_checkbox' : 'multi_checkbox') . '" id="' . $row->id . '" name="multi_checkbox[]" value="' . $row->id . '"><label for="' . $row->id . '" class="custom-control-label"></label></div>';
                    }
                })
                ->addColumn('status', function ($row) {
                    $status = '<div class="form-check form-switch">
                    <input class="form-check-input change-status c-pointer" data-url="' . Route('admin.vendor.edit', $row->id) . '" type="checkbox" name="status" ' . ($row->status == 1 ? 'checked' : '') . '>
                    </div>';
                    return $status;
                })
                ->addColumn('actions', function ($row) {
                    $type = request('type');
                    $data = [
                        'id' => $row->id,
                        'edit' => !empty($type) && $type == 'trash' ? false : true,
                    ];
                    $delete = 'no';
                    if (count($row->liftings) == 0 && count($row->payments) == 0) {
                        $delete = 'yes';
                    }
                    return ActionButtons::actions($data, null, $delete);
                })
                ->rawColumns(['checkbox', 'status', 'actions'])
                ->make(true);
        }

        $title = "Vendor Setup";
        return view('admin.vendor.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add New Vendor';
        return view('admin.vendor.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'name' => 'required',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $parent = CoaSetup::findOrFail(30);
                $prefix = $parent->head_code;
                $maxCode = CoaSetup::where('parent_id', $parent->id)->max('head_code');
                if ($maxCode) {
                    $next = str_pad((int) substr($maxCode, strlen($prefix)) + 1, 2, '0', STR_PAD_LEFT);
                    $headCode = $prefix . $next;
                } else {
                    $headCode = $prefix . '01';
                }

                $account = CoaSetup::create([
                    'company_id'  => Auth::user()->company_id ?? 1,
                    'parent_id'   => $parent->id,
                    'head_code'   => $headCode,
                    'head_name'   => $request->name,
                    'transaction' => true,
                    'general'     => false,
                    'head_type'   => $parent->head_type,
                    'status'      => true,
                    'updateable'  => false,
                    'created_by'  => Auth::user()->id,
                ]);

                Vendor::create([
                    'company_id'    => Auth::user()->company_id ?? 1,
                    'code'          => $request->code,
                    'name'          => $request->name,
                    'contact_person' => $request->contact_person,
                    'email'         => $request->email,
                    'phone'         => $request->phone,
                    'address'       => $request->address,
                    'coa_setup_id' => $account->id,
                    'created_by'    => Auth::user()->id,
                ]);
            });
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect()->route('admin.vendor.index')->withSuccessMessage('Created Successfully!');
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
        if (request()->ajax() && request('status')) {
            $data = Vendor::findOrFail($id);
            $data->update(['status' => !$data->status]);
            return response()->json(['status' => 'success']);
        }

        $title = 'Update Vendor';
        $data = Vendor::findOrFail($id);
        $link = Route('admin.vendor.update', $id);
        return view('admin.vendor.edit', compact('title', 'data', 'link'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'code' => 'required',
        ]);

        try {
            DB::transaction(function () use ($id, $request) {
                $data = Vendor::findOrFail($id);
        
                $account = CoaSetup::find($data->coa_setup_id);
                if ($account) {
                    $account->update([
                        'head_name'  => $request->name,
                        'updateable' => false,
                        'updated_by' => Auth::id()
                    ]);
                } else {
                    $parent = CoaSetup::findOrFail(30);
                    $prefix = $parent->head_code;
                    $maxCode = CoaSetup::where('parent_id', $parent->id)->max('head_code');
                    if ($maxCode) {
                        $next = str_pad((int) substr($maxCode, strlen($prefix)) + 1, 2, '0', STR_PAD_LEFT);
                        $headCode = $prefix . $next;
                    } else {
                        $headCode = $prefix . '01';
                    }
                    
                    $account = CoaSetup::create([
                        'company_id'  => Auth::user()->company_id ?? 1,
                        'parent_id'   => $parent->id,
                        'head_code'   => $headCode,
                        'head_name'   => $request->name,
                        'transaction' => true,
                        'general'     => false,
                        'head_type'   => $parent->head_type,
                        'status'      => true,
                        'updateable'  => false,
                        'created_by'  => Auth::user()->id,
                    ]);
                }


                $data->update([
                    'code' => $request->code,
                    'name' => $request->name,
                    'contact_person' => $request->contact_person,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'address' => $request->address,
                    'coa_setup_id' => $account->id,
                    'updated_by' => Auth::user()->id,
                ]);
            });
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
        return redirect()->route('admin.vendor.index')->withSuccessMessage('Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Recovery Deleted Data
        if (request()->has('recovery') && request('recovery') == 'true') {
            $data = Vendor::onlyTrashed()->findOrFail($id);
            $coa = CoaSetup::onlyTrashed()->find($data->coa_setup_id);
            if ($coa) {
                $coa->restore();
            }
            $data->restore();
            return response()->json(['status' => 'success']);
        }

        // Delete Multiple Items Permanent
        if (request()->has('id') && request()->has('parmanent') && request('parmanent') == 'true') {
            foreach (request('id') as $id) {
                $data = Vendor::onlyTrashed()->findOrFail($id);
                $coa = CoaSetup::withTrashed()->find($data->coa_setup_id);
                if ($coa) {
                    $coa->forceDelete();
                }
                $data->forceDelete();
            }
            return response()->json(['status' => 'success']);
        }

        // Delete Single Item Permanent
        if (request()->has('parmanent') && request('parmanent') == 'true') {
            $data = Vendor::onlyTrashed()->findOrFail($id);                
            $coa = CoaSetup::withTrashed()->find($data->coa_setup_id);
            if ($coa) {
                $coa->forceDelete();
            }
            $data->forceDelete();
            return response()->json(['status' => 'success']);
        }

        // Delete Multiple Items
        if (request()->has('id')) {
            foreach (request('id') as $id) {
                $data = Vendor::findOrFail($id);
                $data->update(['deleted_by' => Auth::user()->id]);
                
                $coa = CoaSetup::find($data->coa_setup_id);
                if ($coa) {
                    $coa->update(['deleted_by' => Auth::id()]);
                    $coa->delete();
                }
                $data->delete();
            }
            return response()->json(['status' => 'success']);
        }

        // Delete Single Item
        $data = Vendor::findOrFail($id);
        $data->update(['deleted_by' => Auth::user()->id]);
        
        $coa = CoaSetup::find($data->coa_setup_id);
        if ($coa) {
            $coa->update(['deleted_by' => Auth::id()]);
            $coa->delete();
        }
        $data->delete();

        return response()->json(['status' => 'success']);
    }
}
