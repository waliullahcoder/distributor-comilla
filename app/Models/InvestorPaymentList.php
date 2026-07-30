<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestorPaymentList extends Model
{
    use HasFactory;
    protected $fillable = ['investor_payment_id', 'profit_distribution_list_id', 'month', 'year', 'invest_qty', 'invest_amount', 'profit_amount'];
}
