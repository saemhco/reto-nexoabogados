<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'monthly_price', 'annual_price'];
    protected $hidden = ['created_at', 'updated_at'];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function scopeWithData()
    {
        if (request()->has('with')) {
            $with = explode('|', request()->input('with'));
            return $this->load($with);
        }
        return $this->load(
            'subscriptions',

        );
    }
    public function scopeWithDataAll($query)
    {
        if (request()->has('with')) {
            $with = explode('|', request()->input('with'));
            return $query->with($with);
        }
        return $query->with(
            'subscriptions',
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
