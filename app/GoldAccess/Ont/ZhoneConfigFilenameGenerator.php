<?php

namespace App\GoldAccess\Ont;

use App\OntSoftware;

class ZhoneConfigFilenameGenerator
{
    /**
     * @param  \App\OntSoftware $ont_software
     */
    public $ont_software;

    /**
     * The construct
     *
     * @return void
     */
    public function __construct(OntSoftware $ont_software)
    {
        $this->ont_software = $ont_software;
    }

    public function generate()
    {
        if ($this->is27xx()) {
            $parts = explode(".", $this->ont_software->version);
            $configFilename = $parts[0] . $parts[1] . $parts[2] . '_' . $this->ont_software->ont->model_number . '_generic.conf';
        } else {
            $parts = explode(".", $this->ont_software->version);
            $configFilename = $parts[0] . $parts[1] . $parts[2] . '_0GF_generic.conf';
        }

        return $configFilename;
    }

    /**
     * Whether or not the parent ONT's model number starts with '27'
     *
     * @return boolean
     */
    protected function is27xx()
    {
        return starts_with($this->ont_software->ont->model_number, '27');
    }

    /**
     * Whether or not the parent ONT is flagged as OEM
     *
     * @return boolean
     */
    protected function isOem()
    {
        return $this->ont_software->ont->oem;
    }
}
