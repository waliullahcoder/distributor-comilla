<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\DeliveryAgentPackage;
use App\Http\Controllers\Controller;
use App\Models\DeliveryAgent;
use Yajra\DataTables\Facades\DataTables;
use App\Services\ActionButtons\ActionButtons;
use Illuminate\Support\Facades\Auth;

class DeliveryAgentPackageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $model = DeliveryAgentPackage::with(['agent'])->orderBy('id', 'desc');
            $type = request('type');
            if (!empty($type) && $type == 'trash') {
                $model->onlyTrashed();
            }
            return DataTables::eloquent($model)
                ->addColumn('checkbox', function ($row) {
                    $checkbox = '<div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input ' . (!empty(request('type')) && request('type') == "trash" ? 'trash_multi_checkbox' : 'multi_checkbox') . '" id="' . $row->id . '" name="multi_checkbox[]" value="' . $row->id . '"><label for="' . $row->id . '" class="custom-control-label"></label></div>';
                    return $checkbox;
                })
                ->addColumn('area_types', function ($row) {
                    $string = '';
                    if ($row->inside_dhaka) {
                        $string .= 'Inside Dhaka | ';
                    }
                    if ($row->subarea_dhaka) {
                        $string .= 'Subarea Dhaka | ';
                    }
                    if ($row->inside_chittagong) {
                        $string .= 'Inside Chittagong | ';
                    }
                    if ($row->subarea_chittagong) {
                        $string .= 'Subarea Chittagong | ';
                    }
                    if ($row->district_level) {
                        $string .= 'District Level';
                    }
                    return $string;
                })
                ->addColumn('status', function ($row) {
                    $status = '<div class="form-check form-switch">
                    <input class="form-check-input change-status c-pointer" data-url="' . Route('admin.agent-package.edit', $row->id) . '" type="checkbox" name="status" ' . ($row->status == 1 ? 'checked' : '') . '>
                    </div>';
                    return $status;
                })
                ->addColumn('actions', function ($row) {
                    $type = request('type');
                    $data = [
                        'id' => $row->id,
                        'edit' => !empty($type) && $type == 'trash' ? false : true,
                    ];
                    return ActionButtons::actions($data);
                })
                ->rawColumns(['checkbox', 'status', 'actions'])
                ->make(true);
        }

        $title = "Delivery Packages";
        return view('admin.agent-package.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add New Package';
        $agents = DeliveryAgent::where('status', true)->orderBy('name', 'asc')->get();
        return view('admin.agent-package.create', compact('title', 'agents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'delivery_agent_id' => 'required',
            'base_rate' => 'required',
            'base_weight' => 'required',
            'additional_rate' => 'required'
        ]);

        DeliveryAgentPackage::create([
            'delivery_agent_id' => $request->delivery_agent_id,
            'name' => $request->name,
            'base_rate' => $request->base_rate,
            'base_weight' => $request->base_weight,
            'additional_rate' => $request->additional_rate,
            'return_charge_type' => $request->return_charge_type,
            'return_charge' => $request->return_charge ?? 0,
            'inside_dhaka' => $request->inside_dhaka ?? false,
            'subarea_dhaka' => $request->subarea_dhaka ?? false,
            'inside_chittagong' => $request->inside_chittagong ?? false,
            'subarea_chittagong' => $request->subarea_chittagong ?? false,
            'district_level' => $request->district_level ?? false,
            'created_by' => Auth::user()->id,
        ]);

        return redirect()->route('admin.agent-package.index')->withSuccessMessage('Created Successfully!');
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
            $data = DeliveryAgentPackage::findOrFail($id);
            $data->update(['status' => !$data->status]);
            return response()->json(['status' => 'success']);
        }

        $title = 'Update Region';
        $data = DeliveryAgentPackage::findOrFail($id);
        $link = Route('admin.agent-package.update', $id);
        $agents = DeliveryAgent::where('status', true)->orderBy('name', 'asc')->get();
        return view('admin.agent-package.edit', compact('title', 'data', 'link', 'agents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'delivery_agent_id' => 'required',
            'base_rate' => 'required',
            'base_weight' => 'required',
            'additional_rate' => 'required'
        ]);

        $data = DeliveryAgentPackage::findOrFail($id);
        $data->update([
            'delivery_agent_id' => $request->delivery_agent_id,
            'name' => $request->name,
            'base_rate' => $request->base_rate,
            'base_weight' => $request->base_weight,
            'additional_rate' => $request->additional_rate,
            'return_charge_type' => $request->return_charge_type,
            'return_charge' => $request->return_charge ?? 0,
            'inside_dhaka' => $request->inside_dhaka ?? false,
            'subarea_dhaka' => $request->subarea_dhaka ?? false,
            'inside_chittagong' => $request->inside_chittagong ?? false,
            'subarea_chittagong' => $request->subarea_chittagong ?? false,
            'district_level' => $request->district_level ?? false,
            'updated_by' => Auth::user()->id,
        ]);

        return redirect()->route('admin.agent-package.index')->withSuccessMessage('Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Recovery Deleted Data
        if (request()->has('recovery') && request('recovery') == 'true') {
            $data = DeliveryAgentPackage::onlyTrashed()->findOrFail($id);
            $data->restore();
            return response()->json(['status' => 'success']);
        }

        // Delete Multiple Items Permanent
        if (request()->has('id') && request()->has('parmanent') && request('parmanent') == 'true') {
            foreach (request('id') as $id) {
                $data = DeliveryAgentPackage::onlyTrashed()->findOrFail($id);
                $data->forceDelete();
            }
            return response()->json(['status' => 'success']);
        }

        // Delete Single Item Permanent
        if (request()->has('parmanent') && request('parmanent') == 'true') {
            $data = DeliveryAgentPackage::onlyTrashed()->findOrFail($id);
            $data->forceDelete();
            return response()->json(['status' => 'success']);
        }

        // Delete Multiple Items
        if (request()->has('id')) {
            foreach (request('id') as $id) {
                $data = DeliveryAgentPackage::findOrFail($id);
                $data->update(['deleted_by' => Auth::user()->id]);
                $data->delete();
            }
            return response()->json(['status' => 'success']);
        }

        // Delete Single Item
        $data = DeliveryAgentPackage::findOrFail($id);
        $data->update(['deleted_by' => Auth::user()->id]);
        $data->delete();

        return response()->json(['status' => 'success']);
    }
}
