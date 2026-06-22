<?php

namespace App\Http\Requests\Cms;

use App\Models\Article;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        $article = $this->route('article');

        return $article instanceof Article
            && ($this->user()?->can('update', $article) ?? false);
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
            'remove_images' => ['nullable', 'array'],
            'remove_images.*' => ['integer'],
            'tags' => ['nullable', 'array', 'max:'.config('articles.max_tags')],
            'tags.*' => ['integer', Rule::exists('tags', 'id')],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            $article = $this->route('article');

            if (! $article instanceof Article) {
                return;
            }

            $removeIds = array_map('intval', (array) $this->input('remove_images', []));
            $existingIds = $article->images()->pluck('id')->all();

            $invalidIds = array_diff($removeIds, $existingIds);

            if ($invalidIds !== []) {
                $validator->errors()->add('remove_images', 'One or more selected images do not belong to this article.');

                return;
            }

            $remaining = count($existingIds) - count(array_intersect($removeIds, $existingIds));
            $added = count((array) $this->file('images', []));
            $maxImages = (int) config('articles.max_images');

            if ($remaining + $added > $maxImages) {
                $validator->errors()->add('images', "An article may have at most {$maxImages} images.");
            }
        });
    }
}
