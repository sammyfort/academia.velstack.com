<?php

namespace App\Models;

use App\Observers\SignboardObserver;
use App\Traits\BootModelTrait;
use App\Traits\HasMediaUploads;
use App\Traits\HasPromotion;
use App\Traits\RatingsAttributesTrait;
use Codebyray\ReviewRateable\Models\Review;
use Codebyray\ReviewRateable\Traits\ReviewRateable;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Tags\HasTags;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @property string $id
 * @property string $uuid
 * @property int $created_by_id
 * @property string $created_at
 * @property string $updated_at
 * @property Collection<Review> $reviews
 * @property int|null $views_count
 * @property string|null $featured_url
 * @property string $gps
 * @property string $gps_lat
 * @property string $gps_lon
 * @property int $service_id
 * @property Service $service
 */

#[ObservedBy(SignboardObserver::class)]
class Signboard extends Model implements HasMedia, Viewable
{
    use BootModelTrait, HasTags, HasFactory, ReviewRateable,
        InteractsWithMedia, InteractsWithViews, HasMediaUploads,
        HasSlug, HasPromotion, RatingsAttributesTrait;


    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')
            ->width(200)
            ->height(200)
            ->sharpen(10);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('featured')
            ->singleFile()
            ->useFallbackUrl(asset('images/logo-blur.png'));
        $this->addMediaCollection('gallery');
    }

    protected $appends = [
        "total_average_rating",
        "reviews_count",
        "created_at_str",
        "active_promotion"
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
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

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            SignboardCategory::class,
            'signboard_signboard_categories',
            'signboard_id',
            'category_id'
        );
    }
}
