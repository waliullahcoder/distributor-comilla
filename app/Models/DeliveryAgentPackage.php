<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryAgentPackage extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['delivery_agent_id', 'name', 'base_rate', 'base_weight', 'additional_rate', 'inside_dhaka', 'subarea_dhaka', 'inside_chittagong', 'subarea_chittagong', 'district_level', 'return_charge_type', 'return_charge', 'status', 'created_by', 'updated_by', 'deleted_by'];

    public function agent()
    {
        return $this->belongsTo(DeliveryAgent::class, 'delivery_agent_id');
    }
}
