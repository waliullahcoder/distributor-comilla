<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestRenewList extends Model
{
    use HasFactory;
    protected $fillable = ['invest_renew_id', 'investor_id', 'invest_id', 'date', 'month', 'year', 'qty', 'amount'];

    public function investRenew()
    {
        return $this->belongsTo(InvestRenew::class, 'invest_renew_id');
    }

    public function invest()
    {
        return $this->belongsTo(Invest::class, 'invest');
    }

    public function investor()
    {
        return $this->belongsTo(Investor::class, 'investor_id');
    }
}
