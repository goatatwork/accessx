<?php

namespace App;

use OwenIt\Auditing\Auditable;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use OwenIt\Auditing\Contracts\Auditable as AuditableContract;

class OntSoftware extends Model implements HasMedia, AuditableContract
{
    use HasMediaTrait, Auditable;

    protected $fillable = ['version','notes'];

    protected $table = 'ont_software';

    protected $appends = ['file', 'has_provisioning_records'];

    public function ont()
    {
        return $this->belongsTo(Ont::class);
    }

    public function ont_profiles()
    {
        return $this->HasMany(OntProfile::class);
    }

    public function provisioning_records()
    {
        return $this->hasManyThrough(ProvisioningRecord::class, OntProfile::class);
    }

    public function getFileAttribute()
    {
        return $this->getFirstMedia();
    }

    public function getHasProvisioningRecordsAttribute()
    {
        return $this->provisioning_records()->exists();
    }

    public function getHasSuspendConfigAttribute()
    {
        return $this->ont_profiles()->whereName('Suspended')->exists();
    }
}
