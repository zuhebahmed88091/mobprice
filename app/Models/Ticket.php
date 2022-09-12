<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'tickets';

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
        'subject',
        'created_by',
        'department_id',
        'product_id',
        'priority',
        'status',
        'agent_action',
        'assign_to',
        'message',
        'customer_action'
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
     * Get the creator for this model.
     */
    public function creator()
    {
        return $this->belongsTo('App\Models\User', 'created_by');
    }

    /**
     * Get the department for this model.
     */
    public function department()
    {
        return $this->belongsTo('App\Models\Department', 'department_id');
    }

    /**
     * Get the product for this model.
     */
    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }

    /**
     * Get the assignTo for this model.
     */
    public function assignTo()
    {
        return $this->belongsTo('App\Models\User', 'assign_to');
    }

    /**
     * Get the assignTo for this model.
     */
    public function comments()
    {
        return $this->hasMany('App\Models\Comment', 'ticket_id');
    }

    /**
     * Get average first time response in minutes
     *
     * @param string $startDate
     * @param string $endDate
     * @return float
     */
    public static function getAvgFirstTimeResponse($startDate, $endDate)
    {
        $sql = "SELECT AVG(TIMESTAMPDIFF(MINUTE, created_at, first_comment_time)) AS avg_time FROM (
                SELECT 
                    t.id, 
                    t.created_at, 
                    c.first_comment_time
                FROM
                    tickets t
                LEFT JOIN (
                    SELECT ticket_id, MIN(created_at) AS first_comment_time FROM comments GROUP BY ticket_id
                ) c
                ON
                    t.id = c.ticket_id
                WHERE 
                    c.first_comment_time IS NOT NULL
                    AND DATE(t.created_at) BETWEEN :start_date AND :end_date
            ) ctable";
        $result = DB::select(DB::raw($sql), [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        if (!empty($result)) {
            return $result[0]->avg_time;
        }

        return 0;
    }

    /**
     * Get average first time response in minutes
     *
     * @param string $startDate
     * @param string $endDate
     * @return float
     */
    public static function getAvgCloseTime($startDate, $endDate)
    {
        $sql = "SELECT 
                    AVG(TIMESTAMPDIFF(MINUTE, created_at, updated_at)) AS avg_time
                FROM
                    tickets
                WHERE 
                    status = 'Closed'
                    AND DATE(created_at) BETWEEN :start_date AND :end_date";
        $result = DB::select(DB::raw($sql), [
            'start_date' => $startDate,
            'end_date' => $endDate,
        ]);

        if (!empty($result)) {
            return $result[0]->avg_time;
        }

        return 0;
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
