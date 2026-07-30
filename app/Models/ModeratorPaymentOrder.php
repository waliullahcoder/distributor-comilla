<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeratorPaymentOrder extends Model
{
    use HasFactory;
    protected $fillable = ['moderator_payment_id', 'user_id', 'order_id'];
}
