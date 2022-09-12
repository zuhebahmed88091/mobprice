<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MediaMap extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'media_maps';

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
        'media_id',
        'item_id',
        'item_field'
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
     * Get the fileType for this model.
     */
    public function media()
    {
        return $this->belongsTo('App\Models\Media', 'media_id');
    }

    /**
     * Get created_at in array format
     *
     * @param string $value
     * @return array
     */
    public function getCreatedAtAttribute($value)
    {
        return date(config('settings.DISPLAY_DATE_FORMAT') . ' g:i A', strtotime($value));
    }

    /**
     * Get updated_at in array format
     *
     * @param string $value
     * @return array
     */
    public function getUpdatedAtAttribute($value)
    {
        return date(config('settings.DISPLAY_DATE_FORMAT') . ' g:i A', strtotime($value));
    }
}
