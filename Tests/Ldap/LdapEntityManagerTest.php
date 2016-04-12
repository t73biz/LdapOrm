<?php
namespace CarnegieLearning\LdapOrmBundle\Tests\Ldap;

use CarnegieLearning\LdapOrmBundle\Ldap\Filter\LdapFilter;
use CarnegieLearning\LdapOrmBundle\Ldap\LdapEntityManager;
use CarnegieLearning\LdapOrmBundle\Tests\Fixtures\CheckMustEntity;
use CarnegieLearning\LdapOrmBundle\Tests\Fixtures\CheckMustGetterEntity;
use CarnegieLearning\LdapOrmBundle\Tests\Fixtures\GetMetaRepositoryEntity;
use CarnegieLearning\LdapOrmBundle\Tests\Fixtures\GetRepositoryEntity;
use CarnegieLearning\LdapOrmBundle\Tests\Fixtures\RetrieveEntity;
use CarnegieLearning\LdapOrmBundle\Tests\Fixtures\RetrieveThrowsExceptionEntity;
use CarnegieLearning\LdapOrmBundle\Tests\SymfonyKernel;

class LdapEntityManagerTest extends \PHPUnit_Framework_TestCase
{
    use SymfonyKernel;
    
    public function testCheckMust_ReturnsTrue()
    {
        $checkMustEntity = new CheckMustEntity();
        $checkMustEntity->setName('foo');
        $checkMustEntity->setCn('FooName');
        $ldapEntityManager = $this->container->get('carnegie_learning_ldap_orm.entity_manager');
        $check = $ldapEntityManager->checkMust($checkMustEntity);

        $this->assertTrue($check);
    }

    /**
     * @expectedException \CarnegieLearning\LdapOrmBundle\Exception\MissingMustAttributeException
     */
    public function testCheckMust_ThrowsException()
    {
        $checkMustEntity = new CheckMustEntity();
        $ldapEntityManager = $this->container->get('carnegie_learning_ldap_orm.entity_manager');
        $ldapEntityManager->checkMust($checkMustEntity);
    }

    /**
     * @expectedException \CarnegieLearning\LdapOrmBundle\Exception\MissingGetterException
     */
    public function testCheckMust_ThrowsGetterException()
    {
        $checkMustGetterEntity = new CheckMustGetterEntity();
        $ldapEntityManager = $this->container->get('carnegie_learning_ldap_orm.entity_manager');
        $ldapEntityManager->checkMust($checkMustGetterEntity);
    }

    public function testGetRepository_ReturnsRepository()
    {
        $getRepositoryEntity = new GetRepositoryEntity();
        $ldapEntityManager = $this->container->get('carnegie_learning_ldap_orm.entity_manager');
        $repository = $ldapEntityManager->getRepository($getRepositoryEntity);

        $this->assertInstanceOf(\CarnegieLearning\LdapOrmBundle\Repository\Repository::class, $repository);
    }

    public function testGetRepository_ReturnsMetaRepository()
    {
        $getRepositoryEntity = new GetMetaRepositoryEntity();
        $ldapEntityManager = $this->container->get('carnegie_learning_ldap_orm.entity_manager');
        $repository = $ldapEntityManager->getRepository($getRepositoryEntity);

        $this->assertInstanceOf(\CarnegieLearning\LdapOrmBundle\Tests\Fixtures\GetMetaRepository::class, $repository);
    }

    /**
     * @expectedException \CarnegieLearning\LdapOrmBundle\Exception\MissingSearchDnException
     */
    public function testRetrieve_ThrowsMissingSearchException()
    {
        $retrieveEntity = new RetrieveThrowsExceptionEntity();
        $ldapEntityManager = $this->container->get('carnegie_learning_ldap_orm.entity_manager');
        $ldapEntityManager->retrieve(get_class($retrieveEntity));
    }

    public function testRetrieve_CheckOnlyReturnsTrue()
    {
        $retrieveEntity = new RetrieveEntity();
        $ldapEntityManager = $this->container->get('carnegie_learning_ldap_orm.entity_manager');
        $check = $ldapEntityManager->retrieve(get_class($retrieveEntity), ['checkOnly' => true]);

        $this->assertTrue($check);
    }

    public function testRetrieve_FilterOptionReturnsEntities()
    {
        $retrieveEntity = new RetrieveEntity();
        $filterArray = [
            '&' => [
                'uid' => 'abarnes',
                'roomNumber' => '2290'
            ]
        ];
        $ldapEntityManager = $this->container->get('carnegie_learning_ldap_orm.entity_manager');

        /**
         * @var \CarnegieLearning\LdapOrmBundle\Tests\Fixtures\RetrieveEntity $entity
         */
        $entity = $ldapEntityManager->retrieve(get_class($retrieveEntity), ['filter' => new LdapFilter($filterArray)]);

        $this->assertInstanceOf(\CarnegieLearning\LdapOrmBundle\Tests\Fixtures\RetrieveEntity::class, $entity);
        $this->assertEquals('Anne-Louise Barnes', $entity->getCn());
    }
}