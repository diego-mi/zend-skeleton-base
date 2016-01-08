<?php
namespace Base\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

/**
 * Class AbstractController
 * @package Base\Controller
 */
abstract class AbstractController extends AbstractActionController
{
    protected $controller;
    protected $route;
    protected $strService;

    /**
     * Inicializar Service
     *
     * @param String $strServiceNamespace Namespace do service a ser carregado
     *
     * @return \Base\Service\AbstractService
     */
    public function getService($strServiceNamespace = null)
    {
        if (is_null($strServiceNamespace)) {
            $strServiceNamespace = $this->strService;
        }

        try {
            $service = $this->getServiceLocator()->get($strServiceNamespace);
        } catch (ServiceNotFoundException $objException) {
            echo(
                'Necessário um Service valido.\n
                Não foi possivel instanciar o service "' . $strServiceNamespace . '".\n
                Erro: ' . $objException->getMessage()
            );
            die();
        }

        return $service;
    }
}
