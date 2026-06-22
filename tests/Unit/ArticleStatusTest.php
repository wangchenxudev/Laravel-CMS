<?php

use App\Enums\Article\ArticleStatus;

test('editable cases only include draft, withdrawn, and rejected', function () {
    expect(ArticleStatus::editableCases())->toBe([
        ArticleStatus::Draft,
        ArticleStatus::Withdrawn,
        ArticleStatus::Rejected,
    ]);
});

test('isEditable reflects the editable cases', function (ArticleStatus $status, bool $expected) {
    expect($status->isEditable())->toBe($expected);
})->with([
    'draft' => [ArticleStatus::Draft, true],
    'withdrawn' => [ArticleStatus::Withdrawn, true],
    'rejected' => [ArticleStatus::Rejected, true],
    'pending review' => [ArticleStatus::PendingReview, false],
    'published' => [ArticleStatus::Published, false],
    'taken down' => [ArticleStatus::TakenDown, false],
]);

test('every status has a human readable label and badge classes', function (ArticleStatus $status) {
    expect($status->label())->toBeString()->not->toBe('')
        ->and($status->badgeClasses())->toBeString()->not->toBe('');
})->with(ArticleStatus::cases());
