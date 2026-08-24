<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesDeliveryList extends Model
{
    use HasFactory;
    protected $fillable = ['sales_delivery_id', 'client_id', 'product_id', 'variant_id', 'do_ratio', 'offer_qty','trade_discount','rate', 'qty', 'delivery', 'delivery_date', 'delivery_amount'];

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id')->with('area')->withTrashed();
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->with(['category', 'vendors', 'attribute'])->withTrashed();
    }

    public function variant()
    {
        return $this->belongsTo(ProductSku::class, 'variant_id');
    }

   public function salesdelivery()
    {
        return $this->belongsTo(
            SalesDelivery::class,
            'sales_delivery_id',
            'id'
        )->withTrashed();
    }
   
}
