<?php

namespace App;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\Interfaces\HasMedia;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class OntSoftware extends Model implements HasMedia, AuditableContract
{
    use HasMediaTrait, Auditable;

    protected $fillable = ['version','notes'];

    protected $table = 'ont_software';

    protected $appends = ['file'];

    public function ont()
    {
        return $this->belongsTo(Ont::class);
    }

    public function ont_profiles()
    {
        return $this->HasMany(OntProfile::class);
    }

    public function getFileAttribute()
    {
        return $this->getFirstMedia();
    }
}
