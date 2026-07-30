<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestorSattlementList extends Model
{
    use HasFactory;
    protected $fillable = ['investor_sattlement_id', 'invest_id', 'amount'];

    public function invest()
    {
        return $this->belongsTo(Invest::class, 'invest_id');
    }
}
