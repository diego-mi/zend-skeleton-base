<?php
namespace Base\Controller;

use RuntimeException;
use Base\Controller\AbstractFormController;
use Zend\View\Model\ViewModel;

/**
 * Class AbstractCrudController
 * @package Base\Controller
 */
abstract class AbstractCrudController extends AbstractFormController
{
    const INT_ITENS_PER_PAGE = 10;

    /**
     * Index - Lista Resultados
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $arrForPagination = $this->getParamsForPagination();

        $arrDataPaged = $this->getService()->getRepository()->findBy(
            [],
            [],
            $arrForPagination['intItensPerPage'],
            $arrForPagination['intOffset']
        );

        return new ViewModel(
            [
                'data' => $arrDataPaged,
                'page' => $arrForPagination['intPage'],
                'flashMessenger' => $this->flashMessenger()
            ]
        );
    }

    /**
     * InserirAction
     *
     * @return ViewModel
     */
    public function inserirAction()
    {
        $form = $this->getForm();
        $service = $this->getService();

        try {
            if ($this->checkFormIsValid($form)) {
                $resultOfInsert = $service->save($form->getData());
                if ($resultOfInsert instanceof $this->entity) {
                    $this->flashMessenger()->addSuccessMessage('Cadastrado com sucesso!');

                    return $this->redirect()
                        ->toRoute($this->route, array('controller' => $this->controller, 'action' => 'index'));
                } else {
                    $this->flashMessenger()->addErrorMessage(var_dump($resultOfInsert));
                }
            }
        } catch (\Doctrine\DBAL\ConnectionException $objException) {
            $this->flashMessenger()->addErrorMessage($objException->getMessage());
        }

        return new ViewModel([
            'form' => $form,
            'flashMessenger' => $this->flashMessenger()
        ]);
    }

    /**
     * Edita um registro
     */
    public function editarAction()
    {
        $form = $this->getForm();
        $service = $this->getService();
        $intIdFromRoute = $this->params()->fromRoute('id', 0);
        $entityForEdit = $service->find((int)$intIdFromRoute);

        try {
            if (($entityForEdit) && ($this->checkFormIsValid($form))) {
                $arrDataForEdit = $form->getData();
                $arrDataForEdit['id'] = (int)$intIdFromRoute;

                if ($service->save($arrDataForEdit)) {
                    $this->flashMessenger()->addSuccessMessage('Atualizado com sucesso!');
                } else {
                    $this->flashMessenger()->addErrorMessage('Não foi possivel atualizar! Tente mais tarde.');
                }

                return $this->redirect()->toRoute(
                    $this->route,
                    array('controller' => $this->controller, 'action' => 'editar', 'id' => $intIdFromRoute)
                );
            } elseif ($entityForEdit) {
                $arrForEditForm = $entityForEdit->toArray();
                $form->setData($arrForEditForm);
            }
        } catch (\RuntimeException $objException) {
            $this->flashMessenger()->addErrorMessage($objException->getMessage());
        } catch (\Exception $objException) {
            $this->flashMessenger()->addErrorMessage($objException->getMessage());
        }

        #$this->flashMessenger()->clearMessages();

        return new ViewModel([
            'form' => $form,
            'id' => $intIdFromRoute,
            'flashMessenger' => $this->flashMessenger()
        ]);
    }

    /**
     * Exclui um registro
     *
     * @return \Zend\Http\Response
     */
    public function excluirAction()
    {
        $service = $this->getService();
        $id = $this->params()->fromRoute('id', 0);

        if ($service->remove(array('id' => $id))) {
            $this->flashMessenger()->addSuccessMessage('Resistro deletado com sucesso!');
        } else {
            $this->flashMessenger()->addErrorMessage('Não foi possivel deletar o registro!');
        }

        return $this->redirect()->toRoute($this->route, array('controller' => $this->controller));
    }

    /**
     * Metodo para recuperar dados para realizar a paginacao
     *
     * @return array
     */
    public function getParamsForPagination()
    {
        $arrParamsForCreatePagination = $this->getRequest()->isPost()
            ? $this->getRequest()->getPost()
            : $this->params()->fromRoute();

        $intPage = $this->getIntPage($arrParamsForCreatePagination);
        $intItensPerPage = $this->getIntItensPerPage($arrParamsForCreatePagination);
        $intOffset = $intPage * $intItensPerPage;

        return [
            'intPage' => $intPage,
            'intItensPerPage' => $intItensPerPage,
            'intOffset' => $intOffset
        ];
    }

    /**
     * Atraves de um array, recupere o valor de intPage ou retorne 0
     *
     * @param $arrParams
     *
     * @return int
     */
    public function getIntPage($arrParams)
    {
        return (isset($arrParams['intPage']) && ($arrParams['intPage'] > 0))
            ? (int)($arrParams['intPage'] - 1)
            : 0;
    }


    /**
     * Atraves de um array, recupere o valor de intItensPerPage ou retorne 0
     *
     * @param $arrParams
     *
     * @return int
     */
    public function getIntItensPerPage($arrParams)
    {
        return (isset($arrParams['intItensPerPage']) && ($arrParams['intItensPerPage'] > 0))
            ? (int)$arrParams['intItensPerPage']
            : self::INT_ITENS_PER_PAGE;
    }
}
