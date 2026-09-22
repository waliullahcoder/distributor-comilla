<?php

namespace App\Services\Statement\Vendor;

use App\Models\LiftingReceive;
use App\Models\LiftingReturn;
use App\Models\Vendor;
use App\Models\VendorPayment;
use Carbon\CarbonPeriod;

class Statement
{
    public static function previousBalance($vendor_id, $fromDate)
    {
        $liftingAmount = LiftingReceive::where('vendor_id', $vendor_id)
            ->where('receive_date', '<', $fromDate)
            ->sum('total_receive_amount');

        $paymentAmount = VendorPayment::where('vendor_id', $vendor_id)
            ->where('payment_date', '<', $fromDate)
            ->whereNot('type', 'adjust')
            ->sum('amount');

        $returnAmount = LiftingReturn::where('vendor_id', $vendor_id)
            ->where('date', '<', $fromDate)
            ->sum('amount');

        return $liftingAmount - ($returnAmount + $paymentAmount);
    }

    public static function Statement(
        $vendor_id,
        $fromDate,
        $toDate,
        $previousBalance
    ) {
        $balance = $previousBalance;

        $vendorInfo = Vendor::where('id', $vendor_id)->first();

        $dateRange = CarbonPeriod::create($fromDate, $toDate);

        $statements = [];

        foreach ($dateRange as $date) {

            $d = $date->format('Y-m-d');

            /*
            |--------------------------------------------------------------------------
            | PURCHASE / LIFTING RECEIVE
            |--------------------------------------------------------------------------
            */

            $liftingAmounts = LiftingReceive::where('vendor_id', $vendor_id)
                ->where('receive_date', $d)
                ->get();

            foreach ($liftingAmounts as $liftingAmount) {

                $purchaseAmount =
                    $liftingAmount->total_receive_amount
                    - $liftingAmount->discount;

                $balance += $purchaseAmount;

                $row = [
                    'vendor_name' => $vendorInfo->name,

                    'date' => $date->format('d-m-Y'),

                    'lifting' => $purchaseAmount,

                    'payment' => 0.00,

                    'return' => 0.00,

                    'balance' => $balance,

                    'remarks' =>
                        ($liftingAmount->lifting
                            ? $liftingAmount->lifting->payment_type
                            : '')
                        . ' purchase on '
                        . ($liftingAmount->lifting
                            ? $liftingAmount->lifting->lifting_no
                            : '')
                        . ' which manual voucher no '
                        . ($liftingAmount->lifting
                            ? $liftingAmount->lifting->voucher_no
                            : ''),

                    // DELETE INFORMATION
                    'transaction_type' => 'purchase',
                    'transaction_id' => $liftingAmount->id,
                ];

                $statements[] = $row;
            }


            /*
            |--------------------------------------------------------------------------
            | PAYMENT
            |--------------------------------------------------------------------------
            */

            $paymentAmounts = VendorPayment::where('vendor_id', $vendor_id)
                ->where('payment_date', $d)
                ->whereNot('type', 'adjust')
                ->get();

            foreach ($paymentAmounts as $paymentAmount) {

                $balance -= $paymentAmount->amount;

                $row = [
                    'vendor_name' => $vendorInfo->name,

                    'date' => $date->format('d-m-Y'),

                    'lifting' => 0.00,

                    'payment' => $paymentAmount->amount,

                    'return' => 0.00,

                    'balance' => $balance,

                    'remarks' =>
                        $paymentAmount->payment_type
                        . ' Payment on '
                        . $paymentAmount->payment_no
                        . ' which Payment Mode '
                        . $paymentAmount->type,

                    // DELETE INFORMATION
                    'transaction_type' => 'payment',
                    'transaction_id' => $paymentAmount->id,
                ];

                $statements[] = $row;
            }


            /*
            |--------------------------------------------------------------------------
            | RETURN
            |--------------------------------------------------------------------------
            */

            $returnAmounts = LiftingReturn::where('vendor_id', $vendor_id)
                ->where('date', $d)
                ->get();

            foreach ($returnAmounts as $returnAmount) {

                $invoices = '';

                foreach ($returnAmount->list as $key => $item) {

                    if ($item->lifting_product && $item->lifting_product->lifting) {

                        $invoices .=
                            ($key > 0 ? ', ' : '')
                            . $item->lifting_product->lifting->lifting_no;
                    }
                }

                $balance -= $returnAmount->amount;

                $row = [
                    'vendor_name' => $vendorInfo->name,

                    'date' => $date->format('d-m-Y'),

                    'lifting' => 0.00,

                    'payment' => 0.00,

                    'return' => $returnAmount->amount,

                    'balance' => $balance,

                    'remarks' =>
                        'Return No '
                        . $returnAmount->return_no
                        . ' against on invoice no '
                        . $invoices,

                    // DELETE INFORMATION
                    'transaction_type' => 'return',
                    'transaction_id' => $returnAmount->id,
                ];

                $statements[] = $row;
            }
        }

        return $statements;
    }
}