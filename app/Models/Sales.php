<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sales extends Model
{
    use HasFactory;
    protected $fillable = ['company_id', 'product_type', 'store_id','vendor_id', 'staff_id', 'client_id', 'invoice', 'date', 'sales_type', 'total_amount','total_delivery_amount', 'discount', 'total_paid', 'status', 'created_by', 'updated_by', 'deleted_by'];

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id')->withTrashed();
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id')->withTrashed();
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id')->withoutGlobalScope(CompanyScope::class)->withTrashed();
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'staff_id')->withTrashed();
    }

    public function list()
    {
        return $this->hasMany(SalesList::class, 'sales_id')->with(['product', 'variant']);
    }

    public function deliveryList()
    {
        return $this->belongsTo(DeliveryList::class, 'id', 'sales_id')->with('delivery');
    }
}
