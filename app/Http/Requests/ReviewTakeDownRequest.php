<?php

namespace App\Http\Requests;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;

class ReviewTakeDownRequest extends FormRequest
{
    public function authorize(): bool
    {
        $article = $this->route('article');

        return $article instanceof Article
            && ($this->user()?->can('takeDown', $article) ?? false);
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'reason' => ['nullable', 'string', 'max:5000'],
        ];
    }
}
