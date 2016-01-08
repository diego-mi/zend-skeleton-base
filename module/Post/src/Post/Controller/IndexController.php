<?php
namespace Post\Controller;

use Base\Controller\AbstractController;

class IndexController extends AbstractController
{
    function __construct()
    {
        $this->strForm = 'Post\Form\PostForm';
        $this->controller = 'post';
        $this->route = 'post/default';
        $this->entity = 'Post\Entity\Post';
        $this->strService = 'Post\Service\PostService';
    }
}
