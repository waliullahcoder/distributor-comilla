<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SalesDelivery extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['vendor_id', 'sales_id', 'client_id', 'delivery_date', 'total_amount','total_delivery_amount', 'discount', 'total_paid', 'status', 'created_by'];

   
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }


    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id')->withoutGlobalScope(CompanyScope::class)->withTrashed();
    }

    public function sales()
    {
        return $this->belongsTo(Sales::class, 'sales_id')->withTrashed();
    }

    public function salesdelivery()
    {
        return $this->hasMany(SalesDeliveryList::class, 'sales_delivery_id')->with(['product', 'variant']);
    }

}
