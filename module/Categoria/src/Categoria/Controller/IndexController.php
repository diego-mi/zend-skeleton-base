<?php
namespace Categoria\Controller;

use Base\Controller\AbstractController;

class IndexController extends AbstractController
{

    function __construct()
    {
        $this->strForm = 'Categoria\Form\CategoryForm';
        $this->controller = 'categoria';
        $this->route = 'categoria/default';
        $this->strService = 'Categoria\Service\CategoriaService';
        $this->entity = 'Categoria\Entity\Category';
    }
}