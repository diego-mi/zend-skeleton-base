<?php
namespace Base\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\Entity;
use Zend\Stdlib\Hydrator\ClassMethods;

/**
 * Class AbstractService
 * @package Base\Service
 */
abstract class AbstractService
{
    protected $em;
    public $strEntity;

    /**
     * AbstractService constructor.
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    /**
     * @param array $data
     * @return bool|\Doctrine\Common\Proxy\Proxy|\Exception|null|object
     */
    public function save(Array $data = array())
    {
        try {
            if (isset($data['id'])) {
                $entity = $this->em->getReference($this->strEntity, $data['id']);

                $hydrator = new ClassMethods();
                $hydrator->hydrate($data, $entity);

            } else {
                $entity = new $this->strEntity($data);
            }

            $this->em->persist($entity);
            $this->em->flush();

            return $entity;
        } catch (\Doctrine\DBAL\ConnectionException $objError) {
            return $objError;
        }
    }

    /**
     * @param array $data
     * @return null|object
     */
    public function remove(Array $data = array())
    {
        try {
            $entity = $this->em->getRepository($this->strEntity)->findOneBy($data);

            if ($entity) {
                $this->em->remove($entity);
                $this->em->flush();

                return $entity;
            } else {
                throw new \RuntimeException(
                    'Não foi encontrado registro para remover.'
                );
            }
        } catch (\Exception $objError) {
            return $objError;
        }
    }

    /**
     * Metodo responsavel por recuperar um registro no banco de dados atraves da chave primaria
     *
     * @param Integer $intId
     *
     * @return Entity
     */
    public function find($intId)
    {
        return $this->em->getRepository($this->strEntity)->find($intId);
    }

    /**
     * @param string $strEntityNamespace
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getRepository($strEntityNamespace = null)
    {
        if (is_null($strEntityNamespace)) {
            $strEntityNamespace = $this->strEntity;
        }
        return $this->em->getRepository($strEntityNamespace);
    }
}
