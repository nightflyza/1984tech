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

    /**
     * Contains ipfw table number to push IPs
     *
     * @var int
     */
    protected $ipfwTable = 0;

    /**
     * Contains ipfw binary path
     *
     * @var 
     */
    protected $ipfwPath = '';

    /**
     * Contains ipfw variable name for scrips generation
     *
     * @var string
     */
    protected $ipfwMacro = '';

    /**
     * Contains ipfw script generation path
     *
     * @var string
     */
    protected $ipfwScriptPath = '';

    /**
     * Contains name of address list for Mikrotik
     *
     * @var string
     */
    protected $mtListName = '';

    /**
     * Contains path for generation of Mikrotik address-list update script
     *
     * @var string
     */
    protected $mtScriptPath = '';

    /**
     * Contains path of iptables binary
     *
     * @var string
     */
    protected $iptablesPath = '';

    /**
     * Contains iptables blocking chain
     *
     * @var string
     */
    protected $iptablesChain = '';

    /**
     * Contains ipset blacklist name
     *
     * @var string
     */
    protected $ipsetListName = '';

    /**
     * Contains ipset binary path
     *
     * @var string
     */
    protected $ipsetPath = '';

    /**
     * Contains JunOS black list name
     *
     * @var string
     */
    protected $junListName = '';

    /**
     * Contains Cisco IOS access list number
     *
     * @var string
     */
    protected $cisListNum = '';

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
        $this->dnsUnboundZonesPath = $this->config['UNBOUND_DNS_ZONES'];
        $this->dnsRedirectsPath = $this->config['DNS_REDIRECTS'];
        $this->ipfwPath = $this->config['IPFW_PATH'];
        $this->ipfwTable = $this->config['IPFW_TABLE'];
        $this->ipfwMacro = $this->config['IPFW_MACRO'];
        $this->ipfwScriptPath = $this->config['IPFW_SCRIPT_PATH'];
        $this->mtListName = $this->config['MT_LISTNAME'];
        $this->mtScriptPath = $this->config['MT_SCRIPT_PATH'];
        $this->iptablesPath = $this->config['IPTABLES_PATH'];
        $this->iptablesChain = $this->config['IPTABLES_CHAIN'];
        $this->ipsetPath = $this->config['IPSET_PATH'];
        $this->ipsetListName = $this->config['IPSET_LISTNAME'];
        $this->SquidPath = $this->config['SQUID_PATH'];
        $this->junListName = $this->config['JUN_LISTNAME'];
        $this->cisListNum = $this->config['CIS_LISTNUM'];

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
                $raw = explode(PHP_EOL, $raw);
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
        $result = '';
        if (!empty($this->domainsList)) {
            foreach ($this->domainsList as $io => $eachDomain) {
                $result.=$eachDomain . PHP_EOL;
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
                $result.= 'zone "' . $eachDomain . '" { type master; file "' . $this->dnsRedirectsPath . '"; allow-query { ' . $this->dnsAcl . ' }; };' . PHP_EOL;
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
            file_put_contents($this->dnsZonesPath, $zonesData);
            $result = $this->dnsZonesPath;
        } else {
            $result = '';
        }
        return ($result);
    }

    /**
     * Returns unbound zones file
     *
     * @return string
     */
    public function getUnboundZones() {
        $result = '';
        if (!empty($this->domainsList)) {
            foreach ($this->domainsList as $io => $eachDomain) {
                $result.= 'local-zone: "' . $eachDomain . '" static' . PHP_EOL;
                $result.= 'local-data: "' . $eachDomain . ' A 127.0.0.1"' . PHP_EOL;
            }
        }
        return ($result);
    }

    /**
     * Rewrites unbound zones files
     *
     * @return string/void - generated filename
     */
    public function saveUnboundZones() {
        $result = '';
        $zonesData = $this->getUnboundZones();
        if (!empty($this->dnsUnboundZonesPath)) {
            file_put_contents($this->dnsUnboundZonesPath, $zonesData);
            $result = $this->dnsUnboundZonesPath;
        } else {
            $result = '';
        }
        return ($result);
    }

    /**
     * Returns Squid configs file file
     *
     * @return string
     */
    public function getSquidConfig($config) {
        $result = '';
        if (!empty($config)) {
            $result.= $config;
        }
        return ($result);
    }

    /**
     * Rewrites Squid Configs files
     *
     * @return string/void - generated filename
     */
    public function saveSquid($config, $squidCA, $ERR_1984TECH) {
        $result = '';
        $zonesData = $this->renderDomainsRaw();
        if (!empty($zonesData) and ! empty($config) and ! empty($squidCA)) {
            file_put_contents($this->SquidPath . '/squid.conf', $config);
            file_put_contents($this->SquidPath . '/squidCA.pem', $squidCA);
            file_put_contents($this->SquidPath . '/1984tech.conf', $zonesData);
            if (is_dir($this->SquidPath) and ! is_dir($this->SquidPath . '/errors/templates/')) {
                mkdir($this->SquidPath . '/errors/templates/', 0755, true);
            }
            file_put_contents($this->SquidPath . '/errors/templates/ERR_1984TECH', $ERR_1984TECH);
            $result = $this->SquidPath . '/squid.conf' . PHP_EOL;
            $result .= $this->SquidPath . '/1984tech.conf' . PHP_EOL;
            $result .= $this->SquidPath . '/errors/templates/ERR_1984TECH' . PHP_EOL;
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
     * @param string $type
     * 
     * @return  array
     */
    protected function getDomainIps($domain, $type = 'A') {
        $result = array();
        if (empty($this->resolver)) {
            $this->initDnsResolver();
        }

        try {
            $queryTmp = $this->resolver->query($domain, $type);
            if (!empty($queryTmp)) {
                if (!empty($queryTmp->answer)) {
                    foreach ($queryTmp->answer as $io) {
                        $result[$io->address] = $io->name;
                    }
                }
            }
        } catch (Exception $e) {
            print('Fail: ' . $e->getMessage() . PHP_EOL);
        }
        return ($result);
    }

    /**
     * Performs all loaded domains IPs resolving
     * 
     * @return array
     */
    protected function resolveAllDomainsIps() {
        $result = array();
        if (!empty($this->domainsList)) {
            foreach ($this->domainsList as $domainIndex => $eachDomain) {
                $domainIps = $this->getDomainIps($eachDomain);
                if (!empty($domainIps)) {
                    foreach ($domainIps as $domainIp => $domainName) {
                        if (!empty($domainIp)) {
                            $result[$domainIp] = $domainName;
                        }
                    }
                }
            }
        }
        return ($result);
    }

    /**
     * Returns ipfw rules list
     * 
     * @param bool $useMacro - use raw path or variable name as ipfw command
     * 
     * @return string
     */
    public function getIpfwRules($useMacro) {
        $result = '';
        if ((!empty($this->domainsList)) AND ( !empty($this->ipfwTable)) AND ( !empty($this->ipfwPath))) {
            $allDomainIps = $this->resolveAllDomainsIps();
            if ($useMacro) {
                $ipfwCommand = '${' . $this->ipfwMacro . '}';
            } else {
                $ipfwCommand = $this->ipfwPath;
            }
            if (!empty($allDomainIps)) {
                foreach ($allDomainIps as $eachIp => $eachDomain) {
                    $result.=$ipfwCommand . ' table ' . $this->ipfwTable . ' add ' . $eachIp . PHP_EOL;
                }
            }
        }
        return ($result);
    }

    /**
     * Returns ipfw script for table filling
     * 
     * @return string
     */
    public function getIpfwScript() {
        $result = '#!/bin/sh' . PHP_EOL;
        $result.=$this->ipfwMacro . '="/sbin/ipfw -q"' . PHP_EOL;
        $result.='${' . $this->ipfwMacro . '} -f table ' . $this->ipfwTable . ' flush' . PHP_EOL;
        $result.=$this->getIpfwRules(true);
        return ($result);
    }

    /**
     * Returns ipfw script for table filling
     * 
     * @return string
     */
    public function getMikrotikScript() {
        $result = '/ip firewall address-list' . PHP_EOL;
        if ((!empty($this->domainsList)) AND ( !empty($this->mtListName))) {
            $allDomainIps = $this->resolveAllDomainsIps();
            if (!empty($allDomainIps)) {
                foreach ($allDomainIps as $eachIp => $eachDomain) {
                    $result.='add address=' . $eachIp . ' list=' . $this->mtListName . PHP_EOL;
                }
            }
        }
        return ($result);
    }

    /**
     * Saves mikrotik update script to filesystem
     * 
     * @return string/void
     */
    public function saveMikrotikScript() {
        $result = '';
        $mtScript = $this->getMikrotikScript();
        if (!empty($this->mtScriptPath)) {
            file_put_contents($this->mtScriptPath, $mtScript);
            $result = $this->mtScriptPath;
        }
        return ($result);
    }

    /**
     * Returns JunOS policy script
     * 
     * @return string
     */
    public function getJunosScript() {
        $result = '';
        if ((!empty($this->domainsList)) AND ( !empty($this->junListName))) {
            $allDomainIps = $this->resolveAllDomainsIps();
            if (!empty($allDomainIps)) {
                foreach ($allDomainIps as $eachIp => $eachDomain) {
                    $result.='set policy-options prefix-list ' . $this->junListName . ' ' . $eachIp . '/32' . PHP_EOL;
                }
            }
        }
        return ($result);
    }

    /**
     * Returns Cisco IOS script
     * 
     * @return string
     */
    public function getCiscoScript() {
        $result = '';
        if ((!empty($this->domainsList)) AND ( !empty($this->cisListNum))) {
            $allDomainIps = $this->resolveAllDomainsIps();
            if (!empty($allDomainIps)) {
                foreach ($allDomainIps as $eachIp => $eachDomain) {
                    $result.='access-list ' . $this->cisListNum . ' deny ip any host ' . $eachIp . '/32' . PHP_EOL;
                }
            }
        }
        return ($result);
    }

    /**
     * Saves ipfw script
     * 
     * @return string/void
     */
    public function saveIpfwScript() {
        $result = '';
        $ipfwScript = $this->getIpfwScript();
        if (!empty($this->ipfwScriptPath)) {
            file_put_contents($this->ipfwScriptPath, $ipfwScript);
            $result = $this->ipfwScriptPath;
        }
        return ($result);
    }

    /**
     * Renders list of all loaded domains IPs
     * 
     * @return string
     */
    public function renderDomainsIps() {
        $result = '';
        $allDomainIps = $this->resolveAllDomainsIps();
        if (!empty($allDomainIps)) {
            foreach ($allDomainIps as $ip => $domain) {
                $result.=$ip . ' ' . $domain . PHP_EOL;
            }
        }
        return ($result);
    }

    /**
     * Updates ipfw table with domains IPs
     * 
     * @return string
     */
    public function ipfwTableUpdate() {
        $result = '';
        $allIpfwRules = $this->getIpfwRules(false);
        if ((!empty($allIpfwRules)) AND ( !empty($this->ipfwTable))) {
            $allIpfwRules = explode(PHP_EOL, $allIpfwRules);
            if (!empty($allIpfwRules)) {
                foreach ($allIpfwRules as $io => $eachRule) {
                    if (!empty($eachRule)) {
                        $result.=shell_exec($eachRule);
                    }
                }
            }
        }
        return ($result);
    }

    /**
     * Returns ipset update script
     * 
     * @param bool $run
     * 
     * @return string
     */
    public function getIpsetScript($run = false) {
        $result = '';
        if ((!empty($this->domainsList)) AND ( !empty($this->ipsetListName)) AND ( !empty($this->ipsetPath))) {
            $allDomainIps = $this->resolveAllDomainsIps();
            if (!empty($allDomainIps)) {
                foreach ($allDomainIps as $eachIp => $eachDomain) {
                    $template = $this->ipsetPath . ' -A ' . $this->ipsetListName . ' ' . $eachIp . PHP_EOL;
                    if (!$run) {
                        $result.=$template;
                    } else {
                        $result.=shell_exec($template);
                    }
                }
            }
        }
        return ($result);
    }

    /**
     * Returns iptables update script
     * 
     * @param bool $run
     * 
     * @return string
     */
    public function getIptablesScript($run = false) {
        $result = '';
        if ((!empty($this->domainsList)) AND ( !empty($this->iptablesChain)) AND ( !empty($this->iptablesPath))) {
            $allDomainIps = $this->resolveAllDomainsIps();
            if (!empty($allDomainIps)) {
                foreach ($allDomainIps as $eachIp => $eachDomain) {
                    $template = $this->iptablesPath . ' -A ' . $this->iptablesChain . ' -d ' . $eachIp . ' -j DROP' . PHP_EOL;
                    if (!$run) {
                        $result.=$template;
                    } else {
                        $result.=shell_exec($template);
                    }
                }
            }
        }
        return ($result);
    }

}

?>
