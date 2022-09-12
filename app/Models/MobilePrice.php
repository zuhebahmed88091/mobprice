<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MobilePrice extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mobile_prices';

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
                  'mobile_id',
                  'region_id',
                  'variation',
                  'price',
                  'usd_price',
                  'store',
                  'source',
                  'status',
                  'affiliate_url'
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
    public function mobile()
    {
        return $this->belongsTo('App\Models\Mobile','mobile_id');
    }

    /**
     * Get the region for this model.
     */
    public function region()
    {
        return $this->belongsTo('App\Models\MobileRegion','region_id');
    }

    /**
     * Get the mobileRam for this model.
     */
    public function mobileRam()
    {
        return $this->belongsTo('App\Models\MobileRam','mobile_ram_id');
    }

    /**
     * Get the mobileStorage for this model.
     */
    public function mobileStorage()
    {
        return $this->belongsTo('App\Models\MobileStorage','mobile_storage_id');
    }


    public function affiliate()
    {
        return $this->belongsTo('App\Models\Affiliate','affiliate_id');
    }

    /**
     * Get created_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getCreatedAtAttribute($value)
    {
        return date('j/n/Y g:i A', strtotime($value));
    }

    /**
     * Get updated_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getUpdatedAtAttribute($value)
    {
        return date('j/n/Y g:i A', strtotime($value));
    }

}
