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

    /**
     * Contains ACLs list for bind zones config
     *
     * @var string
     */
    protected $dnsAcl = '';

    /**
     * Contains path for generating DNS zones file
     *
     * @var string
     */
    protected $dnsZonesPath = '';

    /**
     * Contains bind server zone file with domain redirects
     *
     * @var string
     */
    protected $dnsRedirectsPath = '';

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
        $this->dnsAcl = $this->config['DNS_ACL'];
        $this->dnsZonesPath = $this->config['DNS_ZONES'];
        $this->dnsRedirectsPath = $this->config['DNS_REDIRECTS'];
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
                            if (!empty($eachDomain)) {
                                $this->domainsList[$line] = trim($eachDomain);
                            }
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

    /**
     * Returns list of loaded domains
     * 
     * @return string
     */
    public function renderDomainsRaw() {
        $reult = '';
        if (!empty($this->domainsList)) {
            foreach ($this->domainsList as $io => $eachDomain) {
                $result.=$eachDomain . "\n";
            }
        }
        return ($result);
    }

    /**
     * Returns isc-bind zones file
     * 
     * @return string
     */
    public function getBindZones() {
        $result = '';
        if (!empty($this->domainsList)) {
            foreach ($this->domainsList as $io => $eachDomain) {
                $result.= 'zone "' . $eachDomain . '" { type master; file "' . $this->dnsRedirectsPath . '"; allow-query { ' . $this->dnsAcl . ' }; };' . "\n";
            }
        }
        return ($result);
    }

    /**
     * Rewrites isc-bind zones files
     * 
     * @return void
     */
    public function saveBindZones() {
        $zonesData = $this->getBindZones();
        file_put_contents($this->dnsZonesPath, $zonesData);
    }

}

?>
