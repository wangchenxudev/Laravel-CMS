<?php

namespace App\Models;

use App\Enums\ArticleStatus;
use Database\Factories\ArticleFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'author_id',
    'title',
    'slug',
    'summary',
    'content',
    'status',
    'approved_by',
    'approved_at',
    'rejected_by',
    'rejected_at',
    'reject_reason',
    'withdrawn_at',
    'taken_down_at',
])]
class Article extends Model
{
    /** @use HasFactory<ArticleFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => ArticleStatus::class,
            'approved_at' => 'datetime',
            'rejected_at' => 'datetime',
            'withdrawn_at' => 'datetime',
            'taken_down_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function rejecter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'rejected_by');
    }

    public function reviewActions(): HasMany
    {
        return $this->hasMany(ArticleReviewAction::class);
    }

    /**
     * @return array{article: self, slug: string}
     */
    public function publicRouteParameters(): array
    {
        return [
            'article' => $this,
            'slug' => $this->slug,
        ];
    }
}
