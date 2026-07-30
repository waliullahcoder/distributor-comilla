@extends('layouts.admin.edit_app')

@section('content')
    <div class="row g-3">
        <div class="col-md-4 col-sm-6">
            <label for="name" class="form-label"><b>Team Name <span class="text-danger">*</span></b></label>
            <input type="text" class="form-control" id="name" name="name" placeholder="Team Name"
                value="{{ old('name', $data->name) }}" required>
        </div>
        <div class="col-md-4 col-sm-6">
            <label for="user_id" class="form-label"><b>Team Members <span class="text-danger">*</span></b></label>
            <select name="user_id[]" id="user_id" class="select form-select" data-placeholder="Select Members.." multiple
                required>
                <option value=""></option>
                @foreach ($moderators as $item)
                    <option value="{{ $item->id }}"
                        {{ is_array(old('user_id', $data->members->pluck('user_id')->toArray())) && in_array($item->id, old('user_id', $data->members->pluck('user_id')->toArray())) ? 'selected' : '' }}>
                        {{ $item->name }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 col-sm-6">
            <label for="team_leader" class="form-label"><b>Team Leader <span class="text-danger">*</span></b></label>
            <select name="team_leader" id="team_leader" class="select form-select" data-placeholder="Select Leader.."
                required>
                <option value=""></option>
                @foreach ($data->members as $item)
                    <option value="{{ $item->user_id }}" {{ $item->user_id == $data->team_leader ? 'selected' : '' }}>
                        {{ $item->user->name }}</option>
                @endforeach
            </select>
        </div>
    </div>
@endsection

@push('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#user_id').on('change', function() {
                let selectedOptions = $(this).find('option:selected');
                let $teamLeader = $('#team_leader');

                $teamLeader.empty(); // Clear previous options
                $teamLeader.append('<option value="">Select Leader..</option>'); // Default option

                selectedOptions.each(function() {
                    let value = $(this).val();
                    let text = $(this).text();
                    $teamLeader.append(`<option value="${value}">${text}</option>`);
                });
            });
        });
    </script>
@endpush
