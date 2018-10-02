<?php

namespace App\GoldAccess\Ont;

class ZhoneFilenameConverter
{
    /**
     * @param boolean $oem
     */
    public $oem;

    /**
     * Example: ZNID-24xxA-301266-SIP.img
     *
     * @param  string $original_filename
     */
    public $original_filename;

    /**
     * @var  The strange string that Zhone will request
     */
    public $dhcp_config_string;

    /**
     * @var  string The device type, almost always ZNID
     */
    public $type;

    /**
     * @var  string The model number indicator in the filename
     */
    public $model_number;

    /**
     * @var  string The software version
     */
    public $version;

    /**
     * @var  string The protocol that the software image supports
     */
    public $protocol;

    /**
     * @var  string The version string used in the database
     */
    public $version_string_for_database;

    /**
     * The construct
     * // ZNID24xxA_GRSIP_0301266_image_with_cfe.img (GF models) or ZNID24xxASIP_0301266_image_with_cfe.img (Zhone models)
     *
     * @return void
     */
    public function __construct($original_filename, $oem = false)
    {
        $this->original_filename = $original_filename;
        $this->oem = $oem;
    }

    public function getDestinationFilename()
    {
        // ZNID27xxA1SIP_0401069_image_with_cfe.img for 27xx
        if ($this->is27xx()) {
            return $this->type . $this->model_number . $this->protocol . '_0' . $this->version . '_image_with_cfe.img';
        }

        // ZNID24xxASIP_0301266_image_with_cfe.img for NOT oem'd
        // ZNID24xxA_GRSIP_0301266_image_with_cfe.img for oem'd
        return $this->oem ?
            $this->type . $this->model_number . '_GR' . $this->protocol . '_0' . $this->version . '_image_with_cfe.img' :
            $this->type . $this->model_number . $this->protocol . '_0' . $this->version . '_image_with_cfe.img';

    }

    public function calculate()
    {
        $this->type = $this->getFilenameParts()[0];
        $this->model_number = $this->getFilenameParts()[1];
        $this->version = $this->getFilenameParts()[2];
        $this->protocol = $this->getFilenameParts()[3];
        $this->dhcp_config_string = substr($this->protocol, 0, 1) . '0' . $this->version;
        $this->version_string_for_database = $this->getVersionForDatabase();
        return $this;
    }

    /**
     * The string used 'version' in the database. Example:S03.01.266
     *
     * @return string
     */
    protected function getVersionForDatabase()
    {
        $characters = str_split($this->version);
        $short_voip = substr($this->protocol, 0, 1);

        return $short_voip . '0' . $characters[0] . '.' . $characters[1] . $characters[2] . '.' . $characters[3] . $characters[4] . $characters[5];
    }

    /**
     * @return string
     */
    protected function getOriginalFilenameWithoutExtension()
    {
        return explode('.', $this->original_filename)[0];  // ZNID-24xxA-301266-SIP
    }

    protected function makeDhcpString($protocol, $version)
    {
        return ($protocol == 'SIP') ? 'S0' . $version : 'M0' . $version;
    }

    /**
     * Split up the filename to get it's indicators/parts.
     * Filename ZNID-24xxA-301266-SIP.img would result in an array like this:
     * ["ZNID","24xxA","301266","SIP"]
     *
     * @return array
     */
    public function getFilenameParts()
    {
        return explode('-', $this->getOriginalFilenameWithoutExtension());
    }

    protected function is27xx()
    {
        return starts_with($this->model_number, '27');
    }
}
