<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait UploadTrait
{
    public function uploadFile(
        UploadedFile $file,
        string       $directory = 'uploads',
        string       $disk      = 'public'
    ): string {
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();
        return $file->storeAs($directory, $filename, $disk);
    }

    public function replaceFile(
        UploadedFile $newFile,
        ?string      $oldPath,
        string       $directory = 'uploads',
        string       $disk      = 'public'
    ): string {
        // Delete old file if exists
        if ($oldPath && Storage::disk($disk)->exists($oldPath)) {
            Storage::disk($disk)->delete($oldPath);
        }

        return $this->uploadFile($newFile, $directory, $disk);
    }

    public function deleteFile(
        ?string $path,
        string  $disk = 'public'
    ): bool {
        if (!$path) return false;

        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }

        return false;
    }
}
