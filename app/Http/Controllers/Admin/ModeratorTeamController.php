<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\ModeratorTeam;
use App\Models\ModeratorTeamMember;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Services\ActionButtons\ActionButtons;
use Exception;
use Illuminate\Support\Facades\DB;

class ModeratorTeamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $model = ModeratorTeam::with(['leader', 'members', 'members.user']);
            $type = request('type');
            if (!empty($type) && $type == 'trash') {
                $model->onlyTrashed();
            }
            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->addColumn('team_members', function ($row) {
                    return $row->team_member_names ?? '';
                })
                ->addColumn('status', function ($row) {
                    $status = '<div class="form-check form-switch">
                    <input class="form-check-input change-status c-pointer" data-url="' . Route('admin.moderator-team.edit', $row->id) . '" type="checkbox" name="status" ' . ($row->status == 1 ? 'checked' : '') . '>
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

        $title = "Moderator Teams";
        return view('admin.moderator-team.index', compact('title'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $title = 'Add New Team';
        $moderators = User::whereHas('roles', function ($query) {
            // $query->where('name', 'Moderator');
        })
            ->whereDoesntHave('members', function ($query) {
                $query->whereHas('team');
            })
            ->whereDoesntHave('leaders')
            ->where('status', true)
            ->orderBy('name', 'asc')->get();
        return view('admin.moderator-team.create', compact('title', 'moderators'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'user_id' => 'required',
            'team_leader' => 'required',
        ]);

        try {
            DB::transaction(function () use ($request) {
                $data = ModeratorTeam::create([
                    'name' => $request->name,
                    'team_leader' => $request->team_leader,
                    'status' => $request->boolean('status', true),
                    'created_by' => Auth::user()->id,
                ]);

                foreach ($request->user_id as $user_id) {
                    ModeratorTeamMember::create([
                        'moderator_team_id' => $data->id,
                        'user_id' => $user_id
                    ]);
                }
            });
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect()->Route('admin.moderator-team.index')->withSuccessMessage('Created Successfully!');
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
            $data = ModeratorTeam::findOrFail($id);
            $data->update(['status' => !$data->status]);
            return response()->json(['status' => 'success']);
        }

        $title = 'Update Team';
        $link = route('admin.moderator-team.update', $id);
        $data = ModeratorTeam::findOrFail($id);
        $moderators = User::whereHas('roles', function ($query) {
            $query->where('name', 'Moderator');
        })
            ->whereDoesntHave('members', function ($query) use ($id) {
                $query->whereNot('moderator_team_id', $id);
            })
            ->whereDoesntHave('leaders', function ($query) use ($id) {
                $query->whereNot('id', $id);
            })
            ->where('status', true)
            ->orderBy('name', 'asc')->get();
        return view('admin.moderator-team.edit', compact('title', 'data', 'link', 'moderators'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required',
            'user_id' => 'required',
            'team_leader' => 'required',
        ]);

        try {
            DB::transaction(function () use ($request, $id) {
                $data = ModeratorTeam::findOrFail($id);
                $data->update([
                    'name' => $request->name,
                    'team_leader' => $request->team_leader,
                    'status' => $request->boolean('status', $data->status),
                    'updated_by' => Auth::user()->id,
                ]);

                $newUserIds = $request->user_id ?? [];

                // Get currently assigned member IDs
                $existingUserIds = $data->members()->pluck('user_id')->toArray();

                // Determine which to delete (those not in new list)
                $toDelete = array_diff($existingUserIds, $newUserIds);

                // Determine which to add (those not already existing)
                $toAdd = array_diff($newUserIds, $existingUserIds);

                // Delete removed members
                if (!empty($toDelete)) {
                    ModeratorTeamMember::where('moderator_team_id', $data->id)
                        ->whereIn('user_id', $toDelete)
                        ->delete();
                }

                // Add new members
                foreach ($toAdd as $user_id) {
                    ModeratorTeamMember::create([
                        'moderator_team_id' => $data->id,
                        'user_id' => $user_id,
                    ]);
                }
            });
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }

        return redirect()->Route('admin.moderator-team.index')->withSuccessMessage('Updated Successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Recovery Deleted Data
        if (request()->has('recovery') && request('recovery') == 'true') {
            try {
                DB::transaction(function () use ($id) {
                    $data = ModeratorTeam::onlyTrashed()->findOrFail($id);
                    foreach ($data->members as $item) {
                        $check_exists = ModeratorTeamMember::whereHas('team')->where('user_id', $item->user_id)->whereNot('moderator_team_id', $id)->count();
                        if ($check_exists) {
                            throw new Exception('Somethig went wrong');
                        }
                    }
                    $data->restore();
                });
                return response()->json(['status' => 'success']);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
            }
        }

        // Delete Single Item Permanent
        if (request()->has('parmanent') && request('parmanent') == 'true') {
            $data = ModeratorTeam::onlyTrashed()->findOrFail($id);
            $data->forceDelete();
            return response()->json(['status' => 'success']);
        }

        // Delete Single Item
        $data = ModeratorTeam::findOrFail($id);
        $data->update(['deleted_by' => Auth::user()->id]);
        $data->delete();

        return response()->json(['status' => 'success']);
    }
}
