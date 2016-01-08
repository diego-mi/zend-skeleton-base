<?php
namespace Base;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

/**
 * Class Module
 * @package Base
 */
class Module
{
    /**
     * @param MvcEvent $e
     */
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $myServiceManager = $e->getApplication()->getServiceManager();

        $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
        $myService = $myServiceManager->get('Categoria\Service\CategoriaService');
        $viewModel->someVar = $myService->getRepository()->findAll();

        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

//    public function onBootstrap($e) {
//
//        $serviceManager = $e->getApplication()->getServiceManager();
//        $viewModel = $e->getApplication()->getMvcEvent()->getViewModel();
//
//        $myService = $serviceManager->get('MyModule\Service\MyService');
//
//        $viewModel->someVar = $myService->getSomeValue();
//
//    }

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
                }
            )
        );
    }
}
