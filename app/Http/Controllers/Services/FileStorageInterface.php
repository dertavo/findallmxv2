<?php
namespace App\Http\Controllers\Services;

use Illuminate\Http\UploadedFile;

interface FileStorageInterface
{
    public function store(UploadedFile $file, string $path, string $fileName): string;

    public function storeAs(UploadedFile $file, string $path, string $fileName): string;

    public function delete_file(string $filename):string;
    

    public function getUrl(string $path): string;
}