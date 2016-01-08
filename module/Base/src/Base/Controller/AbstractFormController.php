<?php
namespace Base\Controller;

use Zend\Form\Form;
use Base\Controller\AbstractController;

/**
 * Class AbstractFormController
 * @package Base\Controller
 */
abstract class AbstractFormController extends AbstractController
{
    protected $strForm;

    /**
     * Inicializar formulario
     *
     * @param string $strFormNamespace Namespaces do formulario a inicializar
     *
     * @return \Zend\Form\Form
     */
    public function getForm($strFormNamespace = null)
    {
        if (is_null($strFormNamespace)) {
            $strFormNamespace = $this->strForm;
        }

        $form = $this->getServiceLocator()->get($strFormNamespace);

        if (is_null($form)) {
            echo(
                'Necessário um Form valido.
                Não foi possivel instanciar o form "' . $strFormNamespace . '"'
            );
            die();
        }

        return $form;
    }

    /**
     * Metodo para validar o isPost e isValid
     *
     * @param Form $form Formulario a ser validado
     *
     * @return Form
     */
    public function checkFormIsValid(Form $form)
    {
        $objRequest = $this->getRequest();

        if ($objRequest->isPost()) {
            $form->setData($objRequest->getPost());
            if ($form->isValid()) {
                return true;
            }
        }

        return false;
    }
}
