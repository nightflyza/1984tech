<?php

class OrwellWorld {

    /**
     * Contains 1984tech.conf as key=>value
     *
     * @var array
     */
    protected $config = array();

    /**
     * Contains name of file with domains list
     *
     * @var string
     */
    protected $domainsFile = '';

    /**
     * Contains domains list as index=>domain
     *
     * @var array
     */
    protected $domainsList = array();

    const CONFIG_PATH = '1984tech.conf';

    /**
     * Creates new object instance
     * 
     * @return void
     */
    public function __construct() {
        $this->loadConfig();
        $this->setOptions();
        $this->loadDomains();
    }

    /**
     * Loads 1948tech.conf for further usage
     * 
     * @return void
     */
    protected function loadConfig() {
        $this->config = parse_ini_file(self::CONFIG_PATH);
    }

    /**
     * Sets options into protected props for further usage
     * 
     * @return void
     */
    protected function setOptions() {
        $this->domainsFile = $this->config['DOMAINS_LIST'];
    }

    /**
     * Loads domains from domains list file into protected prop
     * 
     * @return void
     */
    protected function loadDomains() {
        if (!empty($this->domainsFile)) {
            if (file_exists($this->domainsFile)) {
                $raw = file_get_contents($this->domainsFile);
                if (!empty($raw)) {
                    $raw = explode("\n", $raw);
                    if (!empty($raw)) {
                        foreach ($raw as $line => $eachDomain) {
                            $this->domainsList[$line] = $eachDomain;
                        }
                    }
                }
            } else {
                throw new Exception('EX_DOMAINS_FILE_NOT_EXISTS');
            }
        }
    }

    /**
     * Returns list of loaded domains
     * 
     * @return array
     */
    public function getDomains() {
        return($this->domainsList);
    }

}

?>
