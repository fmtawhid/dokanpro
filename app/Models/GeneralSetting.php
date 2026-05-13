<?php

namespace App\Models;

use App\Enums\CurrencyPosition;
use App\Enums\DateFormat;
use App\Enums\DateWithTime;
use App\Enums\Direction;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeneralSetting extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'currency_position' => CurrencyPosition::class,
        'date_format' => DateFormat::class,
        'date_with_time' => DateWithTime::class,
        'direction' => Direction::class
    ];

    public function logo()
    {
        return $this->belongsTo(Media::class, 'logo_id');
    }

    public function smallLogo()
    {
        return $this->belongsTo(Media::class, 'small_logo_id');
    }

    public function favicon()
    {
        return $this->belongsTo(Media::class, 'fav_id');
    }

    public function defaultCurrency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }
}
