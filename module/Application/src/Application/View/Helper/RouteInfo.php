<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * This class contain all needed infomations of route match
 *
 * @version 1.0
 * @author Minh Tran <minh@starseed.fr>
 */
class RouteInfo extends AbstractHelper
{

    protected $routeMatch;

    protected $info;

    public function __construct($routeMatch)
    {
        $this->routeMatch = $routeMatch;
        $info = new \stdClass();
        $info->controller = null;
        $info->action = null;
        $info->lang = 'en';
        $info->name = null;
        $info->params = null;
        $info->module = null;
        $info->orginalController = null;
        $info->title = null;
        $this->info = $info;
    }

    /**
     * 
     * @return Ambigous <\stdClass, unknown>
     */
    public function __invoke()
    {
        $info = $this->info;
        if ($this->routeMatch) {
            $info->controller = $this->routeMatch->getParam('controller', 'index');
            $explodedController = explode('\\', $info->controller);
            
            $info->orginalController = end($explodedController);
            
            $info->controller = strtolower(end($explodedController));
            
            $info->module = $explodedController[0];
            
            $info->action = $this->routeMatch->getParam('action', 'index');
            
            $info->lang = $this->routeMatch->getParam('lang');
            
            $info->name = $this->routeMatch->getMatchedRouteName();
            
            $info->params = $this->routeMatch->getParams();
            $info->params['controller'] = $info->controller;
            $action = $info->action != 'index' ? $info->action : 'Show all';
            $controller = $info->orginalController;
            $title = ucwords($action) . " " . ucwords($controller);
            if ($info->action == 'index' && $info->orginalController == 'Index') {
                $title = 'Dashboard';
            }
            
            $info->title = $title;
        }
        $this->info = $info;
        return $this->info;
    }

    /**
     * 
     * @param string $name
     * @return string
     */
    public function get($name)
    {
        return $this->info->$name;
    }
}
