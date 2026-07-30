<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvestorPayment extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['investor_id', 'coa_setup_id', 'payment_no', 'date', 'amount', 'deposit_type', 'bkash', 'rocket', 'nagad', 'bank_account', 'remarks', 'approved', 'status', 'created_by', 'updated_by', 'deleted_by'];

    public function investor()
    {
        return $this->belongsTo(Investor::class, 'investor_id');
    }

    public function list()
    {
        return $this->hasMany(InvestorPaymentList::class, 'investor_payment_id');
    }

    public function transactions()
    {
        return $this->hasMany(AccountTransaction::class, 'voucher_no', 'payment_no')->where('voucher_type', 'Investor Payment');
    }
}
