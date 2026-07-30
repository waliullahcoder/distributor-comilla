@extends('layouts.admin.app')

@section('content')
    <form action="{{ Route('admin.additional-cost.update', '0') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row g-3">
            <div class="col-12">
                <div class="card">
                    <div class="card-header pe-2 py-2">
                        <h6 class="h6 mb-0 py-5px">Update Additional Cost</h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-3 col-6">
                                <label for="management_cost" class="form-label"><b>Management Cost Per Order</b></label>
                                <input type="number" class="form-control" id="management_cost" name="management_cost"
                                    placeholder="Management Cost Per Order" value="{{ $data->management_cost ?? '' }}"
                                    required>
                            </div>
                            <div class="col-md-3 col-6">
                                <label for="management_cost_percentage" class="form-label"><b>Management Cost
                                        Percentage</b></label>
                                <input type="number" class="form-control" id="management_cost_percentage"
                                    name="management_cost_percentage" placeholder="Management Cost Percentage"
                                    value="{{ $data->management_cost_percentage ?? '' }}" required>
                            </div>
                            <div class="col-md-3 col-6">
                                <label for="moderator_cost" class="form-label"><b>Moderator Cost Per Order</b></label>
                                <input type="number" class="form-control" id="moderator_cost" name="moderator_cost"
                                    placeholder="Moderator Cost Per Order" value="{{ $data->moderator_cost ?? '' }}"
                                    required>
                            </div>
                            <div class="col-md-3 col-6">
                                <label for="moderator_cost_percentage" class="form-label"><b>Moderator Cost
                                        Percentage</b></label>
                                <input type="number" class="form-control" id="moderator_cost_percentage"
                                    name="moderator_cost_percentage" placeholder="Moderator Cost Percentage"
                                    value="{{ $data->moderator_cost_percentage ?? '' }}" required>
                            </div>
                            <div class="col-md-3 col-6">
                                <label for="team_leader_cost" class="form-label"><b>Team Leader Cost Per Order</b></label>
                                <input type="number" class="form-control" id="team_leader_cost" name="team_leader_cost"
                                    placeholder="Team Leader Cost Per Order" value="{{ $data->team_leader_cost ?? '' }}"
                                    required>
                            </div>
                            <div class="col-md-3 col-6">
                                <label for="team_leader_percentage" class="form-label"><b>Team Leader Cost
                                        Percentage</b></label>
                                <input type="number" class="form-control" id="team_leader_percentage"
                                    name="team_leader_percentage" placeholder="Team Leader Cost Percentage"
                                    value="{{ $data->team_leader_percentage ?? '' }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer pe-2 py-2 text-end">
                        <button type="submit" class="btn btn-sm btn-primary">Update Now</button>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
