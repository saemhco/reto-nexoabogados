<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_file_id',
        'path',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'created_at', 'deleted_at', 'updated_at', 'user_id', 'user_update_id', 'pivot', 'created_by', 'updated_by', 'deleted_by'
    ];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by = auth()->user() ? auth()->user()->id : 1;
            $model->updated_by = auth()->user() ? auth()->user()->id : 1;
        });
        static::updating(function ($model) {

            $model->updated_by = auth()->user() ? auth()->user()->id : 1;
        });
    }

    public function categoryFile()
    {
        return $this->belongsTo(CategoryFile::class);
    }
    protected function path(): Attribute
    {
        return new Attribute(
            get: fn ($value) => asset(Storage::url($value)),
        );
    }
}
