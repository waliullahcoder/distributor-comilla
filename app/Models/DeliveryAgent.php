<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DeliveryAgent extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['code', 'name', 'phone', 'status', 'created_by', 'updated_by', 'deleted_by'];

    public function packages()
    {
        return $this->hasMany(DeliveryAgentPackage::class, 'delivery_agent_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'delivery_agent_id');
    }
}
