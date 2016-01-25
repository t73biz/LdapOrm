<?php

namespace CarnegieLearning\LdapOrmBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

class CarnegieLearningLdapOrmExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        // Test for configuration and set the resource location for connecting to the LDAP server
        if (!isset($config['connection']['uri'])) {
            throw new \InvalidArgumentException(
                'The "uri" argument must be set for the ldap client.'
            );
        }
        $container->setParameter('carnegie_learning_ldap_orm.connection.uri', $config['connection']['uri']);

        // Test for configuration and set the user for connecting to the LDAP server
        if (!isset($config['connection']['bind_dn'])) {
            throw new \InvalidArgumentException(
                'The "bind_dn" argument must be set for the ldap client.'
            );
        }
        $container->setParameter('carnegie_learning_ldap_orm.connection.bind_dn', $config['connection']['bind_dn']);

        // Test for configuration and set the password for connecting to the LDAP server
        if (!isset($config['connection']['password'])) {
            throw new \InvalidArgumentException(
                'The "password" argument must be set for the ldap client.'
            );
        }
        $container->setParameter('carnegie_learning_ldap_orm.connection.password', $config['connection']['password']);

        $container->setParameter('carnegie_learning_ldap_orm.connection.password_type', $config['connection']['password_type']);
        $container->setParameter('carnegie_learning_ldap_orm.connection.use_tls', $config['connection']['use_tls']);

        $isActiveDirectory = false;
        if(isset($config['connection']['is_active_directory'])) {
            $isActiveDirectory = true;
        }

        $container->setParameter('carnegie_learning_ldap_orm.connection.is_active_directory', $isActiveDirectory);
    }
}
