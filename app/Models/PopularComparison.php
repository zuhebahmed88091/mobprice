<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PopularComparison extends Model
{

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'popular_comparisons';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'mobile1_id',
                  'mobile2_id',
                  'view_count'
              ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];

    /**
     * Get the mobile for this model.
     */
    public function mobile1()
    {
        return $this->belongsTo('App\Models\Mobile','mobile1_id');
    }

    public function mobile2()
    {
        return $this->belongsTo('App\Models\Mobile','mobile2_id');
    }

    /**
     * Get created_at in array format
     *
     * @param  string  $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return date('j/n/Y g:i A', strtotime($value));
    }

    /**
     * Get updated_at in array format
     *
     * @param  string  $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    {
        return date('j/n/Y g:i A', strtotime($value));
    }

}
