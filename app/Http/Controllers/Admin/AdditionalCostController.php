<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdditionalCost;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdditionalCostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = AdditionalCost::first();
        return view('admin.additional-cost.edit', compact('data'));
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        AdditionalCost::updateOrCreate([], $request->only([
            'management_cost',
            'management_cost_percentage',
            'moderator_cost',
            'moderator_cost_percentage',
            'team_leader_cost',
            'team_leader_percentage',
        ]));

        return redirect()->back()->withSuccessMessage('Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
