<?php
namespace Categoria\Service;

use Base\Service\AbstractService;
use Doctrine\ORM\EntityManager;

class CategoriaService extends AbstractService
{
    public function __construct(EntityManager $em)
    {
        $this->strEntity = 'Categoria\Entity\Category';
        parent::__construct($em);
    }

}