<?php
namespace Acl;

use Zend\Mvc\MvcEvent; 

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    // added for Acl ###################################
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $sharedManager = $eventManager->getSharedManager();
//        $eventManager->attach('route', array($this, 'loadConfiguration'), 2);
//        $this->loadConfiguration($e);
        $sharedManager->attach('Zend\Mvc\Controller\AbstractActionController', MvcEvent::EVENT_DISPATCH , array($this, 'authorize'), 2);
    }

    public function loadConfiguration(MvcEvent $e)
    {
        $application = $e->getApplication();
        $sm = $application->getServiceManager();
        $sharedManager = $application->getEventManager()->getSharedManager();
        
        $router = $sm->get('router');
        $request = $sm->get('request');
        
        $matchedRoute = $router->match($request);
        if (null !== $matchedRoute) {
            $sharedManager->attach('Zend\Mvc\Controller\AbstractActionController', MvcEvent::EVENT_DISPATCH , array($this, 'authorize'), 2);
        }
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    // authorize current user
    public function authorize(MvcEvent $e)
    {
        $sm = $e->getApplication()->getServiceManager();
        $sm->get('ControllerPluginManager')->get('AclPlugin')->authorize($e);

        $response = $e->getResponse();
        if ($response->getStatusCode() == 302) {
            $router = $sm->get('router');
            $request = $sm->get('request');
            $matchedRoute = $router->match($request);
            $lang = $matchedRoute->getParam('lang');
            if(!empty($lang))
                $lang = "/$lang";
            $redirectUrl = "$lang/auth/login?return_url=$_SERVER[REQUEST_URI]";
            $response->getHeaders()->addHeaderLine('Location', $redirectUrl);
            $response->sendHeaders();
            $e->stopPropagation();
            return $response->setContent('');
        }
    }
}
