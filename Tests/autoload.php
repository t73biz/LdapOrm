<?php

use Doctrine\Common\Annotations\AnnotationRegistry;
use Composer\Autoload\ClassLoader;

/**
 * @var ClassLoader $loader
 */
$loader = require __DIR__.'/../vendor/autoload.php';

AnnotationRegistry::registerLoader(array($loader, 'loadClass'));
AnnotationRegistry::registerFile(__DIR__ . '/../Annotation/Ldap/Mapping/LdapAnnotations.php');

return $loader;