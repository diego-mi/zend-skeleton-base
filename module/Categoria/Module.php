<?php
namespace Categoria;

use Categoria\Service\CategoriaService;
use Categoria\Form\CategoryForm;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

/**
 * Class Module
 * @package Categoria
 */
class Module
{
    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    /**
     * @return mixed
     */
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    /**
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    /**
     * @return array
     */
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Categoria\Service\CategoriaService' => function ($em) {
                    return new CategoriaService($em->get('Doctrine\ORM\EntityManager'));
                },
                'Categoria\Form\CategoryForm' => function ($em) {
                    return new CategoryForm($em->get('Doctrine\ORM\EntityManager'));
                }
            )
        );
    }


}
