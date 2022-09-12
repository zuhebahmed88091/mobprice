<?php

namespace App\Models;

use App\Helpers\ScrapHelper;
use Illuminate\Database\Eloquent\Model;
use Session;

/**
 * @property mixed $mobilePrices
 * @property mixed $mobileImages
 * @property mixed $cameras
 * @property mixed $price_ranges
 */
class Mobile extends Model
{
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'mobiles';

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
        'title', 'technology', 'announced', 'status', 'dimensions', 'weight', 'build',
        'sim', 'display_type', 'size', 'resolution', 'protection', 'multitouch', 'os', 'chipset',
        'cpu', 'gpu', 'card_slot', 'internal', 'mc_numbers', 'mc_resolutions', 'mc_features',
        'mc_video', 'sc_numbers', 'sc_resolutions', 'sc_features', 'sc_video', 'loudspeaker',
        'jack_3_5mm', 'wlan', 'bluetooth', 'gps', 'nfc', 'infrared_port', 'radio', 'usb',
        'sensors', 'battery_type', 'charging', 'colors', 'price', 'price_url', 'gm_weight',
        'hz_refresh_rate', 'inch_size', 'px_density', 'px_resolution', 'mhz_processor_speed',
        'mah_battery', 'analyzed', 'full_spec', 'sorting', 'revision', 'brand_id', 'detail_url',
        'origin_id', 'price_updated', 'official_link', 'view_count', 'expert_score', 'avg_rating',
        'usd_min_price', 'completed','published'
    ];

    /**
     * Define hidden attributes.
     *
     * @var array
     */
    protected $hidden = ['mobileImages', 'cameras', 'mobilePrices'];

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
     * Define additional attributes.
     *
     * @var array
     */
    protected $appends = ['availability', 'px_density', 'price_ranges', 'featured_image',
        'rear_camera', 'front_camera', 'album', 'regional_prices'
    ];

    public function Brand()
    {
        return $this->belongsTo('App\Models\Brand', 'brand_id', 'id');
    }

    public function mobileStorage()
    {
        return $this->hasMany('App\Models\MobileStorage', 'mobile_id');
    }

    public function rams()
    {
        return $this->hasMany('App\Models\MobileRam', 'mobile_id');
    }

    public function prices()
    {
        return $this->hasMany('App\Models\MobilePrice', 'mobile_id');
    }

    public function cameras()
    {
        return $this->hasMany('App\Models\MobileCamera', 'mobile_id');
    }

    public function ratings()
    {
        return $this->hasMany('App\Models\Rating', 'mobile_id');
    }

    public function mobileImages()
    {
        return $this->hasMany('App\Models\MobileImage', 'mobile_id');
    }

    public function mobilePrices()
    {
        return $this->hasMany('App\Models\MobilePrice', 'mobile_id');
    }

    /**
     * Get created_at in array format
     *
     * @param string $value
     * @return string
     */
    public function getCreatedAtAttribute($value)
    {
        return date(config('settings.DISPLAY_DATE_FORMAT') , strtotime($value));
    }

    /**
     * Get updated_at in array format
     *
     * @param string $value
     * @return string
     */
    public function getUpdatedAtAttribute($value)
    { 
        if($value != null){
            return date(config('settings.DISPLAY_DATE_FORMAT') , strtotime($value));
        } else {
            return null;
        }
    }

    /**
     * Get Availability in string format
     *
     * @return String
     */
    public function getAvailabilityAttribute(): string
    {
        $status = $this->attributes['status'];
        if (stripos($status, 'Available.') !== false) {
            $status = 'Available';
        } else if (stripos($status, 'Coming soon.') !== false) {
            $status = 'Upcoming';
        } else if (stripos($status, 'Rumored') !== false) {
            $status = 'Rumored';
        } else if (stripos($status, 'Cancelled') !== false) {
            $status = 'Cancelled';
        }
        return $status;
    }

    /**
     * Get size in clean format
     *
     * @return String
     */
    public function getSizeAttribute(): string
    {
        $size = $this->attributes['size'];
        $size = ScrapHelper::cleanFirstBracketContent($size);
        $parts = explode(',', $size);
        if (!empty($parts[0])) {
            $size = trim($parts[0]);
        }
        return $size;
    }

    /**
     * Get resolution in clean format
     *
     * @return String
     */
    public function getResolutionAttribute(): string
    {
        $field = $this->attributes['resolution'];
        $field = ScrapHelper::cleanFirstBracketContent($field);
        $parts = explode(',', $field);
        if (!empty($parts[0])) {
            $field = trim($parts[0]);
        }
        return $field;
    }

    /**
     * Get pixel density in clean format
     *
     * @return String
     */
    public function getPxDensityAttribute(): string
    {
        $field = $this->attributes['px_density'];
        if (!empty($field)) {
            $field = '~' . $field . ' ppi';
        }
        return $field;
    }

    /**
     * Get cpu in clean format
     *
     * @return String
     */
    public function getCpuAttribute(): string
    {
        return trim(ScrapHelper::cleanFirstBracketContent($this->attributes['cpu']));
    }

    /**
     * Get mc_resolutions in clean format
     *
     * @return String
     */
    public function getMcResolutionsAttribute(): string
    {
        return str_replace('|', '<br>', $this->attributes['mc_resolutions']);
    }

    /**
     * Get sc_resolutions in clean format
     *
     * @return String
     */
    public function getScResolutionsAttribute(): string
    {
        return str_replace('|', '<br>', $this->attributes['sc_resolutions']);
    }

    /**
     * Get wlan in clean format
     *
     * @return String
     */
    public function getWlanAttribute(): string
    {
        $field = $this->attributes['wlan'];
        $parts = array_map('trim', explode(',', $field));
        if (!empty($parts[0]) && strpos($parts[0], '/') !== false) {
            $segmentParts = explode(' ', $parts[0]);
            array_pop($segmentParts);
            $parts[0] = implode(' ', $segmentParts);
            $field = implode(', ', $parts);
        }

        return $field;
    }

    /**
     * Get gps in clean format
     *
     * @return String
     */
    public function getGpsAttribute(): string
    {
        $field = $this->attributes['gps'];
        if (!empty($field) && strpos($field, 'Yes') !== false) {
            $field = 'Yes';
        }
        return $field;
    }

    /**
     * Get chipset in clean format
     *
     * @return String
     */
    public function getChipsetAttribute(): string
    {
        $field = !empty($this->attributes['chipset']) ? $this->attributes['chipset'] : '';

        $chipset = '';
        if (!empty($field) && strpos($field, '|') !== false) {
            $field = str_replace('International', 'Global', $field);
            $parts = explode('|', $field);
            if (!empty($parts[0]) && stripos($parts[0], '-') !== false) {
                list($processor, $location) = explode('-', $parts[0]);
                if (!empty($processor) && !empty($location)) {
                    $chipset .= $location . ': ' . $processor;
                }
            }

            if (!empty($parts[1]) && stripos($parts[1], '-') !== false) {
                list($processor, $location) = explode('-', $parts[1]);
                if (!empty($chipset)) {
                    $chipset .= '<br>';
                }

                if (!empty($processor) && !empty($location)) {
                    $chipset .= $location . ': ' . $processor;
                }
            }

            if (!empty($chipset)) {
                $field = $chipset;
            }
        }
        return $field;
    }

    /**
     * Get price ranges for a specific region for variations
     *
     * @return array
     */
    public function getPriceRangesAttribute(): array
    {
        $mergePrices = [];
        $symbols = [];
        $mobilePrices = $this->mobilePrices;

        foreach ($mobilePrices as $mobilePrice) {
            $regionCode = $mobilePrice->region->iso_code;
            if (empty($regionCode)) {
                continue;
            }

            $symbol = $mobilePrice->region->symbol;

            if (empty($mergePrices[$regionCode])) {
                $mergePrices[$regionCode] = [];
            }
            $mergePrices[$regionCode][] = $mobilePrice->price;
            $symbols[$regionCode] = $symbol;
        }

        $priceRanges = [];
        foreach ($mergePrices as $regionCode => $prices) {
            $minPrice = min($prices);
            $maxPrice = max($prices);
            $symbol = $symbols[$regionCode];
            if (abs($minPrice - $maxPrice) <= 0.01) {
                $priceRanges[$regionCode] = $symbol . ' ' . $minPrice;
            } else {
                $priceRanges[$regionCode] = $symbol . ' ' . $minPrice . ' - ' . $symbol . ' ' . $maxPrice;
            }
        }

        return $priceRanges;
    }

    /**
     * Get price in clean format
     *
     * @return String
     */
    public function getPriceAttribute(): string
    {
        $field = !empty($this->attributes['price']) ? $this->attributes['price'] : '';
        if (!empty($field) && strpos($field, 'EUR') !== false) {
            $field = str_replace('About', '', $field);
            $field = str_replace('EUR', '', $field);
            $field = '€ ' . trim($field);
        }

        $currencyCode = Session::get('currency');
        if (empty($currencyCode)) {
            $currencyCode = 'USD';
        }
        $priceRange = $this->price_ranges;
        if (!empty($priceRange[$currencyCode])) {
            return $priceRange[$currencyCode];
        } else if (!empty($priceRange['USD'])) {
            return $priceRange['USD'];
        } else {
            return $field;
        }
    }

    /**
     * Get featured image from image tables
     *
     * @return String
     */
    public function getFeaturedImageAttribute(): string
    {
        $featuredImage = '';
        $image = $this->mobileImages->first();
        if (!empty($image)) {
            $featuredImage = asset('storage/mobiles/' . $this->attributes['id'] . '/' . $image->filename);
        }
        return $featuredImage;
    }

    /**
     * Get rear camera with plus format
     *
     * @return String
     */
    public function getRearCameraAttribute(): string
    {
        $rearCameraString = '';
        $cameras = $this->cameras;
        if (!empty($cameras) && $cameras->isNotEmpty()) {
            $rearCameras = [];
            foreach ($cameras as $camera) {
                if ($camera->type == 'Main') {
                    $rearCameras[] = $camera->title;
                }
            }

            if (!empty($rearCameras)) {
                $rearCameraString = 'Rear: ' . implode(' + ', $rearCameras);
            }
        }
        return $rearCameraString;
    }

    /**
     * Get front camera with plus format
     *
     * @return String
     */
    public function getFrontCameraAttribute(): string
    {
        $frontCameraString = '';
        $cameras = $this->cameras;
        if (!empty($cameras) && $cameras->isNotEmpty()) {
            $frontCameras = [];
            foreach ($cameras as $camera) {
                if ($camera->type != 'Main') {
                    $frontCameras[] = $camera->title;
                }
            }

            if (!empty($frontCameras)) {
                $frontCameraString = 'Front: ' . implode(' + ', $frontCameras);
            }
        }
        return $frontCameraString;
    }

    /**
     * Get album from mobile images tables
     *
     * @return array
     */
    public function getAlbumAttribute(): array
    {
        $album = [];
        $images = $this->mobileImages;
        if (!empty($images) && $images->count() > 1) {
            foreach ($images as $image) {
                $album[] = asset('storage/mobiles/' . $this->attributes['id'] . '/' . $image->filename);
            }
        }
        return $album;
    }

    /**
     * Get regional prices for variations
     *
     * @return array
     */
    public function getRegionalPricesAttribute(): array
    {
        $mergePrices = [];
        $mobilePrices = $this->mobilePrices;

        foreach ($mobilePrices as $mobilePrice) {
            $regionTitle = $mobilePrice->region->title;
            if (empty($mergePrices[$regionTitle])) {
                $mergePrices[$regionTitle] = [];
            }
            $mergePrices[$regionTitle][] = (object)[
                'store' => $mobilePrice->store,
                'variation' => $mobilePrice->variation,
                'symbol' => $mobilePrice->region->iso_code,
                'price' => $mobilePrice->price
            ];
        }

        $regionalPrices = [];
        foreach ($mergePrices as $regionTitle => $prices) {
            $regionalPrices[] = (object)[
                'group_label' => $regionTitle,
                'prices' => $prices
            ];
        }
        return $regionalPrices;
    }
}
