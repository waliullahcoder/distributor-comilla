<?php

namespace App\Models;

use App\Models\Scopes\CompanyScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'company_id', 'vendor_id', 'attribute_id', 'product_type', 'category_id', 'brand_id', 'name', 'code', 'slug', 'thumbnail', 'more_images', 'short_description', 'description', 'additional_info', 'meta_title', 'meta_description', 'meta_keyword', 'alert_quantity', 'min_order', 'max_order', 'video', 'video_id', 'ctn_size', 'choice_options',
        'attributes', 'status', 'on_request', 'show_on_website', 'trending', 'featured', 'top_rated', 'best_selling', 'serial', 'allowed_investor', 'shared_profit', 'created_by', 'updated_by', 'deleted_by'
    ];

    protected static function booted()
    {
        static::addGlobalScope(new CompanyScope);
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id')->withTrashed();
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function vendors()
    {
        return $this->hasMany(ProductVendor::class, 'product_id');
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class, 'product_id');
    }

    public function price()
    {
        return $this->belongsTo(ProductPrice::class, 'id', 'product_id');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id')->with('customer');
    }

    public function sales()
    {
        return $this->hasMany(SalesList::class, 'product_id')->with('sales');
    }

    public function liftings()
    {
        return $this->hasMany(LiftingProduct::class, 'product_id')->with('lifting');
    }

    public function sku()
    {
        return $this->hasMany(ProductSku::class, 'product_id');
    }
}
