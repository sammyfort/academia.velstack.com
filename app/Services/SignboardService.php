<?php

namespace App\Services;

use App\Models\Signboard;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class SignboardService
{
    public static function getSignboards(): LengthAwarePaginator
    {
        $signboardsQuery = QueryBuilder::for(Signboard::class)
            ->allowedFilters([
                AllowedFilter::callback('q', function (Builder $query, $input) {
                    if (is_array($input)){
                        $input = implode(',', $input);
                    }
                    $query->where("name", "LIKE", "%$input%")
                        ->orWhere("town", "LIKE", "%$input%")
                        ->orWhere("street", "LIKE", "%$input%")
                        ->orWhere("landmark", "LIKE", "%$input%")
                        ->orWhere("blk_number", "LIKE", "%$input%")
                        ->orWhere("gps", "LIKE", "%$input%")
                        ->orWhereRelation('service', 'title', "LIKE", "%$input%");
                }),
                AllowedFilter::callback('categories', function ($query, $value) {
                    $ids = is_array($value) ? $value : explode(',', $value);
                    $query->whereHas('categories', function ($q) use ($ids) {
                        $q->whereIn('signboard_categories.id', $ids);
                    });
                }),
                AllowedFilter::exact('country', 'region.country_id'),
                AllowedFilter::exact('region', 'region_id'),
            ])
            ->with('categories', function ($categoriesQuery) {
                $categoriesQuery->take(3);
            })
            ->with('reviews', function ($reviewsQuery) {
                $reviewsQuery->where('user_id', auth()->id())
                    ->with(['ratings']);
            })
            ->with(['service', 'region.country'])
            ->when(auth()->user(), function ($q){
                $q->where('created_by_id', '!=', auth()->id());
            });

        $signboards = CountryBuilderService::applyRegionFilterAuth($signboardsQuery);
        $signboards->map(function (Signboard $signboard) {
            $signboard->featured_url = $signboard->getFirstMediaUrl('featured');
            return $signboard;
        });

        return $signboards;
    }

    public static function getPromoted(): Collection
    {
        $signboardsQuery = Signboard::getRandomPromotedQuery()
            ->with(['service', 'region.country'])
            ->with('categories', function ($categoriesQuery) {
                $categoriesQuery->take(3);
            })
            ->with('reviews', function ($reviewsQuery) {
                $reviewsQuery->where('user_id', auth()->id())
                    ->with(['ratings']);
            });
        $signboards = CountryBuilderService::applyRegionFilterAuthOnPromoted($signboardsQuery);

        if ($signboards->count() < 1) {
            $signboardsQuery = Signboard::query()
                ->with(['service', 'region.country'])
                ->with('categories', function ($categoriesQuery) {
                    $categoriesQuery->take(3);
                })
                ->with('reviews', function ($reviewsQuery) {
                    $reviewsQuery->where('user_id', auth()->id())
                        ->with(['ratings']);
                })
                ->when(auth()->user(), function ($q){
                    $q->where('created_by_id', '!=', auth()->id());
                });
            $signboards = CountryBuilderService::applyRegionFilterAuthOnPromoted($signboardsQuery);
        }

        $signboards->map(function (Signboard $signboard) {
            $signboard->featured_url = $signboard->getFirstMediaUrl('featured');
        });

        return $signboards;
    }
}
