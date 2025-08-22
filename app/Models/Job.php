<?php

namespace App\Models;

use App\Enums\JobStatus;
use App\Traits\BootModelTrait;
use App\Traits\HasMediaUploads;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Traits\HasPromotion;
use CyrildeWit\EloquentViewable\Contracts\Viewable;
use CyrildeWit\EloquentViewable\InteractsWithViews;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;


/**
 * @property string $id
 * @property string $uuid
* @property int $created_by_id
 * @property int $views_count
* @property string $created_at
* @property string $updated_at
 * @property string $company_logo
 * @property Collection<JobCategory> $categories
 */

class Job extends Model implements  HasMedia, Viewable
{
    use BootModelTrait, HasFactory, InteractsWithMedia,
        InteractsWithViews, HasSlug, HasMediaUploads, HasPromotion;

    protected $table = 'user_jobs';

    protected $appends = [
        'company_logo'
    ];

    protected $casts = [
        'status' => JobStatus::class,
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('company_logo')
            ->singleFile()
            ->useFallbackUrl(asset('images/logo-blur.png'));
    }

    public function user(): BelongsTo
    {
        return  $this->belongsTo(User::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            JobCategory::class,
            'job_job_category',
            'job_id',
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

    public function companyLogo(): Attribute
    {
        return Attribute::get(fn() => $this->getFirstMediaUrl('company_logo'));
    }

    public function createdAt(): Attribute
    {
        return Attribute::get(fn($created_at) => Carbon::parse($created_at)->diffForHumans());
    }
}
