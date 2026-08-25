<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiftingReceive extends Model
{
    use HasFactory;
    protected $fillable = ['vendor_id', 'lifting_id', 'receive_date', 'total_amount','total_receive_amount', 'discount', 'total_paid', 'status', 'created_by'];

   
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'vendor_id');
    }



    public function sales()
    {
        return $this->belongsTo(Lifting::class, 'lifting_id');
    }

    public function lifitingreceives()
    {
        return $this->hasMany(LiftingReceiveProduct::class, 'lifting_receives_id')->with(['product', 'veendor']);
    }

}
