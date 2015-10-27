<?php
namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

/**
 * UserController
 *
 * @author
 *
 * @version
 *
 */
class UserController extends AbstractActionController
{
    public $entityClass = '\Entity\StUser';
    public $controller = 'user';
    public $form = '\Admin\Form\UserForm';

    public function indexAction()
    {
        $config = new \Admin\Config\Config();
        
        $renderValue = array(
            array(
                'attributes' => array(
                    'data-mdata' => 'username',
                ),
                'value' => 'Username'
            ),
            array(
                'attributes' => array(
                    'data-mdata' => 'datetimeCreated',
                    'data-fnrender' => 'renderDatetime',
                ),
                'value' => 'Date Registered'
            ),
            array(
                'attributes' => array(
                    'data-mdata' => 'email',
                ),
                'value' => 'Email'
            ),
            array(
                'attributes' => array(
                    'data-mdata' => 'role',
                    'data-fnrender' => 'renderSelect',
                ),
                'value' => 'Role'
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
            'id' => array(
                'type' => 'number',
            ),
            'username' => array(),
            'datetimeCreated' => array(
                'type' => 'date',
                'display' => 'Date Registered'
            ),
            'email' => array(),
            'role' => array(
                'type' => 'checkbox',
                'values' => $config->getConfig('admin_roles'),
            ),
            'status' => array(
                'type' => 'checkbox',
                'values' => $config->getConfig('status'),
            )
        );
        
        $viewModel = new ViewModel();
        $viewModel->setVariables(array(
            'filter' => $filter,
            'render_value' => $renderValue,
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
                $row['option_role'] = $config->getConfig('admin_roles');
                $row['option_status'] = $config->getConfig('status');
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
                    $data['password'] = \ST\Text::generatePassword($data['password'], _ST_TIME_NOW);
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
            unset($form_data['password']);
            $form->setData($form_data);
            if($this->getRequest()->isPost()){
                 
                $data = $this->params()->fromPost();
                if($data['password'] == ''){
                    unset($data['password']);
                    $form->getInputFilter()->remove('password');
                    $form->getInputFilter()->remove('confirm_password');
                }
                $form->setData($data);
                if($form->isValid()){
                    try{
                        if(isset($data['password']))
                        {
                            $data['password'] = \ST\Text::generatePassword($data['password'], $object->getDatetimeCreated()->getTimestamp());
                        }
                        $id = $this->UtilityPlugin()->bindingObject($this->entityClass, $data, 'edit');
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
    
    public function statusAction()
    {
        $result = $this->AdminPlugin()->setAttribute($this->entityClass);
        return new JsonModel($result);
    }
    
    public function roleAction()
    {
        $result = $this->AdminPlugin()->setAttribute($this->entityClass);
        return new JsonModel($result);
    }
    
    public function removeAction() {
        $result = $this->AdminPlugin()->remove($this->entityClass);
        return new JsonModel($result);
    }
}