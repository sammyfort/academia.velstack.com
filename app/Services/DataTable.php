<?php

namespace App\Services;

use BadMethodCallException;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Schema;

class DataTable
{
    protected Builder $query;
    protected array $relations = [];
    protected ?string $searchTerm = null;
    protected Model|Relation $model;

    public function __construct(Model|Relation $model)
    {
        if ($model instanceof Relation) {
            $this->query = $model->getQuery();
        } else {
            $this->query = $model->query();
        }
        $this->model = $model;
    }

    public function searchable(?string $searchTerm): static
    {
        $this->searchTerm = $searchTerm;
        return $this;
    }


    public function with(array $relations): static
    {
        $this->relations = $relations;
        return $this;
    }


    public function query(callable $callback): static
    {
        $callback($this->query);
        return $this;
    }


    public function __call(string $method, array $arguments): static
    {
        if (method_exists($this->query, $method)) {
            $this->query = $this->query->{$method}(...$arguments);
        } else {
            throw new BadMethodCallException("Method $method does not exist on the query builder.");
        }
        return $this;
    }


    public function paginate(int $perPage = 10):  LengthAwarePaginator
    {
        $this->applySearchFilter();

        if (!empty($this->relations)) {
            $this->query->with($this->relations);
        }

        return $this->query->paginate($perPage);
    }


    protected function applySearchFilter(): void
    {
        if ($this->searchTerm) {
            $this->query->where(function ($query) {
                $fieldsToSearch = $this->getSearchableFields();
                foreach ($fieldsToSearch as $field) {
                    $query->orWhere($field, 'like', '%' . $this->searchTerm . '%');
                }
            });
        }
    }


    protected function getSearchableFields(): array
    {
        $fields = Schema::getColumnListing($this->model->getTable());
        return array_filter($fields, function ($field) {
            return !in_array($field, ['password', 'remember_token']);
        });
    }


    public function getQuery(): Builder
    {
        $this->applySearchFilter();
        if (!empty($this->relations)) {
            $this->query->with($this->relations);
        }
        return $this->query;
    }
}
