<?php

namespace App\Http\Controllers\Admin;

use DataTables;
use Carbon\Carbon;
use App\HelperClass;
use App\Models\Invest;
use App\Models\InvestRenew;
use Illuminate\Http\Request;
use App\Models\InvestorProfit;
use App\Models\InvestRenewList;
use Illuminate\Support\Facades\DB;
use App\Models\Scopes\CompanyScope;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\ActionButtons\ActionButtons;

class InvestRenewController extends Controller
{
    public $path;
    public $title;
    public $create_title;
    public $edit_title;
    public $model;
    public function __construct()
    {
        $this->path = 'invest-renew';
        $this->title = 'Invest Renews';
        $this->create_title = 'Add Renew';
        $this->edit_title = 'Update Renew';
        $this->model = InvestRenew::class;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $model = $this->model::with(['list', 'list.investor'])->orderBy('id', 'desc');
            $type = request('type');
            if (!empty($type) && $type == 'trash') {
                $model->onlyTrashed();
            }
            return DataTables::eloquent($model)
                ->addIndexColumn()
                ->addColumn('date', function ($row) {
                    return date('d-m-Y', strtotime($row->date));
                })
                ->addColumn('qty', function ($row) {
                    return number_format($row->list->sum('qty'));
                })
                ->addColumn('amount', function ($row) {
                    return number_format($row->list->sum('amount'));
                })
                ->addColumn('investors', function ($row) {
                    return $row->investor_names;
                })
                ->addColumn('actions', function ($row) {
                    $type = request('type');
                    $data = [
                        'id' => $row->id,
                        'edit' => !empty($type) && $type == 'trash' ? false : true,
                    ];
                    $addiotional_buttons = '<a class="btn btn-sm border-0 px-10px btn-primary mw-fit text-white tt" href="' . Route('admin.invest-renew.show', $row->id) . '" style="min-height: 28px;" data-bs-toggle="tooltip" data-bs-placement="top" title="View"><i class="fas fa-eye"></i></a>';
                    $profit = InvestorProfit::where('month', $row->month)->where('year', $row->year)->first();
                    $edit = 'yes';
                    $delete = 'yes';
                    if (!is_null($profit)) {
                        $edit = 'no';
                        $delete = 'no';
                    }
                    return ActionButtons::actions($data, $addiotional_buttons, $delete, $edit);
                })
                ->rawColumns(['checkbox', 'actions'])
                ->make(true);
        }

