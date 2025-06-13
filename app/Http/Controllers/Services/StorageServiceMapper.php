<?php

namespace App\Http\Controllers\Services;


class StorageServiceMapper
{
    public function getService(string $serviceName): FileStorageInterface
    {
        switch ($serviceName) {
            case 'local':
                return new LocalStorageService();
            case 'gcs':
                //Obtenemos el valor de la variable de entorno del nombre del bucket.
                $bucketName = env('GOOGLE_CLOUD_STORAGE_BUCKET', 'default_bucket');
                return new GoogleCloudStorageService($bucketName);
            // Agregar otros casos para AWS S3, etc.
            default:
                return new LocalStorageService(); // Servicio por defecto
        }
    }
}