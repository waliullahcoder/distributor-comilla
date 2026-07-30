<?php

namespace App\Http\Controllers\Admin;

use App\HelperClass;
use App\Models\Campaign;
use Illuminate\Support\Str;
use App\Models\OrderPackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\CampaignPackage;
use App\Services\ActionButtons\ActionButtons;
use Yajra\DataTables\Facades\DataTables;

class CampaignController extends Controller
{
    public $path;
    public $title;
    public $create_title;
    public $edit_title;
    public $model;
    public function __construct()
    {
        $this->path = 'campaign';
        $this->title = 'All Campaigns';
        $this->create_title = 'Add Campaign';
        $this->edit_title = 'Update Campaign';
        $this->model = Campaign::class;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $model = $this->model::orderBy('id', 'desc');
            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $status = '<div class="form-check form-switch">
                    <input class="form-check-input change-status c-pointer" data-url="' . Route('admin.campaign.edit', $row->id) . '" type="checkbox" name="status" ' . ($row->status == 1 ? 'checked' : '') . '>
                    </div>';
                    return $status;
                })
                ->addColumn('link', function ($row) {
                    return route('frontend.campaign', $row->slug);
                })
                ->addColumn('actions', function ($row) {
                    $data = [
                        'id' => $row->id,
                        'edit' => true,
                    ];

                    $addiotional_buttons = '';
                    $addiotional_buttons .= '<a class="btn btn-sm border-0 px-10px fs-15 text-white tt btn-print-1" href="' . Route('admin.campaign-review.index', $row->id) . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Review"><i class="fad fa-stars"></i></a>';
                    $addiotional_buttons .= '<a class="btn btn-sm border-0 px-10px fs-15 text-white tt btn-print-1" href="' . Route('admin.campaign-faq.index', $row->id) . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Add FAQ"><i class="fad fa-stars"></i></a>';
                    $addiotional_buttons .= '<a class="btn btn-sm border-0 px-10px fs-15 text-white tt btn-print-1" href="' . Route('admin.campaign-facility.index', $row->id) . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Facility"><i class="fad fa-stars"></i></a>';
                    $addiotional_buttons .= '<a class="btn btn-sm border-0 px-10px fs-15 text-white tt btn-print-2" href="' . Route('admin.campaign-section.index', $row->id) . '" data-bs-toggle="tooltip" data-bs-placement="top" title="Add Section"><i class="fas fa-window-alt"></i></a>';
                    return ActionButtons::actions($data, $addiotional_buttons);
                })
                ->rawColumns(['status', 'actions'])
                ->make(true);
        }

        return view('admin.campaign.index', ['title' => $this->title]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = $this->create_title;
        $packages = OrderPackage::where('status', 1)->orderBy('name', 'asc')->get();
        return view("admin.{$this->path}.create", compact('title', 'packages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'order_package_id' => 'required',
        ]);

        DB::transaction(function () use ($request) {
            $slug = Str::slug($request->name);
            $same_slug_count = $this->model::where('slug', 'LIKE', $slug . '%')->count();
            $slug_suffix = $same_slug_count ? '-' . $same_slug_count + 1 : '';
            $slug .= $slug_suffix;

            $data = $this->model::create([
                'name' => $request->name,
                'slug' => $slug,
                'phone' => $request->phone,
            ]);

            foreach ($request->order_package_id as $order_package_id) {
                CampaignPackage::create([
                    'campaign_id' => $data->id,
                    'order_package_id' => $order_package_id
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
        $packages = OrderPackage::where('status', 1)->orderBy('name', 'asc')->get();
        return view("admin.{$this->path}.edit", compact('title', 'data', 'packages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'order_package_id' => 'required',
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
                'phone' => $request->phone,
            ]);

            CampaignPackage::where('campaign_id', $id)->delete();
            foreach ($request->order_package_id as $order_package_id) {
                CampaignPackage::create([
                    'campaign_id' => $data->id,
                    'order_package_id' => $order_package_id
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
        return HelperClass::resourceDataDelete($this->model, $id);
    }
}
