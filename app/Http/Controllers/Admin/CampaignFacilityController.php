<?php

namespace App\Http\Controllers\Admin;

use App\HelperClass;
use App\Http\Controllers\Controller;
use App\Models\CampaignFacilities;
use Illuminate\Http\Request;

class CampaignFacilityController extends Controller
{
    public $path;
    public $title;
    public $create_title;
    public $edit_title;
    public $model;
    public function __construct()
    {
        $this->path = 'campaign-facility';
        $this->title = 'Campaign Facilitiess';
        $this->create_title = 'Add Facility';
        $this->edit_title = 'Update Facility';
        $this->model = CampaignFacilities::class;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(string $id)
    {
        return HelperClass::resourceDataView($this->model::where('campaign_id', $id)->orderBy('id', 'desc'), NULL, NULL, $this->path, $this->title);
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
        $this->model::create([
            'campaign_id' => $id,
            'title' => $request->title,
            'description' => $request->description,
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
        $data = $this->model::findOrFail($id);
        $data->update([
            'title' => $request->title,
            'description' => $request->description,
        ]);

        return redirect()->route("admin.{$this->path}.index", $data->campaign_id)->withSuccessMessage('Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return HelperClass::resourceDataDelete($this->model, $id);
    }
}
