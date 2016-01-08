<?php
namespace Post\Controller;

use Base\Controller\AbstractCrudController;

/**
 * Class IndexController
 * @package Post\Controller
 */
class IndexController extends AbstractCrudController
{
    /**
     * IndexController constructor.
     */
    public function __construct()
    {
        $this->strForm = 'Post\Form\PostForm';
        $this->controller = 'post';
        $this->route = 'post/default';
        $this->entity = 'Post\Entity\Post';
        $this->strService = 'Post\Service\PostService';
    }
}
