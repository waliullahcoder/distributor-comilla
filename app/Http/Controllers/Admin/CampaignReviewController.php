<?php

namespace App\Http\Controllers\Admin;

use App\HelperClass;
use App\Http\Controllers\Controller;
use App\Models\CampaignReview;
use Illuminate\Http\Request;

class CampaignReviewController extends Controller
{
    public $path;
    public $title;
    public $create_title;
    public $edit_title;
    public $model;
    public function __construct()
    {
        $this->path = 'campaign-review';
        $this->title = 'Campaign Reviews';
        $this->create_title = 'Add Review';
        $this->edit_title = 'Update Review';
        $this->model = CampaignReview::class;
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
    public function create()
    {
        $title = $this->create_title;
        return view("admin.{$this->path}.create", compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $id)
    {
        $request->validate([
            'image' => 'image|required',
        ]);

        $this->model::create([
            'campaign_id' => $id,
            'name' => $request->name,
            'image' => isset($request->image) ? HelperClass::saveImage($request->image, 500, 'media/campaign-review') : NULL,
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
    public function edit(string $id)
    {
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
            'image' => 'image',
        ]);

        $data = $this->model::findOrFail($id);
        $data->update([
            'name' => $request->name,
            'image' => isset($request->image) ? HelperClass::saveImage($request->image, 500, 'media/campaign-review', $data->image) : $data->image,
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
