<?php

namespace CarnegieLearning\LdapOrmBundle\Ldap;

use Symfony\Bridge\Monolog\Logger;

/**
 * Class Client
 * @package CarnegieLearning\LdapOrmBundle\Ldap
 */
class Client
{
    /**
     * @var string
     */
    private $bindDN;

    /**
     * @var bool
     */
    private $isActiveDirectory;

    /**
     * @var Resource
     */
    private $ldapResource;

    /**
     * @var Logger
     */
    private $logger;

    /**
     * @var string
     */
    private $password;

    /**
     * @var string
     */
    private $passwordType;

    /**
     * @var string
     */
    private $uri;

    /**
     * @var bool
     */
    private $useTLS;

    /**
     * Client constructor.
     * @param Logger $logger
     * @param array $config
     * @throws \Exception
     */
    public function __construct(Logger $logger, array $config)
    {
        if(empty($config)) {
            throw new \Exception('The configuration for the LDAP Client can not be empty.');
        }

        $this->logger           = $logger;
        $this->bindDN     	    = $config['connection']['bind_dn'];
        $this->isActiveDirectory= $config['connection']['is_active_directory'];
        $this->password   	    = $config['connection']['password'];
        $this->passwordType     = $config['connection']['password_type'];
        $this->uri        	    = $config['connection']['uri'];
        $this->useTLS     	    = $config['connection']['use_tls'];
        $this->ldapResource     = false;
        $this->connect();
    }

    /**
     * Connect to LDAP service
     * @codeCoverageIgnore
     * @throws /Exception
     */
    public function connect()
    {
        // Don't permit multiple connect() calls to run
        if ($this->ldapResource) {
            return $this->ldapResource;
        }

        $this->ldapResource = ldap_connect($this->uri);

        if($this->ldapResource === false) {
            throw new \Exception('Unable to enable establish LDAP connection.');
        }

        ldap_set_option($this->ldapResource, LDAP_OPT_PROTOCOL_VERSION, 3);

        // Switch to TLS, if configured
        if ($this->useTLS) {
            try {
                ldap_start_tls($this->ldapResource);
            } catch(\Exception $e) {
                throw new \Exception('Unable to enable TLS for LDAP connection.');
            }

            $this->logger->info('TLS enabled for LDAP connection.');
        }

        try {
            ldap_bind($this->ldapResource, $this->bindDN, $this->password);
        } catch(\Exception $e) {
            throw new \Exception('Cannot connect to LDAP server: ' . $this->uri . ' as ' . $this->bindDN . '/"' . $this->password . '".');
        }

        $this->logger->debug('Connected to LDAP server: ' . $this->uri . ' as ' . $this->bindDN . ' .');
    }

    /**
     * @codeCoverageIgnore
     * @return Resource
     */
    public function getLdapResource()
    {
        return $this->ldapResource;
    }

    /**
     * @codeCoverageIgnore
     * @return bool
     */
    public function getIsActiveDirectory()
    {
        return $this->isActiveDirectory;
    }

}