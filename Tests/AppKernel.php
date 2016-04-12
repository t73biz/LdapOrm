<?php
namespace CarnegieLearning\LdapOrmBundle\Tests;

use CarnegieLearning\LdapOrmBundle\CarnegieLearningLdapOrmBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Bundle\MonologBundle\MonologBundle;

class AppKernel extends Kernel
{
    /**
     * Returns an array of bundles to register.
     *
     * @return BundleInterface[] An array of bundle instances.
     */
    public function registerBundles()
    {
        $bundles = [
            new CarnegieLearningLdapOrmBundle(),
            new MonologBundle(),
        ];

        return $bundles;
    }

    /**
     * Loads the container configuration.
     *
     * @param LoaderInterface $loader A LoaderInterface instance
     */
    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/Fixtures/config_'.$this->getEnvironment().'.yml');
    }
}