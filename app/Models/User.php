<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles, HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'password',
        'email_verified_at',
        'active',
        //Others fields
        'document_number',
        'document_type',
        'avatar_id',
        'address',
        'birth_date',
        'nationality',
        'other_nationality',
        'civil_status',
        'sex'
    ];


    protected $hidden = [
        'created_at', 'updated_at', 'deleted_at', 'created_by', 'updated_by', 'deleted_by', 'avatar_id', 'file', 'password'
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
    public function delete(): void
    {
        $this->deleted_at = now();
        $this->deleted_by = auth()->user()->id;
        $this->save();
    }
    public function avatar()
    {
        return $this->belongsTo(File::class, 'avatar_id', 'id');
    }

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    public function scopeWithData()
    {
        if (request()->has('with')) {
            $with = explode('|', request()->input('with'));
            return $this->load($with);
        }
        return $this->load(
            'avatar',

        );
    }
    public function scopeWithDataAll($query)
    {
        if (request()->has('with')) {
            $with = explode('|', request()->input('with'));
            return $query->with($with);
        }
        return $query->with(
            'avatar',
        );
    }
    public function scopeFilters($query)
    {
        if (request()->has('filters')) {
            $filters = explode('|', request()->input('filters'));
            foreach ($filters as $filter) {
                $filter = explode('=', $filter);
                $query->where($filter[0], 'like', '%' . $filter[1] . '%');
            }
        }
        return $query;
    }
}
