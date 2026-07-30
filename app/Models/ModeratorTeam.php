<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ModeratorTeam extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'team_leader', 'status', 'created_by', 'updated_by', 'deleted_by'];

    public function leader()
    {
        return $this->belongsTo(User::class, 'team_leader');
    }

    public function members()
    {
        return $this->hasMany(ModeratorTeamMember::class, 'moderator_team_id');
    }

    public function getTeamMemberNamesAttribute()
    {
        $this->loadMissing('members.user');
        return $this->members->pluck('user.name')->implode(' | ');
    }
}