        return view("admin.{$this->path}.index", ['title' => $this->title]);
    }

    public function serialNo()
    {
        $data = $this->model::withoutGlobalScope(CompanyScope::class)->withTrashed()->select(['serial_no'])->whereDate('created_at', '>=', date('Y-m-01'))->whereDate('created_at', '<=', date('Y-m-t'))->orderBy('id', 'desc')->first();
        if ($data) {
            $trim = str_replace("IR", '', $data->serial_no);
            $dataPrefix = (int)$trim + 1;
            $serial_no = "IR" . $dataPrefix;
        } else {
            $serial_no = "IR" . date('y') . date('m') . '001';
        }
        return $serial_no;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        if ($request->ajax()) {
            $date = Carbon::parse($request->date);
            $invests = Invest::where('sattled', 0)
                ->whereDoesntHave('renews', function ($query) use ($date) {
                    $query->where('month', $date->format('F'))
                        ->where('year', $date->year);
                })
                ->get();
            return response()->json([
                'status' => 'success',
                'year' => $date->format('Y'),
                'month' => $date->format('F'),
                'data' => view('admin.invest-renew.table', ['invests' => $invests, 'date' => $date])->render()
            ]);
        }

        $title = $this->create_title;
        $serial_no = $this->serialNo();
        $invests = Invest::where('sattled', 0)
            ->whereDoesntHave('renews', function ($query) {
                $query->where('month', now()->format('F'))
                    ->where('year', now()->year);
            })
            ->get();
        $date = now();

        return view("admin.{$this->path}.create", compact('title', 'serial_no', 'invests', 'date'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required',
            'month' => 'required',
            'invest_id' => 'required',
            'date' => 'required'
        ]);

        DB::transaction(function () use ($request) {
            $data = $this->model::create([
                'serial_no' => $this->serialNo(),
                'month' => $request->month,
                'year' => $request->year,
                'date' => date('Y-m-d', strtotime($request->date)),
                'remarks' => $request->remarks,
                'approved' => true,
                'status' => 'Approved',
                'created_by' => Auth::user()->id,
            ]);

            foreach ($request->invest_id as $invest_id) {
                InvestRenewList::create([
                    'invest_renew_id' => $data->id,
                    'investor_id' => $request->investor_id[$invest_id],
                    'invest_id' => $invest_id,
                    'date' => date('Y-m-d', strtotime($request->date)),
                    'month' => $request->month,
                    'year' => $request->year,
                    'qty' => $request->qty[$invest_id],
                    'amount' => $request->amount[$invest_id],
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
        $data = $this->model::findOrFail($id);
        $title = 'View Renew List';
        return view("admin.{$this->path}.view", compact('data', 'title'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, string $id)
    {
        if ($request->ajax()) {
            $date = Carbon::parse($request->date);
            $data = $this->model::findOrFail($id);
            $invests = Invest::where('sattled', 0)
                ->whereDoesntHave('renews', function ($query) use ($date) {
                    $query->where('month', $date->format('F'))
                        ->where('year', $date->year);
                });
            if ($data->month == $date->format('F') && $data->year == $date->year) {
                $invests->orWhereHas('renews', function ($query) use ($id) {
                    $query->where('invest_renew_id', $id);
                });
            }
            $invests = $invests->get();
            return response()->json([
                'status' => 'success',
                'year' => $date->format('Y'),
                'month' => $date->format('F'),
                'data' => view('admin.invest-renew.table', ['invests' => $invests, 'data' => $data, 'date' => $date])->render()
            ]);
        }

        $title = $this->create_title;
        $data = $this->model::findOrFail($id);
        $date = Carbon::parse($data->date);
        $invests = Invest::where('sattled', 0)
            ->whereDoesntHave('renews', function ($query) use ($date) {
                $query->where('month', $date->format('F'))
                    ->where('year', $date->year);
            })
            ->orWhereHas('renews', function ($query) use ($id) {
                $query->where('invest_renew_id', $id);
            })
            ->get();

        $additionalData = [
            'invests' => $invests,
            'date' => $date
        ];
        return HelperClass::resourceDataEdit($this->model, $id, $this->path, $title, $additionalData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'year' => 'required',
            'month' => 'required',
            'invest_id' => 'required',
            'date' => 'required'
        ]);

        DB::transaction(function () use ($request, $id) {
            $data = $this->model::findOrFail($id);
            $data->update([
                'month' => $request->month,
                'year' => $request->year,
                'date' => date('Y-m-d', strtotime($request->date)),
                'remarks' => $request->remarks,
                'updated_by' => Auth::user()->id,
            ]);

            InvestRenewList::where('invest_renew_id', $id)->delete();
            foreach ($request->invest_id as $invest_id) {
                InvestRenewList::create([
                    'invest_renew_id' => $data->id,
                    'investor_id' => $request->investor_id[$invest_id],
                    'invest_id' => $invest_id,
                    'date' => date('Y-m-d', strtotime($request->date)),
                    'month' => $request->month,
                    'year' => $request->year,
                    'qty' => $request->qty[$invest_id],
                    'amount' => $request->amount[$invest_id],
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
        // Delete Single Item
        $data = $this->model::findOrFail($id);
        $data->update(['deleted_by' => Auth::user()->id]);
        $data->forceDelete();
        return response()->json(['status' => 'success']);
    }
}
