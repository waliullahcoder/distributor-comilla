<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModeratorPayment extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['coa_setup_id', 'serial_no', 'year', 'month', 'date', 'member_order_qty', 'member_order_amout', 'member_qty_commission', 'member_amount_commission', 'leader_order_qty', 'leader_order_amout', 'leader_qty_commission', 'leader_amount_commission', 'total_commission', 'created_by', 'updated_by', 'deleted_by'];

    public function list()
    {
        return $this->hasMany(ModeratorPaymentList::class, 'moderator_payment_id');
    }

    public function getModeratorNamesAttribute()
    {
        $this->loadMissing('list.user');
        return $this->list->pluck('user.name')->implode(' | ');
    }

    public function orders()
    {
        return $this->hasMany(ModeratorPaymentOrder::class, 'moderator_payment_id');
    }

    public function transactions()
    {
        return $this->hasMany(AccountTransaction::class, 'voucher_no', 'serial_no')->where('voucher_type', 'Moderator Payment');
    }
}
