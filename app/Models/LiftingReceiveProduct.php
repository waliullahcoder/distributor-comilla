<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiftingReceiveProduct extends Model
{
    use HasFactory;
    protected $fillable = ['lifting_receives_id', 'vendor_id', 'product_id', 'variant_id', 'do_ratio', 'offer_qty','trade_discount','rate', 'qty', 'receive', 'receive_date', 'receive_amount'];

   

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->with(['category', 'vendors', 'attribute'])->withTrashed();
    }

   public function salesdelivery()
    {
        return $this->belongsTo(
            LiftingReceive::class,
            'lifting_receives_id'
        );
    }
   
}
