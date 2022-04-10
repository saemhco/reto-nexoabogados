<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryFile extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'folder',
        'user_id',
        'user_update_id',
    ];

    public function files()
    {
        return $this->hasMany(File::class);
    }
}
