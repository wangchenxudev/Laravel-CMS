<?php

namespace App\Models;

use App\Enums\ArticleReviewActionType;
use App\Enums\ArticleStatus;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'article_id',
    'admin_id',
    'action',
    'from_status',
    'to_status',
    'reason',
    'is_open',
    'open_slot',
])]
class ArticleReviewAction extends Model
{
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'action' => ArticleReviewActionType::class,
            'from_status' => ArticleStatus::class,
            'to_status' => ArticleStatus::class,
            'is_open' => 'boolean',
        ];
    }

    public function article(): BelongsTo
    {
        return $this->belongsTo(Article::class);
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
