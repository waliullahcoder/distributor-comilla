<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPackage extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'image', 'shipping_charge', 'discount', 'amount', 'net_amount', 'description', 'status'];

    public function list()
    {
        return $this->hasMany(OrderPackageList::class, 'order_package_id');
    }
}
