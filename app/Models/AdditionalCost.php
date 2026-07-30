<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionalCost extends Model
{
    use HasFactory;
    protected $fillable = ['management_cost', 'management_cost_percentage', 'moderator_cost', 'moderator_cost_percentage', 'team_leader_cost', 'team_leader_percentage'];
}
