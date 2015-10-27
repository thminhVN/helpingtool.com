<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

/**
 * LanguageController
 *
 * @author
 *
 * @version
 *
 */
class LanguageController extends AbstractActionController
{
    public $entityClass = '\Entity\StLanguage';
    public $controller = 'language';
    public $form = '\Admin\Form\LanguageForm';
    public function indexAction()
    {
        $config = new \Admin\Config\Config();
        
        $renderValue = array(
            array(
                'attributes' => array(
                    'data-mdata' => 'name',
                ),
                'value' => 'Name'
            ),
            array(
                'attributes' => array(
                    'data-mdata' => 'code',
                ),
                'value' => 'Code'
            ),
            array(
                'attributes' => array(
                    'data-mdata' => 'locale',
                ),
                'value' => 'Locale'
            ),
            array(
                'attributes' => array(
                    'data-mdata' => 'dateFormat',
                ),
                'value' => 'Date Format'
            ),
            array(
                'attributes' => array(
                    'data-mdata' => 'timeFormat',
                ),
                'value' => 'Time Format'
            ),
            array(
                'attributes' => array(
                    'data-mdata' => 'status',
                    'data-fnrender' => 'renderSelect',
                ),
                'value' => 'Status'
            ),
            array(
                'attributes' => array(
                    'data-fnrender' => 'renderSetting',
                    'data-orderable' => 'false'
                ),
                'value' => 'Setting'
            )
        );
        $filter = array(
//             'id' => array(
//                 'type' => 'number',
//             ),
            'code' => array(),
            'name' => array(),
//             'status' => array(
//                 'type' => 'checkbox',
//                 'values' => $config->getConfig('status'),
//             ),
//             'language' => $this->UtilityPlugin()->getFilterLanguages(),
        );

        $viewModel = new ViewModel();
        $viewModel->setVariables(array(
            'render_value' => $renderValue,
            'filter' => $filter,
        ));
        $viewModel->setTemplate('partials/list');
        return $viewModel;
    }

    public function pagingAction()
    {
        $s_params = $this->AdminPlugin()->prepareParams();
        $em = $this->UtilityPlugin()->getEm();
        $data = $em->getRepository($this->entityClass)->getBy($s_params);
        $total = count($em->getRepository($this->entityClass)->getBy(null));
        $count = count($em->getRepository($this->entityClass)->getBy($s_params));
        // STRUCTURE OUTPUT
        $output = array(
                "sEcho" => intval($this->params()->fromQuery('sEcho',0)),
                "iTotalRecords" => $total,
                "iTotalDisplayRecords" => $count,
                "aaData" => array()
        );
    
        if($count > 0)
        {
            $config = new \Admin\Config\Config();
            foreach($data as $key => $object)
            {
                $entityArray = $this->UtilityPlugin()->doctrine2Array($object);
                $row = $entityArray;
                $row['option_status'] = $config->getConfig('language_status');
                $output['aaData'][] = $row;
            }   
        }
        return new JsonModel($output);
    } 
    
    public function addAction(){

        $em = $this->UtilityPlugin()->getEm();
        $errors = '';
        $success = $this->params()->fromQuery('success',false);
        $form = new $this->form($em);
        if($this->getRequest()->isPost()){
        	$data = $this->params()->fromPost();
            $form->setData($data);
            if($form->isValid()) {
                try{
                    $id = $this->UtilityPlugin()->bindingObject($this->entityClass, $data);
                    return $this->redirect()->toRoute('admin/default',
                        array(
                            'controller' => $this->controller,
                            'action' => 'edit', 'id' =>$id),
                            array(
                                'query' => array(
                                    'success' => true
                                )
                            )
                        );
                } catch(\Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }

        $view = new ViewModel();
        $view->setTemplate('form/form')->setVariables(array(
            'form' => $form,
            'error' => @$error,
        ));
        return $view;
    }

    public function editAction(){
        $id = $this->params()->fromRoute('id', 0);
        $view = new ViewModel();

        $now = time();
        $em = $this->UtilityPlugin()->getEm();
        $errors = '';
        $success = $this->params()->fromQuery('success', false);

        $object = $em->getRepository($this->entityClass)->findOneBy(array('id' => $id));
        if($object) {
            $form = new $this->form($em);
            $form_data = $this->UtilityPlugin()->getFormData($form, $object);
            $form->setData($form_data);
            if($this->getRequest()->isPost()){
                $data = $this->params()->fromPost();
                $form->setData($data);
                if($form->isValid()){
                    try{
                        $id = $this->UtilityPlugin()->bindingObject($this->entityClass, $data);
                        return $this->redirect()->toRoute('admin/default',array('controller' => $this->controller, 'action' => 'edit', 'id' => $id), array('query' => array('success' => true)));
                    }catch(\Exception $e){
                        $message = $e->getMessage();
                    }
                }
            }
            
            $view->setTemplate('form/form')->setVariables(array(
                'form' => $form,
                'success' => $success,
                'message' => @$message
            ));
            return $view;
        } else {
            $view->setTemplate('partials/noobject')->setVariables(array());
            return $view;
        }
    }
    
    private function _bindingForm2Db()
    {
        
    }
    
    public function statusAction()
    {
        $result = $this->AdminPlugin()->setAttribute($this->entityClass);
        return new JsonModel($result);
    }
    
    public function removeAction() {
        $result = $this->AdminPlugin()->remove($this->entityClass);
        return new JsonModel($result);
    }
}