<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FilterSection extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'filter_sections';

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
                  'filter_tab_id',
                  'label',
                  'field',
                  'type',
                  'show_label',
                  'sorting',
                  'status'
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
     * Get the filterTab for this model.
     */
    public function filterTab()
    {
        return $this->belongsTo('App\Models\FilterTab','filter_tab_id');
    }

    public function filterOptions()
    {
        return $this->hasMany('App\Models\FilterOption', 'filter_section_id')
            ->where('status', 'Active')
            ->orderBy('sorting', 'ASC');
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
