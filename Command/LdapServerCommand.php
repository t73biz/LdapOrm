<?php

namespace CarnegieLearning\LdapOrmBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

abstract class LdapServerCommand extends Command implements ContainerAwareInterface
{

    /**
     * @var string The address to bind to.
     */
    protected $address;

    /**
     * @var int The port to listen on.
     */
    protected $port;

    /**
     * @var string The base DN for the LDAP server.
     */
    protected $baseDn;

    /**
     * @var string The path to the LDIF seed file.
     */
    protected $ldif;

    /**
     * @var ContainerInterface|null
     */
    private $container;

    /**
     * @return ContainerInterface
     *
     * @throws \LogicException
     */
    protected function getContainer()
    {
        if (null === $this->container) {
            $application = $this->getApplication();
            if (null === $application) {
                throw new \LogicException('The container cannot be retrieved as the application instance is not yet set.');
            }

            $this->container = $application->getKernel()->getContainer();
        }

        return $this->container;
    }

    /**
     * {@inheritdoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function __construct($address, $port, $baseDn, $ldif)
    {
        $this->address = $address;
        $this->port = $port;
        $this->baseDn = $baseDn;
        $this->ldif = $ldif;

        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    public function isEnabled()
    {
        if (!class_exists('Symfony\Component\Process\Process')) {
            return false;
        }

        return parent::isEnabled();
    }

    /**
     * Determines the name of the lock file for a particular LDAP server process.
     *
     * @param string $address An address/port tuple
     *
     * @return string The filename
     */
    protected function getLockFile($address)
    {
        return sys_get_temp_dir().'/'.strtr($address, '.:', '--').'.pid';
    }

    protected function isOtherServerProcessRunning($address)
    {
        $lockFile = $this->getLockFile($address);

        if (file_exists($lockFile)) {
            return true;
        }

        list($hostname, $port) = explode(':', $address);

        $fp = @fsockopen($hostname, $port, $errno, $errstr, 5);

        if (false !== $fp) {
            fclose($fp);

            return true;
        }

        return false;
    }
}
