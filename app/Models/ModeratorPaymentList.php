<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeratorPaymentList extends Model
{
    use HasFactory;
    protected $fillable = ['moderator_payment_id', 'user_id', 'month', 'year', 'order_qty', 'order_amount', 'qty_commission', 'amount_commission', 'leader_qty', 'leader_amount', 'leader_qty_commission', 'leader_amount_commission', 'total_commission'];

    public function payment()
    {
        return $this->belongsTo(ModeratorPayment::class, 'moderator_payment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
