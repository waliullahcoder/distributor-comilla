<?php

namespace App\Services\LiftingRealization;

use Carbon\Carbon;
use App\Models\Lifting;
use App\Models\LiftingReturnList;
use App\Models\VendorPayment;

class LiftingRealization
{
    public $vendor_id;
    public $year;
    public $month;

    public function __construct($vendor_id, $year, $month)
    {
        $this->vendor_id = $vendor_id;
        $this->year = $year;
        $this->month = $month;
    }

    public function getMonthlyInfo()
    {
        $firstDateOfMonth = Carbon::create($this->year, $this->month)->firstOfMonth()->format('Y-m-d');
        $lastDateOfMonth = Carbon::create($this->year, $this->month)->lastOfMonth()->format('Y-m-d');

        $liftingAmount = Lifting::where('vendor_id', $this->vendor_id)
            ->where('lifting_date', '>=', $firstDateOfMonth)
            ->where('lifting_date', '<=', $lastDateOfMonth)
            ->sum('total_cost');

        $paymentAmount = VendorPayment::where('vendor_id', $this->vendor_id)
            ->where('type', '!=', 'adjust')
            ->where('payment_date', '>=', $firstDateOfMonth)
            ->where('payment_date', '<=', $lastDateOfMonth)
            ->sum('amount');

        $returnAmount = LiftingReturnList::with(['return'])
            ->whereHas('return', function ($query) use ($firstDateOfMonth, $lastDateOfMonth) {
                $query->where('date', '>=', $firstDateOfMonth)
                    ->where('date', '<=', $lastDateOfMonth);
            })
            ->where('vendor_id', $this->vendor_id)
            ->sum('amount');

        return [
            'lifting' => $liftingAmount,
            'payment' => $paymentAmount,
            'return' => $returnAmount,
            'balance' => $liftingAmount - ($paymentAmount + $returnAmount),
        ];
    }

    public function getYearlyInfo()
    {
        $firstDateOfYear = Carbon::create($this->year)->firstOfYear()->format('Y-m-d');
        $lastDateOfYear = Carbon::create($this->year)->lastofYear()->format('Y-m-d');

        $liftingAmount = Lifting::where('vendor_id', $this->vendor_id)
            ->where('lifting_date', '>=', $firstDateOfYear)
            ->where('lifting_date', '<=', $lastDateOfYear)
            ->sum('total_cost');

        $paymentAmount = VendorPayment::where('vendor_id', $this->vendor_id)
            ->where('type', '!=', 'adjust')
            ->where('payment_date', '>=', $firstDateOfYear)
            ->where('payment_date', '<=', $lastDateOfYear)
            ->sum('amount');

        $returnAmount = LiftingReturnList::with(['return'])
            ->whereHas('return', function ($query) use ($firstDateOfYear, $lastDateOfYear) {
                $query->where('date', '>=', $firstDateOfYear)
                    ->where('date', '<=', $lastDateOfYear);
            })
            ->where('vendor_id', $this->vendor_id)
            ->sum('amount');

        return [
            'lifting' => $liftingAmount,
            'payment' => $paymentAmount,
            'return' => $returnAmount,
            'balance' => $liftingAmount - ($paymentAmount + $returnAmount),
        ];
    }

    public function getPreviousBalance()
    {
        $firstDateOfYear = Carbon::create($this->year)->firstOfYear()->format('Y-m-d');
        $liftingAmount = Lifting::where('vendor_id', $this->vendor_id)
            ->where('lifting_date', '<', $firstDateOfYear)
            ->sum('total_cost');
        $paymentAmount = VendorPayment::where('vendor_id', $this->vendor_id)
            ->where('payment_date', '<', $firstDateOfYear)
            ->where('type', '!=', 'adjust')
            ->sum('amount');
        return $liftingAmount - $paymentAmount;
    }
}
