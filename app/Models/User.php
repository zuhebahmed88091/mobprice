<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
    use Notifiable;
    use EntrustUserTrait; // add this trait to your user model

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'gender',
        'email',
        'password',
        'api_token',
        'country_id',
        'department_id',
        'phone',
        'address',
        'state',
        'city',
        'zip',
        'status',
        'remember_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the UploadedFile record associated with the user.
     */
    public function uploadedFile()
    {
        return $this->hasOne('App\Models\UploadedFile');
    }

    /**
     * Get the country for this model.
     */
    public function country()
    {
        return $this->belongsTo('App\Models\Country', 'country_id');
    }


    public function identities()
    {
        return $this->hasMany('App\Models\SocialIdentity');
    }

    public static function boot()
    {
        parent::boot();
        static::deleting(function ($user) {
            // remove related rows with uploaded files
            if (!empty($user->uploadedFile)) {
                Storage::delete('profiles/' . $user->uploadedFile->filename);
                $user->uploadedFile()->delete();
            }
            return true;
        });
    }

    /**
     * Get created_at in array format
     *
     * @param string $value
     * @return false|string
     */
    public function getCreatedAtAttribute($value)
    {
        return date(config('settings.DISPLAY_DATE_FORMAT') . ' g:i A', strtotime($value));
    }

    /**
     * Get updated_at in array format
     *
     * @param string $value
     * @return false|string
     */
    public function getUpdatedAtAttribute($value)
    {
        return date(config('settings.DISPLAY_DATE_FORMAT') . ' g:i A', strtotime($value));
    }


    // get user store
     public function store()
    {
        return $this->hasMany(Store::class);
    }
    // get user orders
     public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
