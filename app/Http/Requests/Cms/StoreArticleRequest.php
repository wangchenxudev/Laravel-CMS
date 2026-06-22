<?php

namespace App\Http\Requests\Cms;

use App\Models\Article;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('create', Article::class) ?? false;
    }

    /**
     * @return array<string, list<string>>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'summary' => ['nullable', 'string'],
            'content' => ['required', 'string'],
            'images' => ['nullable', 'array', 'max:'.config('articles.max_images')],
            'images.*' => ['image', 'mimes:'.implode(',', config('articles.image_mimes')), 'max:'.config('articles.max_image_kb')],
            'tags' => ['nullable', 'array', 'max:'.config('articles.max_tags')],
            'tags.*' => ['integer', Rule::exists('tags', 'id')],
        ];
    }
}
