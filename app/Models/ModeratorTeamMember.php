<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModeratorTeamMember extends Model
{
    use HasFactory;
    protected $fillable = ['moderator_team_id', 'user_id'];

    public function team()
    {
        return $this->belongsTo(ModeratorTeam::class, 'moderator_team_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
