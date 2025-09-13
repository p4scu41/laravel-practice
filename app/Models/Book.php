<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Book extends Model
{
    /** @use HasFactory<\Database\Factories\BookFactory> */
    use HasFactory;

    protected $fillable = ['category_id', 'name', 'published_at'];

    function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    #[Scope]
    function filterByCategory(Builder $query, int $category_id)
    {
        $query->where('category_id', $category_id);
    }

    #[Scope]
    function filterByName(Builder $query, string $name)
    {
        $query->whereLike('name', "%$name%");
    }
}
