<?php
namespace CarnegieLearning\LdapOrmBundle\Ldap;

use CarnegieLearning\LdapOrmBundle\Exception\InvalidLdapBindException;
use CarnegieLearning\LdapOrmBundle\Exception\InvalidLdapConnectionException;
use CarnegieLearning\LdapOrmBundle\Exception\InvalidLdapTlsConnectionException;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Bridge\Monolog\Logger;

/**
 * Class Client
 * @package CarnegieLearning\LdapOrmBundle\Ldap
 */
class Client implements ClientInterface
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
     * @var Core
     */
    private $ldap;

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
     * @throws InvalidConfigurationException
     *
     * @codeCoverageIgnore
     */
    public function __construct(Logger $logger, array $config)
    {
        if(empty($config)) {
            throw new InvalidConfigurationException('The configuration for the LDAP Client can not be empty.');
        }

        $this->setLdap();
        $this->logger           = $logger;
        $this->bindDN     	    = $config['connection']['bind_dn'];
        $this->isActiveDirectory= $config['connection']['is_active_directory'];
        $this->password   	    = $config['connection']['password'];
        $this->passwordType     = $config['connection']['password_type'];
        $this->uri        	    = $config['connection']['uri'];
        $this->useTLS     	    = $config['connection']['use_tls'];
        $this->ldapResource     = false;
    }

    /**
     * Connect to LDAP service
     *
     * @throws InvalidLdapConnectionException, InvalidLdapTlsConnectionException, InvalidLdapBindException
     */
    public function connect()
    {
        // Don't permit multiple connect() calls to run
        if ($this->ldapResource) {
            return $this->ldapResource;
        }

        $this->ldapResource = $this->ldap->connect($this->uri);

        if($this->ldapResource === false) {
            throw new InvalidLdapConnectionException('Unable to enable establish LDAP connection.');
        }

        $this->ldap->setOption($this->ldapResource, LDAP_OPT_PROTOCOL_VERSION, 3);

        // Switch to TLS, if configured
        if ($this->useTLS) {
            if(!$this->ldap->startTls($this->ldapResource)) {
                throw new InvalidLdapTlsConnectionException('Unable to enable TLS for LDAP connection.');
            }

            $this->logger->info('TLS enabled for LDAP connection.');
        }

        if(!$this->ldap->bind($this->ldapResource, $this->bindDN, $this->password)) {
            throw new InvalidLdapBindException('Cannot connect to LDAP server: ' . $this->uri . ' as ' . $this->bindDN . '/"' . $this->password . '".');
        }

        $this->logger->debug('Connected to LDAP server: ' . $this->uri . ' as ' . $this->bindDN . ' .');
    }

    /**
     * @return mixed
     *
     * @codeCoverageIgnore
     */
    public function getLdapResource()
    {
        return $this->ldapResource;
    }

    /**
     * @return bool
     *
     * @codeCoverageIgnore
     */
    public function getIsActiveDirectory()
    {
        return $this->isActiveDirectory;
    }

    /**
     * This is needed to implement UnitTest Mocking
     *
     * @param null $ldapCore
     * @return Core
     */
    public function setLdap($ldapCore = null)
    {
        if(null == $ldapCore) {
            $this->ldap = new Core();
        } else {
            $this->ldap = $ldapCore;
        }

        return $this->ldap;
    }

    /**
     * @param $useTLS
     *
     * @codeCoverageIgnore
     */
    public function setUseTls($useTLS)
    {
        $this->useTLS = $useTLS;
    }
}