<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['user_id', 'plan_id', 'frecuency', 'start_date', 'end_date', 'end_at', 'renewal', 'renewal_cancel_at', 'status', 'failed_subscription_attempts'];
    protected $hidden = ['created_at', 'updated_at', 'deleted_at', 'deleted_by', 'created_by', 'updated_by'];

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_by  = auth()->user() ? auth()->user()->id : 1;
            $model->updated_by  = auth()->user() ? auth()->user()->id : 1;
            // $model->user_id     = auth()->user();
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

    public function scopeWithData()
    {
        if (request()->has('with')) {
            $with = explode('|', request()->input('with'));
            return $this->load($with);
        }
        return $this->load(
            'plan',
            'user'

        );
    }
    public function scopeWithDataAll($query)
    {
        if (request()->has('with')) {
            $with = explode('|', request()->input('with'));
            return $query->with($with);
        }
        return $query->with(
            'plan',
            'user'
        );
    }
    public function scopeFilters($query)
    {
        if (request()->has('filters')) {
            $filters = explode('|', request()->input('filters'));
            foreach ($filters as $filter) {
                $filter = explode('=', $filter);
                if ($filter[1] == '!null')
                    $query->whereNotNull($filter[0]);
                else if ($filter[1] == 'null')
                    $query->whereNull($filter[0]);
                else
                    $query->where($filter[0], 'like', '%' . $filter[1] . '%');
            }
        }
        return $query;
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }
}
