<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProfitDistribution extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['serial_no', 'year', 'month', 'date', 'sales_amount', 'purchase_amount', 'monthly_cost', 'management_cost', 'delivery_cost', 'investor_profit', 'sales_commission', 'net_profit', 'created_by', 'updated_by', 'deleted_by'];

    public function list()
    {
        return $this->hasMany(ProfitDistributionList::class, 'profit_distribution_id');
    }
}
