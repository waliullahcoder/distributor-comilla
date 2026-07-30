<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'slug', 'phone', 'status'];

    public function packages()
    {
        return $this->hasMany(CampaignPackage::class, 'campaign_id');
    }

    public function list()
    {
        return $this->hasMany(CampaignList::class, 'campaign_id')->orderBy('order', 'asc');
    }

    public function reviews()
    {
        return $this->hasMany(CampaignReview::class, 'campaign_id');
    }

    public function facilities()
    {
        return $this->hasMany(CampaignFacilities::class, 'campaign_id');
    }

    public function faqs()
    {
        return $this->hasMany(CampaignFaq::class, 'campaign_id');
    }
}
