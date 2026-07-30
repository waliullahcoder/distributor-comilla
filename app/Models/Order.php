<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['company_id', 'order_package_id', 'client_id', 'customer_id', 'order_code', 'store_id', 'date', 'potential_delivery_date', 'invoice', 'user_name', 'user_phone', 'user_email', 'user_phone_alt', 'shipping_address_id', 'shipping_address', 'area_id', 'shipping_charge', 'sub_total', 'discount', 'total', 'paid', 'due', 'advance', 'receive', 'total_return', 'payment_method', 'coupon_id', 'delivery_man_id', 'delivery_agent_id', 'delivery_package_id', 'area_type', 'delivery_cost', 'return_cost', 'collected', 'status', 'courier_assigned', 'cancel_approve', 'gate_pass', 'order_type', 'pending_at', 'confirmed_at', 'processing_at', 'delivered_at', 'collected_at', 'canceled_at', 'return_at', 'order_note', 'commission', 'created_by', 'updated_by', 'deleted_by'];

    protected static function booted()
    {
        static::addGlobalScope(function ($query) {
            if (Auth::check() && is_array(Auth::user()->stores) && count(Auth::user()->stores)) {
                $query->whereIn('store_id', Auth::user()->stores);
            }
        });
    }

    public function products()
    {
        return $this->hasMany(OrderProduct::class, 'order_id')->with(['product']);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function agent()
    {
        return $this->belongsTo(DeliveryAgent::class, 'delivery_agent_id');
    }

    public function deliveryPackage()
    {
        return $this->belongsTo(DeliveryAgentPackage::class, 'delivery_package_id');
    }

    public function staff()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function address()
    {
        return $this->belongsTo(ShippingAddress::class, 'shipping_address_id');
    }

    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    public function area()
    {
        return $this->belongsTo(Area::class, 'area_id');
    }

    public function delivery_man()
    {
        return $this->belongsTo(DeliveryMan::class, 'delivery_man_id');
    }

    public function package()
    {
        return $this->belongsTo(OrderPackage::class, 'order_package_id');
    }

    public function getFormattedDateAttribute()
    {
        return date('d-m-Y', strtotime($this->date));
    }

    protected $appends = ['formattedDate'];
}
