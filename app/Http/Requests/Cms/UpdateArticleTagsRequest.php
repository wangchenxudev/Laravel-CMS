<?php

namespace App\Http\Requests\Cms;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArticleTagsRequest extends FormRequest
{
    public function authorize(): bool
    {
        $article = $this->route('article');

        return $article instanceof Article
            && ($this->user()?->can('updateTags', $article) ?? false);
    }

    /**
     * @return array<string, list<mixed>>
     */
    public function rules(): array
    {
        return [
            'tags' => ['nullable', 'array', 'max:'.config('articles.max_tags')],
            'tags.*' => ['integer', Rule::exists('tags', 'id')],
        ];
    }
}
