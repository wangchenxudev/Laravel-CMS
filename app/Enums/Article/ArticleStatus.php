<?php

namespace App\Enums\Article;

enum ArticleStatus: string
{
    case Draft = 'draft';
    case PendingReview = 'pending_review';
    case Withdrawn = 'withdrawn';
    case Rejected = 'rejected';
    case Published = 'published';
    case TakenDown = 'taken_down';

    /**
     * Statuses an author may edit, submit, or delete.
     *
     * @return array<int, self>
     */
    public static function editableCases(): array
    {
        return [
            self::Draft,
            self::Withdrawn,
            self::Rejected,
        ];
    }

    public function isEditable(): bool
    {
        return in_array($this, self::editableCases(), true);
    }

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::PendingReview => 'Pending',
            self::Withdrawn => 'Withdrawn',
            self::Rejected => 'Rejected',
            self::Published => 'Published',
            self::TakenDown => 'Taken Down',
        };
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Draft => 'bg-slate-100 text-slate-700 border-slate-200',
            self::PendingReview => 'bg-amber-50 text-amber-800 border-amber-200',
            self::Withdrawn => 'bg-orange-50 text-orange-800 border-orange-200',
            self::Rejected => 'bg-rose-50 text-rose-800 border-rose-200',
            self::Published => 'bg-emerald-50 text-emerald-800 border-emerald-200',
            self::TakenDown => 'bg-slate-200 text-slate-800 border-slate-300',
        };
    }
}
