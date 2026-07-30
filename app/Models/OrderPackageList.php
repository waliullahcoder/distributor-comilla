<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderPackageList extends Model
{
    use HasFactory;
    protected $fillable = ['order_package_id', 'product_id', 'rate', 'qty', 'amount'];

    public function package()
    {
        return $this->belongsTo(OrderPackage::class, 'order_package_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
