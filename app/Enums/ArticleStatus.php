<?php

namespace App\Enums;

enum ArticleStatus: string
{
    case Draft = 'draft';
    case PendingReview = 'pending_review';
    case Withdrawn = 'withdrawn';
    case Rejected = 'rejected';
    case Published = 'published';
    case TakenDown = 'taken_down';
}
