<?php

namespace App\Models;

use App\Observers\ServiceObserver;
use App\Traits\BootModelTrait;
use App\Traits\HasMediaUploads;
use App\Traits\HasPromotion;
use App\Traits\RatingsAttributesTrait;
use Codebyray\ReviewRateable\Models\Review;
use Codebyray\ReviewRateable\Traits\ReviewRateable;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
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
use Spatie\Tags\HasTags;

/**
 * @property string $id
 * @property string $uuid
 * @property int $user_id
 * @property int $created_by_id
 * @property string $created_at
 * @property string $updated_at
 * @property string $title
 * @property string $description
 * @property string $first_mobile
 * @property string $second_mobile
 * @property string $email
 * @property string $address
 * @property string $region_id
 * @property string $town
 * @property int|null $views_count
 * @property Collection<ServiceCategory> $categories
 * @property \Spatie\MediaLibrary\MediaCollections\Models\Media|null $featured
 * @property string $gps
 * @property string $gps_lat
 * @property string $gps_lon
 * @property Collection<Review> $reviews
 */
#[ObservedBy(ServiceObserver::class)]
class Service extends Model implements HasMedia, Viewable
{
    use BootModelTrait, HasFactory, InteractsWithMedia, ReviewRateable,
        InteractsWithViews, HasSlug, HasMediaUploads, HasPromotion, RatingsAttributesTrait;

    protected $appends = [
        'featured',
        'gallery',
        'active_promotion',
        "total_average_rating",
        "reviews_count",
    ];


    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured')
            ->singleFile()
            ->useFallbackUrl(asset('images/logo-blur.png'));
        $this->addMediaCollection('gallery');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }
    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function videoLink(): Attribute
    {
        return Attribute::get(function ($videoLink){
            if ($this->active_promotion) return $videoLink;
            return null;
        });
    }
}
