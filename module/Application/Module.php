<?php
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Session\Container;
use Zend\Session\SessionManager;
use Zend\View\Model\ViewModel;
class Module
{

    public function onBootstrap(MvcEvent $e)
    {
        if(php_sapi_name() == 'cli' && empty($_SERVER['REMOTE_ADDR'])) {
            return true;
        }
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
//         $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'), 100);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onDispatchError'), 100);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'onDispatch'), 100);
//         $eventManager->attach(MvcEvent::EVENT_RENDER, array($this, 'onRender'), 100);
//         $eventManager->attach(MvcEvent::EVENT_RENDER_ERROR, array($this, 'onRenderError'), 100);
        $eventManager->attach(MvcEvent::EVENT_FINISH, array($this, 'onFinish'), 100);

        if(SERVER_STATUS == 500){
            $sharedEvent->attach(__NAMESPACE__, MvcEvent::EVENT_DISPATCH, array($this, 'maintenanceMode'), 101);
        }

    }

    public function getConfig()
    {
        $configs = array();
        foreach(glob(__DIR__."/config/*.php") as $file) {
            $content = include $file;
            $configs = array_merge_recursive($configs, $content);
        }
        return $configs;
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    // config view helper with parameters
    public function getViewHelperConfig()
    {
        return array(
            'factories' => array(
                'routeInfo' => function($sm) {
                    $routeMatch = $sm->getServiceLocator()->get('application')->getMvcEvent()->getRouteMatch();
                    return new View\Helper\RouteInfo($routeMatch);
                }
            ),
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Zend\Authentication\AuthenticationService' => function($serviceManager) {
                    return $serviceManager->get('doctrine.authenticationservice.orm_default');
                },
                'StAuthStorage' => function($sm){
                    return new \Application\Model\MyAuthStorage('auth_user');
                },
                'StAcl' => function($sm){
                    return new \Zend\Permissions\Acl\Acl();
                }
            )
        );
    }

    public function onRoute(MvcEvent $e){

    }

    public function onDispatchError(MvcEvent $e)
    {
        if(_ST_APP_ENV != 'development') {
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $viewModel->setTemplate('error/front-end/404');
            $e->setViewModel($viewModel);
            $e->stopPropagation();
        }
    }
    
    public function onRender(MvcEvent $e)
    {
    }
    
    public function onFinish(MvcEvent $e)
    {
        if(_ST_APP_ENV != 'development') {
            $response = $e->getResponse();
            $response->setContent(\ST\Text::compressHtml($response->getBody()));
        }
    }
    
    public function onDispatch(MvcEvent $e)
    {
        $this->setTranslate($e);
    }
    
    public function onRenderError(MvcEvent $e)
    {
        if(_ST_APP_ENV != 'development') {
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $viewModel->setTemplate('error/front-end/500');
            $e->setViewModel($viewModel);
            $e->stopPropagation();
        }
    }

    public function setTranslate($e){
        $sm = $e->getApplication()->getServiceManager();
        $translator = $sm->get('translator');
        foreach(glob(getcwd()."/language/*") as $langDir){
            $aL = substr($langDir, -2);
            //get default files with this language
            foreach(glob("$langDir/*.php") as $file) {
                $files[] = $file;
            }

            //get default files in current module
            $module = $this->_getModuleName($e);
            foreach(glob("$langDir/$module/*.php") as $file) {
                $files[] = $file;
            }

            //get default files in current controller
            $controller = $this->_getControllerName($e);
            foreach(glob("$langDir/$module/$controller/*.php") as $file) {
                $files[] = $file;
            }

            //get default files in current action
            $action = $this->_getActionName($e);
            foreach(glob("$langDir/$module/$controller/$action/*.php") as $file) {
                $files[] = $file;
            }

            foreach($files as $file){
                $translator->addTranslationFile("PhpArray", $file, 'default', $aL);
            }
            
        }

        //set current language
        $translator->setLocale($this->_getCurrentLanguage($e));
        $sm->get('ViewHelperManager')->get('translate')->setTranslator($translator);

    }
    
    public function maintenanceMode(MvcEvent $e){
        $ipAddress = \ST\Text::getClientIpServer();
        $listIP = explode('|',getenv('ALLOW_IP'));
        if(!in_array($ipAddress,$listIP)){
            $viewModel = new ViewModel();
            $viewModel->setTerminal(true);
            $viewModel->setTemplate('error/front-end/500');
            $e->setViewModel($viewModel);
            $e->stopPropagation();
        }
    }
    
    private function _getModuleName(MvcEvent $e)
    {
        $routeInfo = $e->getRouteMatch()->getParams();
        $module = $routeInfo['controller'];
        $module = explode('\\', $module);
        $module = $module[0];
        return $module;
    }
    
    private function _getControllerName(MvcEvent $e)
    {
        $controllerName = $e->getRouteMatch()->getParam('controller', '');
        if(!empty($controllerName)){
            $controllerName = explode('\\', $controllerName);
            $controllerName = end($controllerName);
            $controllerName = strtolower($controllerName);
        }
        return $controllerName;
    }
    
    private function _getActionName(MvcEvent $e)
    {
        $routeInfo = $e->getRouteMatch()->getParams();
        $action = $routeInfo['action'];
        return $action;
    }

    private function _getCurrentLanguage(MvcEvent $e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $em = $sm->get('doctrine.entitymanager.orm_default');
        $defaultLanguage = $em->getRepository('\Entity\StLanguage')->findOneByStatus(\Application\Config\Config::STATUS_DEFAULT);
        $lang = $e->getRouteMatch()->getParam('lang', $defaultLanguage->getCode());
        return $lang;
    }

}
