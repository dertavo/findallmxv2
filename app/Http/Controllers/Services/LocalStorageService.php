<?php

namespace App\Http\Controllers\Services;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class LocalStorageService implements FileStorageInterface
{
    public function store(UploadedFile $file, string $path, string $fileName): string
    {
        $file->store('public/entidades');
        $name = $file->hashName();
        return $name;
    }

    public function storeDisk($nombreArchivo, $imagenDecodificada){
        Storage::disk('public')->put('pruebasContacto/' . $nombreArchivo, $imagenDecodificada);
    }

    public function storeAs(UploadedFile $file, string $path, string $fileName): string
    {

        $file->storeAs(
            'public', "pruebasContacto\\".$fileName,"local"
        );   
        $name = $file->hashName();
        return $name;
    }

    public function delete_file(string $filename): string{
      //local method.
                 $file_path = public_path('storage/entidades/'.$filename);

        
          
              
                $del="";
                if (file_exists($file_path)) { // Verificar si el archivo existe
                if (unlink($file_path)) { // Eliminar el archivo y verificar si la eliminación fue exitosa
                    return "ok";
                }
            }
            return "404";
    }

    public function getUrl(string $path): string
    {
        return Storage::url($path);
    }
}