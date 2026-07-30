<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvestorSattlement extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['company_id', 'investor_id', 'serial_no', 'date', 'amount', 'approved', 'created_by', 'updated_by', 'deleted_by'];

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id')->withTrashed();
    }

    public function investor()
    {
        return $this->belongsTo(Investor::class, 'investor_id')->withTrashed();
    }

    public function list()
    {
        return $this->hasMany(InvestorSattlementList::class, 'investor_sattlement_id');
    }
}
