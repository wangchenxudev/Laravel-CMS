<?php

namespace App\Enums\Article;

enum ArticleReviewActionType: string
{
    case Approve = 'approve';
    case Reject = 'reject';
    case TakeDown = 'take_down';
}
