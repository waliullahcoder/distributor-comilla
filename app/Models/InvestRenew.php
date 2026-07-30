<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvestRenew extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['serial_no', 'month', 'year', 'date', 'remarks', 'approved', 'status', 'created_by', 'updated_by', 'deleted_by'];

    public function list()
    {
        return $this->hasMany(InvestRenewList::class, 'invest_renew_id');
    }

    public function getInvestorNamesAttribute()
    {
        $this->loadMissing('list.investor');
        return $this->list->pluck('investor.name')->implode(' | ');
    }
}
