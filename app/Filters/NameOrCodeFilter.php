<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class NameOrCodeFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $query->where(function ($q) use ($value) {
            $q->where('name', 'like', '%'.$value.'%')
                ->orWhere('code', 'like', '%'.$value.'%');
        });
    }
}
