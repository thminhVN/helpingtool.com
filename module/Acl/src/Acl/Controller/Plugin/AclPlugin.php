<?php
namespace Acl\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin; 
use Zend\Permissions\Acl\Acl;
use Zend\Session\Container; 
use Zend\Permissions\Acl\Role\GenericRole as Role; 
use Zend\Permissions\Acl\Resource\GenericResource as Resource;
use Zend\Mvc\MvcEvent;

class AclPlugin extends AbstractPlugin
{

    protected $role;

    /**
     * Get current user session
     */
    private function _getUserSession()
    {
        if($this->getController()->identity())
        {
            return $this->getController()->identity()->getRole();
        }
        return 'guest';
    }

    /**
     * @param MvcEvent $e
     */
    public function authorize(MvcEvent $e)
    {
        // set ACL
        //$acl = new Acl();
        $acl = $this->getController()->getServiceLocator()->get('StAcl');
        $acl->deny();
        
        // Init roles
        $acl->addRole(new Role('guest'));
        // member will be inherited guest role
        $acl->addRole(new Role('moderator'), array('guest'));
        $acl->addRole(new Role('admin'), array('moderator'));
        
        // init Resources
        // $acl->addResource('module:controller','parent resource');
        $acl->addResource('admin')
            ->addResource('admin:index', 'admin')
            ->addResource('admin:media', 'admin')
            ->addResource('admin:user', 'admin')
            ->addResource('admin:language', 'admin')
            ->addResource('admin:category', 'admin')
            ->addResource('admin:page', 'admin')
            ->addResource('admin:tag', 'admin')
        ;
        
        $acl->addResource('application')
            ->addResource('application:index', 'application')
            ->addResource('application:auth', 'application')
        ;

        // init permissions
        // $acl->allow('role', 'resource', 'action'); resrouce = 'module:controller'
        $acl->allow('guest', 'application', null);
        $acl->allow('admin');
        $acl->allow('moderator');
        $acl->deny('moderator', 'admin:user', array('add'));

        $controller = $e->getTarget();
        $controllerClass = get_class($controller);
        $moduleName = strtolower(substr($controllerClass, 0, strpos($controllerClass, '\\')));
        
        //get current user role
        $role = $this->getRole($e);

        $routeMatch = $e->getRouteMatch();

        $actionName = strtolower($routeMatch->getParam('action', 'not-found')); // get the action name
        $controllerName = $routeMatch->getParam('controller', 'not-found'); // get the controller name
        $arrs = explode('\\', $controllerName);
        $controllerName = strtolower(array_pop($arrs));
        // ################### Check Access ########################
        if (
            $role != 'admin'
            && (!$acl->hasResource("$moduleName:$controllerName") 
                || !$acl->isAllowed($role, "$moduleName:$controllerName", $actionName))
            ) {
            $response = $e->getResponse();
            $response->setStatusCode(302);
            
            // if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])){
            // $e->stopPropagation();
            // }else{
            // // redirect to login page or other page.
            // // $response->getHeaders()->addHeaderLine('Location', '/auth/login?return_url=' . $_SERVER['REQUEST_URI']);
            // // $response->sendHeaders();
            
            // $e->stopPropagation();
            // // exit();
            // }
        }
    }

    // get Role from session or api
    function getRole($e)
    {
        $role = ($this->_getUserSession() == '') ? 'guest' : $this->_getUserSession();
        return $role;
    }
}
