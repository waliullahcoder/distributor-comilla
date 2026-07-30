<?php

namespace App\Services\Statement\Vendor;

use App\Models\Lifting;
use App\Models\LiftingReturn;
use App\Models\Vendor;
use App\Models\VendorPayment;
use Carbon\CarbonPeriod;

class Statement
{
    public static function previousBalance($vendor_id, $fromDate)
    {
        $liftingAmount = Lifting::where('vendor_id', $vendor_id)->where('lifting_date', '<', $fromDate)->sum('total_cost');
        $paymentAmount = VendorPayment::where('vendor_id', $vendor_id)->where('payment_date', '<', $fromDate)->whereNot('type', 'adjust')->sum('amount');
        $returnAmount = LiftingReturn::where('vendor_id', $vendor_id)->where('date', '<', $fromDate)->sum('amount');
        return $liftingAmount - ($returnAmount + $paymentAmount);
    }

    public static function Statement($vendor_id, $fromDate, $toDate, $previousBalance)
    {
        $balance = $previousBalance;
        $vendorInfo = Vendor::where('id', $vendor_id)->first();
        $dateRange = CarbonPeriod::create($fromDate, $toDate);
        $statements = [];
        foreach ($dateRange as $date) {
            $d = $date->format('Y-m-d');

            $liftingAmount = Lifting::where('vendor_id', $vendor_id)
                ->where('lifting_date',  $d)
                ->get();

            foreach ($liftingAmount as $liftingAmount) {
                $balance += $liftingAmount->total_cost - $liftingAmount->discount;
                $row = [
                    'vendor_name' => $vendorInfo->name,
                    'date' => $date->format('d-m-Y'),
                    'lifting' => $liftingAmount->total_cost - $liftingAmount->discount,
                    'payment' => 0.00,
                    'return' => 0.00,
                    'balance' => $balance,
                    'remarks' => $liftingAmount->payment_type . ' purchase on ' . $liftingAmount->lifting_no . ' which manual voucher no ' . $liftingAmount->voucher_no,
                ];
                array_push($statements, $row);
            }

            $paymentAmount = VendorPayment::where('vendor_id', $vendor_id)
                ->where('payment_date', $d)->whereNot('type', 'adjust')
                ->get();

            foreach ($paymentAmount as $paymentAmount) {
                $balance -= $paymentAmount->amount;
                $row = [
                    'vendor_name' => $vendorInfo->name,
                    'date' => $date->format('d-m-Y'),
                    'lifting' => 0.00,
                    'payment' => $paymentAmount->amount,
                    'return' => 0.00,
                    'balance' => $balance,
                    'remarks' => $paymentAmount->payment_type . ' Payment on ' . $paymentAmount->payment_no . ' which Payment Mode ' . $paymentAmount->type,
                ];
                array_push($statements, $row);
            }

            $returnAmount = LiftingReturn::where('vendor_id', $vendor_id)
                ->where('date', $d)
                ->get();

            foreach ($returnAmount as $returnAmount) {
                $invoices = '';
                foreach ($returnAmount->list as $key => $item) {
                    $invoices .= $key > 0 ? ', ' : '' . $item->lifting_product->lifting->lifting_no;
                }
                $balance -= $returnAmount->amount;
                $row = [
                    'vendor_name' => $vendorInfo->name,
                    'date' => $date->format('d-m-Y'),
                    'lifting' => 0.00,
                    'payment' => 0.00,
                    'return' => $returnAmount->amount,
                    'balance' => $balance,
                    'remarks' => 'Return No ' . $returnAmount->return_no . ' against on invoice no ' . $invoices,
                ];
                array_push($statements, $row);
            }
        }
        return $statements;
    }
}
