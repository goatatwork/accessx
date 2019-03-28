<?php

namespace App\Access3;

use Illuminate\Database\Eloquent\Model;

// use OwenIt\Auditing\Auditable;

class OntConfig extends Model
{
    // use Provisionable, Auditable;
    use Provisionable;

    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'mysql_access3';

    protected $fillable = [
        'name',
        'description',
        'public_path',
        'tftp_path',
        'dhcp_filename_string',
    ];

    public function software()
    {
        return $this->belongsTo(OntSoftware::class, 'ont_software_id');
    }

    public function getDhcpConfigFileStringAttribute()
    {
        $tftpPathinfo = pathinfo($this->tftp_path);
        $directory_from_tftp_path = $tftpPathinfo['dirname'];
        $path_parts = explode('/', $directory_from_tftp_path);
        array_splice($path_parts, 0, 2);
        $dhcpFilenamePath = implode('/', $path_parts);
        return $dhcpFilenamePath . '/' . $this->software->dhcp_filename_string;
    }
}
