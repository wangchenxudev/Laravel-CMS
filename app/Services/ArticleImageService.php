<?php

namespace App\Services;

use App\Models\Article;
use App\Models\ArticleImage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class ArticleImageService
{
    /**
     * Persist newly uploaded images for an article, appending after any existing ones.
     *
     * @param  array<int, UploadedFile>  $files
     */
    public function store(Article $article, array $files): void
    {
        if ($files === []) {
            return;
        }

        $disk = $this->disk();
        $position = (int) $article->images()->max('position');

        foreach ($files as $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }

            $position++;

            $path = $file->store("article-images/{$article->id}", $disk);

            $article->images()->create([
                'disk' => $disk,
                'path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'position' => $position,
            ]);
        }
    }

    /**
     * Remove the selected images, then append any newly uploaded ones.
     *
     * @param  array<int, UploadedFile>  $newFiles
     * @param  array<int, int>  $removeIds
     */
    public function sync(Article $article, array $newFiles, array $removeIds): void
    {
        if ($removeIds !== []) {
            $images = $article->images()->whereIn('id', $removeIds)->get();

            foreach ($images as $image) {
                $this->deleteImage($image);
            }
        }

        $this->store($article->refresh(), $newFiles);
    }

    private function deleteImage(ArticleImage $image): void
    {
        Storage::disk($image->disk)->delete($image->path);

        $image->delete();
    }

    private function disk(): string
    {
        return (string) config('articles.image_disk', 'public');
    }
}
