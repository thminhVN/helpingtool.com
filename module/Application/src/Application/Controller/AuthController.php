<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * Use for all authentication functions 
 *
 * @version 1.0
 * @author Minh Tran <minh@starseed.fr>
 */
class AuthController extends AbstractActionController
{

    protected $storage;
    protected $authservice;
    
    /**
     * Return login form or authenticate user 
     * 
     * @return \Zend\Http\Response|\Zend\View\Model\ViewModel|redirect to another page
     */
    public function loginAction()
    {
        $this->layout('layout/auth');
        if($this->getRequest()->isPost())
        {
            $username = $this->params()->fromPost('login_username', '');
            $password = $this->params()->fromPost('login_password', '');
            $rememberme = $this->params()->fromPost('rememberme', '');
            
            $authService = $this->_getAuthService();
            $adapter = $authService->getAdapter();
            $adapter->setIdentityValue($username);
            $adapter->setCredentialValue($password);
            $authResult = $authService->authenticate();

            if ($authResult->isValid()) {
                
                //get doctrine entity StUser
                $identity = $authResult->getIdentity();
                
                if($rememberme == 1){ //set remember me
                    $this->_getAuthStorage()->setRememberMe($rememberme);
                    
                    //set storage session
                    $authService->setStorage($this->_getAuthStorage());
                }

                //save id field in current session
                $authService->getStorage()->write($identity);

                //get return url
                $currentLang = $this->UtilityPlugin()->getCurrentLanguage();
                $returnUrl = $this->params()->fromQuery('return_url', $this->url()->fromRoute($currentLang->getCode().'_home'));
                
                return $this->redirect()->toUrl($returnUrl);
            }

            return new ViewModel(array(
                'error' => 'Username or password wrong',
            ));
        }
        return new ViewModel();
    }
    
    /**
     * Logout and clear all authenticate session
     * @return \Zend\Http\Response
     */
    public function logoutAction()
    {
        $this->_clearAuthStorage();
        return $this->redirect()->toRoute($this->params()->fromRoute('lang').'_home');
    }
    
    private function _getAuthService()
    {
        if (! $this->authservice) {
            $this->authservice = $this->getServiceLocator()->get('Zend\Authentication\AuthenticationService');
        }
        return $this->authservice;
    }

    private function _getAuthStorage()
    {
        if (! $this->storage) {
            $this->storage = $this->getServiceLocator()->get('StAuthStorage');
        }
        return $this->storage;
    }
    
    private function _clearAuthStorage()
    {
        $this->_getAuthService()->clearIdentity();
        $this->_getAuthStorage()->forgetMe();
    }
}