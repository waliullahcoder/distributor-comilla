<?php

namespace App\Http\Controllers\Admin;

use App\HelperClass;
use App\Http\Controllers\Controller;
use App\Models\CampaignList;
use Illuminate\Http\Request;

class CampaignSectionController extends Controller
{
    public $path;
    public $title;
    public $create_title;
    public $edit_title;
    public $model;
    public function __construct()
    {
        $this->path = 'campaign-section';
        $this->title = 'Page Sections';
        $this->create_title = 'Add Section';
        $this->edit_title = 'Update Section';
        $this->model = CampaignList::class;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        return HelperClass::resourceDataView($this->model::where('campaign_id', $id)->orderBy('id', 'desc'), 'image', NULL, $this->path, $this->title);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request, string $id)
    {
        if ($request->ajax()) {
            $type = $request->type;
            return response()->json([
                'status' => 'success',
                'data' => view('admin.campaign-section.partial.collumns', compact('type'))->render()
            ]);
        }

        $title = $this->create_title;
        $order = $this->model::where('campaign_id', $id)->max('order') + 1;
        return view("admin.{$this->path}.create", compact('title', 'order'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $id)
    {
        $request->validate([
            'type' => 'required',
            'image' => 'image',
        ]);

        $list = array();
        if (!is_null(@$request->list[0])) {
            foreach (json_decode($request->list[0]) as $item) {
                array_push($list, $item->value);
            }
        }

        $this->model::create([
            'campaign_id' => $id,
            'type' => $request->type,
            'title' => $request->title,
            'image' => isset($request->image) ? HelperClass::saveImage($request->image, 500, 'media/campaign-section') : NULL,
            'video' => $request->video,
            'list' => implode('|', $list),
            'description' => $request->description,
            'order' => $request->order,
        ]);

        return redirect()->route("admin.{$this->path}.index", $id)->withSuccessMessage('Created Successfully!');
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
        if ($request->ajax()) {
            $type = $request->type;
            return response()->json([
                'status' => 'success',
                'data' => view('admin.campaign-section.partial.collumns', compact('type'))->render()
            ]);
        }
        $title = $this->edit_title;
        $data = $this->model::findOrFail($id);
        return view("admin.{$this->path}.edit", compact('title', 'data'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'type' => 'required',
            'image' => 'image',
        ]);

        $list = array();
        if (!is_null(@$request->list[0])) {
            foreach (json_decode($request->list[0]) as $item) {
                array_push($list, $item->value);
            }
        }

        $data = $this->model::findOrFail($id);
        $data->update([
            'type' => $request->type,
            'title' => $request->title,
            'image' => isset($request->image) ? HelperClass::saveImage($request->image, 500, 'media/campaign-section', $data->image) : $data->image,
            'video' => $request->video,
            'list' => implode('|', $list),
            'description' => $request->description,
            'order' => $request->order,
        ]);

        return redirect()->route("admin.{$this->path}.index", $data->campaign_id)->withSuccessMessage('Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return HelperClass::resourceDataDelete($this->model, $id, 'image');
    }
}
