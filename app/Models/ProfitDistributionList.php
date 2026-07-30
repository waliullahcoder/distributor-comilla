<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProfitDistributionList extends Model
{
    use HasFactory;
    protected $fillable = ['profit_distribution_id', 'investor_id', 'month', 'year', 'invest_qty', 'invest_amount', 'profit_amount', 'paid'];

    public function investor()
    {
        return $this->belongsTo(Investor::class, 'investor_id');
    }

    public function distribution()
    {
        return $this->belongsTo(ProfitDistribution::class, 'profit_distribution_id');
    }

    public function payments()
    {
        return $this->hasMany(InvestorPaymentList::class, 'profit_distribution_list_id');
    }
}
