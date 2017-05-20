<?php

class OrwellWorld {

    /**
     * Contains 1984tech.conf as key=>value
     *
     * @var array
     */
    protected $config = array();

    /**
     * Full filesystem path to 1984tech directory
     *
     * @var string
     */
    protected $basePath = '';

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

    /**
     * Contains DNS servers IPs
     *
     * @var array
     */
    protected $dnsServers = array();

    /**
     * DNS resolver object placeholder
     *
     * @var object
     */
    protected $resolver = '';

    const CONFIG_PATH = '1984tech.ini';

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
        $this->basePath = $this->config['BASE_PATH'];
        $this->domainsFile = $this->config['DOMAINS_LIST'];
        $this->dnsAcl = $this->config['DNS_ACL'];
        $this->dnsZonesPath = $this->config['DNS_ZONES'];
        $this->dnsRedirectsPath = $this->config['DNS_REDIRECTS'];
        $dnsServersTmp = $this->config['DNS_RESOLVER_SERVERS'];

        if (!empty($dnsServersTmp)) {
            $dnsServersTmp = explode(',', $dnsServersTmp);
            if (!empty($dnsServersTmp)) {
                foreach ($dnsServersTmp as $index => $eachServer) {
                    $eachServer = trim($eachServer);
                    if (!empty($eachServer)) {
                        $this->dnsServers[] = $eachServer;
                    }
                }
            }
        }
    }

    /**
     * Loads domains from domains list file into protected prop
     * 
     * @return void
     */
    protected function loadDomains() {
        if (!empty($this->domainsFile)) {
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
     * @return string/void - generated filename
     */
    public function saveBindZones() {
        $result = '';
        $zonesData = $this->getBindZones();
        if (!empty($this->dnsZonesPath)) {
            file_put_contents($this->basePath . $this->dnsZonesPath, $zonesData);
            $result = $this->basePath . $this->dnsZonesPath;
        } else {
            $result = '';
        }
        return ($result);
    }

    /**
     * Initializes dns resolver object incstance for further usage
     * 
     * @return void
     */
    protected function initDnsResolver() {
        require_once ('Net/DNS2.php');
        $this->resolver = new Net_DNS2_Resolver(array('nameservers' => $this->dnsServers));
    }

    /**
     * Performs DNS lookup of some domain, returns list of received IPs
     * 
     * @param string $domain 
     * 
     * @return  array
     */
    public function resolveDNS($domain) {
        $result = array();
        if (empty($this->resolver)) {
            $this->initDnsResolver();
        }

        try {
            $queryTmp = $this->resolver->query($domain, 'A');
            if (!empty($queryTmp)) {
                if (isset($queryTmp['answer'])) {
                    
                }
            }
        } catch (Exception $e) {
            print('Fail: ' . $e . "\n");
        }
        return ($result);
    }

}

?>
