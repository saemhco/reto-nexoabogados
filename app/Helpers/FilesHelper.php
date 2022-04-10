<?php

namespace App\Helpers;

use App\Models\CategoryFile;
use App\Models\File;
use Illuminate\Support\Facades\Storage;

class FilesHelper
{
    static function save($file, $folder, $name = false)
    {
        if (!$file) return false;
        if ($name) {
            $name = $file->getClientOriginalName();
            $file->storeAs('public/' . $folder, $name);
            $file_path = $folder . '/' . $name;
        } else {
            $file_path =    $file->store('public/' . $folder);
        }
        if (!$category = CategoryFile::where('name', $folder)->first()) {
            $category = CategoryFile::create([
                'name' => $folder, 'folder' => $folder
            ]);
        }

        $projectFile = File::create([
            'category_file_id' => $category->id,
            'path' => $file_path,
        ]);
        return $projectFile->id;
    }
}
