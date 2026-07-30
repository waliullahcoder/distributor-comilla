<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestorProfitList extends Model
{
    use HasFactory;
    protected $fillable = ['investor_profit_id', 'investor_id', 'product_id', 'total_profit', 'profit_percentage', 'investor_part', 'total_share', 'individual_share', 'amount', 'deposited', 'deposited_amount', 'deposit_date'];

    public function parent()
    {
        return $this->belongsTo(InvestorProfit::class, 'investor_profit_id')->withTrashed();
    }

    public function investor()
    {
        return $this->belongsTo(Investor::class, 'investor_id')->withTrashed();
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id')->withTrashed();
    }
}
