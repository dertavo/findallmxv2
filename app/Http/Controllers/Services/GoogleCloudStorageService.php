<?php 
namespace App\Http\Controllers\Services;

use Google\Cloud\Storage\StorageClient;
use Illuminate\Http\UploadedFile;

class GoogleCloudStorageService implements FileStorageInterface
{
    protected $bucketName;
    protected $storageClient;

    public function __construct(string $bucketName)
    {
        $this->bucketName = $bucketName;
        $this->storageClient = new StorageClient();
    }

    public function store(UploadedFile $file, string $path, string $fileName): string
    {
        $bucket = $this->storageClient->bucket($this->bucketName);
        $object = $bucket->upload(file_get_contents($file), ['name' => $fileName]);
        return $fileName;
    }

    public function delete_file($filename){
        $bucket = $this->storageClient->bucket('findall_bucket');
        $object = $bucket->object($filename);
        $object->delete();

        return "ok";
    }

    public function getUrl(string $path): string
    {
        // Implementa la lógica para obtener la URL desde GCS
        return "URL_DE_GCS/" . $path;
    }
}