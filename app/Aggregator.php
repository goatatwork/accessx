<?php

namespace App;

use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\AggregatorAlreadyHasSlots;

class Aggregator extends Model
{
    use HasSlug;

    protected $fillable = [
        'platform_id',
        'name',
        'fqdn',
        'management_ip',
        'monitoring_ip',
        'management_mac',
        'notes'
    ];

    // protected $appends = ['slug'];

    public function platform()
    {
        return $this->belongsTo(Platform::class);
    }

    public function slots()
    {
        return $this->hasMany(Slot::class);
    }

    public function ports()
    {
        return $this->hasManyThrough(Port::class, Slot::class);
    }

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function createEmptySlots()
    {
        if ($this->slots()->count() == 0)
        {
            for ($slot_number = 1; $slot_number <= $this->platform->number_of_slots; $slot_number++)
            {
                $this->slots()->create(['slot_number' => $slot_number]);
            }
        } else {
            throw new AggregatorAlreadyHasSlots();
        }
    }

}
