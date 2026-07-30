<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\DeliveryAgent;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Services\ActionButtons\ActionButtons;

class DeliveryAgentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $model = DeliveryAgent::with(['packages'])->orderBy('id', 'desc');
            $type = request('type');
            if (!empty($type) && $type == 'trash') {
                $model->onlyTrashed();
            }
            return DataTables::eloquent($model)
                ->addColumn('checkbox', function ($row) {
                    if (count($row->packages) == 0) {
                        $checkbox = '<div class="custom-control custom-checkbox">
                        <input type="checkbox" class="custom-control-input ' . (!empty(request('type')) && request('type') == "trash" ? 'trash_multi_checkbox' : 'multi_checkbox') . '" id="' . $row->id . '" name="multi_checkbox[]" value="' . $row->id . '"><label for="' . $row->id . '" class="custom-control-label"></label></div>';
                        return $checkbox;
                    }
                })
                ->addColumn('status', function ($row) {
                    $status = '<div class="form-check form-switch">
                    <input class="form-check-input change-status c-pointer" data-url="' . Route('admin.delivery-agent.edit', $row->id) . '" type="checkbox" name="status" ' . ($row->status == 1 ? 'checked' : '') . '>
                    </div>';
                    return $status;
                })
                ->addColumn('actions', function ($row) {
                    $type = request('type');
                    $data = [
                        'id' => $row->id,
                        'edit' => !empty($type) && $type == 'trash' ? false : true,
                    ];
                    $delete = 'yes';
                    $edit = 'yes';
                    if (count($row->packages) > 0) {
                        $delete = 'no';
                    }
                    return ActionButtons::actions($data, null, $delete, $edit);
                })
                ->rawColumns(['checkbox', 'status', 'actions'])
                ->make(true);
        }

        $title = "Delivery Agents";
        return view('admin.delivery-agent.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add New Agent';
        return view('admin.delivery-agent.create', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        DeliveryAgent::create([
            'code' => $request->code,
            'name' => $request->name,
            'phone' => $request->phone,
            'created_by' => Auth::user()->id,
        ]);

        return redirect()->route('admin.delivery-agent.index')->withSuccessMessage('Created Successfully!');
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
            $data = DeliveryAgent::findOrFail($id);
            $data->update(['status' => !$data->status]);
            return response()->json(['status' => 'success']);
        }

        $title = 'Update Agent';
        $data = DeliveryAgent::findOrFail($id);
        $link = Route('admin.delivery-agent.update', $id);
        return view('admin.delivery-agent.edit', compact('title', 'data', 'link'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate(['name' => 'required']);

        $data = DeliveryAgent::findOrFail($id);
        $data->update([
            'code' => $request->code,
            'name' => $request->name,
            'phone' => $request->phone,
            'updated_by' => Auth::user()->id,
        ]);

        return redirect()->route('admin.delivery-agent.index')->withSuccessMessage('Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Recovery Deleted Data
        if (request()->has('recovery') && request('recovery') == 'true') {
            $data = DeliveryAgent::onlyTrashed()->findOrFail($id);
            $data->restore();
            return response()->json(['status' => 'success']);
        }

        // Delete Multiple Items Permanent
        if (request()->has('id') && request()->has('parmanent') && request('parmanent') == 'true') {
            foreach (request('id') as $id) {
                $data = DeliveryAgent::onlyTrashed()->findOrFail($id);
                $data->forceDelete();
            }
            return response()->json(['status' => 'success']);
        }

        // Delete Single Item Permanent
        if (request()->has('parmanent') && request('parmanent') == 'true') {
            $data = DeliveryAgent::onlyTrashed()->findOrFail($id);
            $data->forceDelete();
            return response()->json(['status' => 'success']);
        }

        // Delete Multiple Items
        if (request()->has('id')) {
            foreach (request('id') as $id) {
                $data = DeliveryAgent::findOrFail($id);
                $data->update(['deleted_by' => Auth::user()->id]);
                $data->delete();
            }
            return response()->json(['status' => 'success']);
        }

        // Delete Single Item
        $data = DeliveryAgent::findOrFail($id);
        $data->update(['deleted_by' => Auth::user()->id]);
        $data->delete();

        return response()->json(['status' => 'success']);
    }
}
