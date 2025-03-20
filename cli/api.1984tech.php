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
     * Mikrotik static DNS default IP to point the domains to
     *
     * @var string
     */
    protected $mtDNSStaticIP = '127.0.0.1';

    /**
     * Mikrotik static DNS default TTL for added DNS records
     *
     * @var string
     */
    protected $mtDNSStaticTTL = "00:30:00";

    /**
     * Mikrotik static DNS script path
     *
     * @var string
     */
    protected $mtDNSStaticScriptPath = '';

    /**
     * Mikrotik domains list file chunks path
     *
     * @var string
     */
    protected $mtDNSStaticChunksPath = '';

    /**
     * Mikrotik domains list file chunks base name
     *
     * @var string
     */
    protected $mtDNSStaticChunksBaseName = 'mt_dnsstatic_chunk_';

    /**
     * Mikrotik domains list file chunks extension
     *
     * @var string
     */
    protected $mtDNSStaticChunksExt = '.1984t';

    /**
     * PDNSD script path
     *
     * @var string
     */
    protected $pdnsdScriptPath = '';

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

    /**
     * Contains default unbound redirection host
     *
     * @var string
     */
    protected $unboundRedirectHost = '127.0.0.1';

    /**
     * unbound zones config path
     *
     * @var string
     */
    protected $dnsUnboundZonesPath = '';

    /**
     * Squid directory path
     *
     * @var string
     */
    protected $SquidPath = '';

    /**
     * Primary configuration file path/name
     */
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
        $this->dnsRPZzoneName = $this->config['RPZ_ZONE_NAME'];
        $this->dnsRPZzoneFile = $this->config['RPZ_ZONE_FILE'];
        $this->ipfwPath = $this->config['IPFW_PATH'];
        $this->ipfwTable = $this->config['IPFW_TABLE'];
        $this->ipfwMacro = $this->config['IPFW_MACRO'];
        $this->ipfwScriptPath = $this->config['IPFW_SCRIPT_PATH'];
        $this->mtListName = $this->config['MT_LISTNAME'];
        $this->mtScriptPath = $this->config['MT_SCRIPT_PATH'];
        $this->mtDNSStaticIP = $this->config['MT_DNSSTATIC_IP'];
        $this->mtDNSStaticTTL = $this->config['MT_DNSSTATIC_TTL'];
        $this->mtDNSStaticScriptPath = $this->config['MT_DNSSTATIC_SCRIPT_PATH'];
        $this->mtDNSStaticChunksPath = $this->config['MT_DNSSTATIC_CHUNKS_PATH'];
        $this->pdnsdScriptPath = $this->config['PDNSD_SCRIPT_PATH'];
        $this->iptablesPath = $this->config['IPTABLES_PATH'];
        $this->iptablesChain = $this->config['IPTABLES_CHAIN'];
        $this->ipsetPath = $this->config['IPSET_PATH'];
        $this->ipsetListName = $this->config['IPSET_LISTNAME'];
        $this->SquidPath = $this->config['SQUID_PATH'];
        $this->junListName = $this->config['JUN_LISTNAME'];
        $this->cisListNum = $this->config['CIS_LISTNUM'];
        $dnsServersTmp = $this->config['DNS_RESOLVER_SERVERS'];
        if (isset($this->config['UNBOUND_REDIRECT_HOST'])) {
            if (!empty($this->config['UNBOUND_REDIRECT_HOST'])) {
                $this->unboundRedirectHost = $this->config['UNBOUND_REDIRECT_HOST'];
            }
        }

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
     * Returns domains list array from dataSource file as lineIndex=>domain
     * 
     * @return array
     */
    protected function loadDomainsSource($dataSource) {
        $result = array();
        if (!empty($dataSource)) {
            $raw = file_get_contents($dataSource);
            if (!empty($raw)) {
                $raw = explode(PHP_EOL, $raw);
                if (!empty($raw)) {
                    foreach ($raw as $line => $eachDomain) {
                        if (!empty($eachDomain)) {
                            $result[$line] = trim($eachDomain);
                        }
                    }
                }
            }
        }
        return ($result);
    }

    /**
     * Loads domains from domains list file into protected prop
     * 
     * @return void
     */
    protected function loadDomains() {
        if (!empty($this->domainsFile)) {
            $this->domainsList = $this->loadDomainsSource($this->domainsFile);
        }
    }

    /**
     * Returns list of loaded domains
     * 
     * @return array
     */
    public function getDomains() {
        return ($this->domainsList);
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
                $result .= $eachDomain . PHP_EOL;
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
                $result .= 'zone "' . $eachDomain . '" { type master; file "' . $this->dnsRedirectsPath . '"; allow-query { ' . $this->dnsAcl . ' }; };' . PHP_EOL;
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
     * Validate isc-bind RPZ zone file
     *
     * @return string
     */
    protected function validateBindRpzZoneFile() {
        $result = '';
        if (file_exists($this->dnsRPZzoneFile)) {
            $result = trim(shell_exec('named-checkzone rpz ' . $this->dnsRPZzoneFile . ' | grep "OK"'));
        }
        return $result;
    }

    /**
     * Returns current zone serial
     *
     * @return string
     */
    protected function getBindRpzSerial() {
        $result = '';
        $result = shell_exec('rndc zonestatus ' . $this->dnsRPZzoneName . ' | grep "serial: "');
        $result = trim(str_replace("serial: ", "", $result));
        return $result;
    }

    /**
     * Returns isc-bind RPZ zone file
     *
     * @return string
     */
    public function getBindRpzZone() {
        $result = '';
        if (!empty($this->domainsList)) {
            $result .= file_get_contents("cli/bind-rpz.template");
            foreach ($this->domainsList as $io => $eachDomain) {
                $result .= $eachDomain . "\t" . 'A' . "\t" . '127.0.0.1' . PHP_EOL;
                $result .= "*." . $eachDomain . "\t" . 'A' . "\t" . '127.0.0.1' . PHP_EOL;
            }
        }
        // replace current serial to new serial
        $result = preg_replace("/{serial}/", ($this->getBindRpzSerial()) + 1, $result);
        return ($result);
    }

    /**
     * Rewrite isc-bind RPZ zone file
     * 
     * @return string/void - generated filename
     */
    public function saveBindRpzZone() {
        if (file_exists($this->dnsRPZzoneFile)) {
            rename($this->dnsRPZzoneFile, $this->dnsRPZzoneFile . ".bak");
            $zoneData = $this->getBindRpzZone();
            file_put_contents($this->dnsRPZzoneFile, $zoneData);
            if ($this->validateBindRpzZoneFile() == "OK") {
                $result = shell_exec('rndc reload ' . $this->dnsRPZzoneName);
                unlink($this->dnsRPZzoneFile . ".bak");
                return $result;
            } else {
                rename($this->dnsRPZzoneFile . ".bak", $this->dnsRPZzoneFile);
                die('BIND config error');
            }
        } else {
            die('no zone file found');
        }
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
                $result .= 'local-zone: "' . $eachDomain . '" static' . PHP_EOL;
                $result .= 'local-data: "' . $eachDomain . ' A ' . $this->unboundRedirectHost . '"' . PHP_EOL;
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
            $result .= $config;
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
        if (!empty($zonesData) and !empty($config) and !empty($squidCA)) {
            file_put_contents($this->SquidPath . '/squid.conf', $config);
            file_put_contents($this->SquidPath . '/squidCA.pem', $squidCA);
            file_put_contents($this->SquidPath . '/1984tech.conf', $zonesData);
            if (is_dir($this->SquidPath) and !is_dir($this->SquidPath . '/errors/templates/')) {
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
        require_once('Net/DNS2.php');
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
                        if (!empty($domainIp) and $domainIp != '127.0.0.1') {
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
        if ((!empty($this->domainsList)) and (!empty($this->ipfwTable)) and (!empty($this->ipfwPath))) {
            $allDomainIps = $this->resolveAllDomainsIps();
            if ($useMacro) {
                $ipfwCommand = '${' . $this->ipfwMacro . '}';
            } else {
                $ipfwCommand = $this->ipfwPath;
            }
            if (!empty($allDomainIps)) {
                foreach ($allDomainIps as $eachIp => $eachDomain) {
                    $result .= $ipfwCommand . ' table ' . $this->ipfwTable . ' add ' . $eachIp . PHP_EOL;
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
        $result .= $this->ipfwMacro . '="/sbin/ipfw -q"' . PHP_EOL;
        $result .= '${' . $this->ipfwMacro . '} -f table ' . $this->ipfwTable . ' flush' . PHP_EOL;
        $result .= $this->getIpfwRules(true);
        return ($result);
    }

    /**
     * Returns ipfw script for table filling
     * 
     * @return string
     */
    public function getMikrotikScript() {
        $result = '/ip firewall address-list' . PHP_EOL;
        if ((!empty($this->domainsList)) and (!empty($this->mtListName))) {
            $allDomainIps = $this->resolveAllDomainsIps();
            if (!empty($allDomainIps)) {
                foreach ($allDomainIps as $eachIp => $eachDomain) {
                    $result .= 'add address=' . $eachIp . ' list=' . $this->mtListName . PHP_EOL;
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
    public function getMikrotikScriptDomains() {
        $result = '/ip firewall address-list' . PHP_EOL;
        if ((!empty($this->domainsList)) and (!empty($this->mtListName))) {
            foreach ($this->domainsList as $io => $eachDomain) {
                $result .= 'add address=' . $eachDomain . ' list=' . $this->mtListName . PHP_EOL;
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
     * Generates suitable output for adding Mikrotik static DNS records
     *
     * @return string
     */
    public function getMTStaticDNSScript() {
        $result = '/ip dns static' . PHP_EOL;

        if (!empty($this->domainsList) and !empty($this->mtDNSStaticScriptPath)) {
            foreach ($this->domainsList as $io => $eachDomain) {
                $result .= 'add address=' . $this->mtDNSStaticIP . ' name=' . $eachDomain . ' ttl=' . $this->mtDNSStaticTTL . PHP_EOL;
            }
        }

        return ($result);
    }

    /**
     * Saves output for adding Mikrotik static DNS records into a file
     *
     * @return string
     */
    public function saveMTStaticDNSScript() {
        $result = '';
        $mtScript = $this->getMTStaticDNSScript();

        if (!empty($this->mtDNSStaticScriptPath)) {
            file_put_contents($this->mtDNSStaticScriptPath, $mtScript);
            $result = $this->mtDNSStaticScriptPath;
        }

        return ($result);
    }

    /**
     * Splits domains list to file chunks with size under 4096 bytes for processing with internal Mikrotik script
     *
     * @return int
     */
    public function splitDNsListToChunksForMT() {
        $chunk = '';
        $chunkSize = 4096;
        $chunkCounter = 0;
        $dnsRecParams = ',' . $this->mtDNSStaticIP . ',' . $this->mtDNSStaticTTL;

        if (!empty($this->domainsList)) {
            foreach ($this->domainsList as $io => $eachDomain) {
                $chunk .= $eachDomain . $dnsRecParams . PHP_EOL;

                if (strlen($chunk) >= $chunkSize) {
                    $chunkCounter++;
                    $chunk = str_replace($eachDomain . $dnsRecParams . PHP_EOL, '', $chunk);
                    file_put_contents($this->mtDNSStaticChunksPath . DIRECTORY_SEPARATOR . $this->mtDNSStaticChunksBaseName . $chunkCounter . $this->mtDNSStaticChunksExt, $chunk);
                    $chunk = '';
                }
            }

            if (!empty($chunk)) {
                $chunkCounter++;
                file_put_contents($this->mtDNSStaticChunksPath . DIRECTORY_SEPARATOR . $this->mtDNSStaticChunksBaseName . $chunkCounter . $this->mtDNSStaticChunksExt, $chunk);
                $chunk = '';
            }
        }

        return ($chunkCounter);
    }

    /**
     * Saves negation sections output suitable for PDNSD config to a file
     *
     * @return string
     */
    public function getPDNSDScript() {
        $result = '';

        if (!empty($this->domainsList) and !empty($this->pdnsdScriptPath)) {
            foreach ($this->domainsList as $io => $eachDomain) {
                $result .= 'neg {name=' . $eachDomain . '; types=domain;}' . PHP_EOL;
            }
        }

        return ($result);
    }

    /**
     * Generates negation sections output suitable for PDNSD config
     *
     * @return string
     */
    public function savePDNSDScript() {
        $result = '';
        $pdnsdScript = $this->getPDNSDScript();

        if (!empty($this->pdnsdScriptPath)) {
            file_put_contents($this->pdnsdScriptPath, $pdnsdScript);
            $result = $this->pdnsdScriptPath;
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
        if ((!empty($this->domainsList)) and (!empty($this->junListName))) {
            $allDomainIps = $this->resolveAllDomainsIps();
            if (!empty($allDomainIps)) {
                foreach ($allDomainIps as $eachIp => $eachDomain) {
                    $result .= 'set policy-options prefix-list ' . $this->junListName . ' ' . $eachIp . '/32' . PHP_EOL;
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
        if ((!empty($this->domainsList)) and (!empty($this->cisListNum))) {
            $allDomainIps = $this->resolveAllDomainsIps();
            if (!empty($allDomainIps)) {
                foreach ($allDomainIps as $eachIp => $eachDomain) {
                    $result .= 'access-list ' . $this->cisListNum . ' deny ip any host ' . $eachIp . '/32' . PHP_EOL;
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
                $result .= $ip . ' ' . $domain . PHP_EOL;
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
        if ((!empty($allIpfwRules)) and (!empty($this->ipfwTable))) {
            $allIpfwRules = explode(PHP_EOL, $allIpfwRules);
            if (!empty($allIpfwRules)) {
                foreach ($allIpfwRules as $io => $eachRule) {
                    if (!empty($eachRule)) {
                        $result .= shell_exec($eachRule);
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
        if ((!empty($this->domainsList)) and (!empty($this->ipsetListName)) and (!empty($this->ipsetPath))) {
            $allDomainIps = $this->resolveAllDomainsIps();
            if (!empty($allDomainIps)) {
                foreach ($allDomainIps as $eachIp => $eachDomain) {
                    $template = $this->ipsetPath . ' -A ' . $this->ipsetListName . ' ' . $eachIp . PHP_EOL;
                    if (!$run) {
                        $result .= $template;
                    } else {
                        $result .= shell_exec($template);
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
        if ((!empty($this->domainsList)) and (!empty($this->iptablesChain)) and (!empty($this->iptablesPath))) {
            $allDomainIps = $this->resolveAllDomainsIps();
            if (!empty($allDomainIps)) {
                foreach ($allDomainIps as $eachIp => $eachDomain) {
                    $template = $this->iptablesPath . ' -A ' . $this->iptablesChain . ' -d ' . $eachIp . ' -j DROP' . PHP_EOL;
                    if (!$run) {
                        $result .= $template;
                    } else {
                        $result .= shell_exec($template);
                    }
                }
            }
        }
        return ($result);
    }

    /**
     * Checks current domains list for domain duplicates
     * 
     * @param bool $useLocalList
     * 
     * @return void
     */
    public function uniqueCheck($useLocalList = false) {
        $domainTmp = array();
        $dupCount = 0;
        $totalCount = 0;
        if ($useLocalList) {
            $dataSource = 'domains.txt';
            $listToCheck = $this->loadDomainsSource($dataSource);
        } else {
            $dataSource = $this->domainsFile;
            $listToCheck = $this->domainsList;
        }


        if (!empty($this->domainsList)) {
            print('Looking for domain duplicates in ' . $dataSource . PHP_EOL);
            print(' ==================' . PHP_EOL);
            foreach ($listToCheck as $line => $eachDomain) {
                $eachDomain = strtolower($eachDomain);
                if (!empty($eachDomain)) {
                    if (isset($domainTmp[$eachDomain])) {
                        print($eachDomain . ' duplicate in line ' . $line . PHP_EOL);
                        $dupCount++;
                    } else {
                        $domainTmp[$eachDomain] = $line;
                    }
                } else {
                    print('Error: empty domain in line ' . $line . PHP_EOL);
                }
                $totalCount++;
            }
            print('Total ' . $totalCount . ' domains checked' . PHP_EOL);
            print('Found ' . $dupCount . ' domain duplicates' . PHP_EOL);
            $checkLabel = ($dupCount == 0) ? 'PASSED' : 'FAILED!';

            print(' ===========================' . PHP_EOL);
            print('| DUPLICATES CHECK ' . $checkLabel . '  |' . PHP_EOL);
            print('============================' . PHP_EOL);
        } else {
            print('Error: empty domains list loaded from ' . $this->domainsFile . PHP_EOL);
        }
    }
    /**
     * Syncs local domains list with another or remote list.
     *
     * @param string $fileUrl The URL of the file to fetch the contents from (optional).
     *              
     * @return void
     */
    public function sync($fileUrl = '') {
        $localCount = 0;
        $remoteCount = 0;
        $newCount = 0;
        $newList = '';
        $uniq = array();
        //using local source
        $dataSource = 'domains.txt';
        $localDomains = $this->loadDomainsSource($dataSource);

        if (empty($fileUrl)) {
            $fileUrl = 'newdomains.txt';
        }
        @$rawNewDomains = file_get_contents($fileUrl);
        print('Looking for new domains in ' . $fileUrl . PHP_EOL);

        if (!empty($rawNewDomains)) {
            $newDomains = explode(PHP_EOL, $rawNewDomains);
            $localDomains = array_flip($localDomains);
            $remoteCount = sizeof($newDomains);
            $localCount = sizeof($localDomains);
            print($remoteCount . ' Domains in ' . $fileUrl . PHP_EOL);
            print($localCount . ' Domains in ' . $dataSource . PHP_EOL);
            print(' ===========================' . PHP_EOL);
            foreach ($newDomains as $io => $eachDomain) {
                if (!empty($eachDomain)) {
                    if (!isset($localDomains[$eachDomain])) {
                        if ($this->isDomainValid($eachDomain)) {
                            if (!isset($uniq[$eachDomain])) {
                                $newList .= trim($eachDomain) . PHP_EOL;
                                $uniq[$eachDomain] = 1;
                                $newCount++;
                            }
                        }
                    }
                }
            }
            if ($newCount > 0) {
                print($newList);
            }
            print(' ================================================' . PHP_EOL);
            print('|  CHECK FINISHED: ' . $newCount . ' NEW DOMAINS FOUND |' . PHP_EOL);
            print('=================================================' . PHP_EOL);
        } else {
            print(' ===========================' . PHP_EOL);
            print('|  CHECK FAILED: EMPTY LIST |' . PHP_EOL);
            print('============================' . PHP_EOL);
        }
        //print($rawNewDomains);
    }

    /**
     * Extracts domain name from shitty URLs
     * 
     * @param string $address
     * 
     * @return string
     */
    protected function getHost($address) {
        $result = '';
        $address = trim($address);
        $parseUrl = parse_url($address);

        if (isset($parseUrl['host'])) {
            $result = $parseUrl['host'];
        } else {
            if (isset($parseUrl['path'])) {
                $exploded = explode('/', $parseUrl['path']);
                $result = $exploded[0];
            }
        }
        return ($result);
    }

    /**
     * Basic domain names validator
     * 
     * @return bool
     */
    protected function isDomainValid($domainName) {
        return (preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $domainName) //valid chars check
            && preg_match("/^.{1,253}$/", $domainName) //overall length check
            && preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $domainName));
    }

    /**
     * Runs all checks for all datasources
     * 
     * @return void
     */
    public function runAllChecks() {
        $this->uniqueCheck();
        $this->uniqueCheck(true);
        $this->validityCheck();
        $this->validityCheck(true);
    }

    /**
     * Checks for domains validity
     * 
     * @return void
     */
    public function validityCheck($useLocalList = false) {
        require_once('api.punycode.php'); //yep, we need this ;/
        $errCounter = 0;
        $totalCounter = 0;
        if ($useLocalList) {
            $dataSource = 'domains.txt';
            $listToCheck = $this->loadDomainsSource($dataSource);
        } else {
            $dataSource = $this->domainsFile;
            $listToCheck = $this->domainsList;
        }

        if (!empty($this->domainsList)) {
            print('Checking Domains validity in ' . $dataSource . PHP_EOL);
            print(' ==================' . PHP_EOL);

            foreach ($listToCheck as $line => $eachDomain) {
                $cleanDomain = $this->getHost($eachDomain);
		$cleanDomain = preg_replace('/^www\./', '', $cleanDomain);
                $cleanDomain = Punycode::encodeHostName($cleanDomain);
                if ($cleanDomain) {
                    if ($eachDomain != $cleanDomain) {
                        print_r('Error: domain ' . $eachDomain . ' must be like ' . $cleanDomain . ' at line ' . $line . PHP_EOL);
                        $errCounter++;
                    } else {
                        if (!$this->isDomainValid($eachDomain)) {
                            print_r('Error: domain ' . $eachDomain . ' is not valid in line ' . $line . PHP_EOL);
                            $errCounter++;
                        }
                    }
                } else {
                    print_r('Error: domain ' . $eachDomain . ' is corrupted in line ' . $line . PHP_EOL);
                    $errCounter++;
                }
                $totalCounter++;
            }
        } else {
            print('Error: empty domains list loaded from ' . $this->domainsFile . PHP_EOL);
            $errCounter++;
        }

        print('Total ' . $totalCounter . ' domains checked' . PHP_EOL);
        print('Found ' . $errCounter . ' domain errors' . PHP_EOL);
        $checkLabel = ($errCounter == 0) ? 'PASSED' : 'FAILED!';
        print(' =========================' . PHP_EOL);
        print('| VALIDITY CHECK ' . $checkLabel . '  |' . PHP_EOL);
        print(' =========================' . PHP_EOL);
    }
}
