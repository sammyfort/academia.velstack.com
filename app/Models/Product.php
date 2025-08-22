<?php

namespace App\Models;

use App\Traits\BootModelTrait;
use App\Traits\HasMediaUploads;
use App\Traits\HasPromotion;
use App\Traits\RatingsAttributesTrait;
use Codebyray\ReviewRateable\Models\Review;
use Codebyray\ReviewRateable\Traits\ReviewRateable;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property string $id
 * @property string $uuid
 * @property int $created_by_id
 * @property string $created_at
 * @property string $updated_at
 * @property int|null $views_count
 * @property int $user_id
 * @property Collection<Review> $reviews
 */
class Product extends Model implements HasMedia, Viewable
{
    use BootModelTrait, HasFactory, InteractsWithMedia, ReviewRateable,
        InteractsWithViews, HasSlug, HasMediaUploads, HasPromotion, RatingsAttributesTrait;

    protected $appends = [
        'featured',
        'gallery',
        "active_promotion",
        "total_average_rating",
        "reviews_count",
    ];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured')
            ->singleFile()
            ->useFallbackUrl(asset('images/logo-blur.png'));
        $this->addMediaCollection('gallery');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            ProductCategory::class,
            'product_product_category',
            'product_id',
            'category_id'
        );
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }



    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function videoLink(): Attribute
    {
        return Attribute::get(function ($videoLink){
            if ($this->active_promotion) return $videoLink;
            return null;
        });
    }
}
