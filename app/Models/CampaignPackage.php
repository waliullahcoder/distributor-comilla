<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignPackage extends Model
{
    use HasFactory;
    protected $fillable = ['campaign_id', 'order_package_id'];

    public function package()
    {
        return $this->belongsTo(OrderPackage::class, 'order_package_id');
    }
}
