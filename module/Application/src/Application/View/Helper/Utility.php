<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
/**
 * This is utility view helper for some general functions use regularly 
 *
 * @version 1.0
 * @author Minh Tran <minh@starseed.fr>
 */
class Utility extends AbstractHelper implements ServiceLocatorAwareInterface
{

    /**
     * Set the service locator.
     *
     * @param ServiceLocatorInterface $serviceLocator            
     * @return CustomHelper
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator->getServiceLocator();
    }
    
    /**
     * Get Entity Manager 
     * 
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm()
    {
        return $this->getServiceLocator()->get('doctrine.entitymanager.orm_default');
    }

    public function getActiveLanguages()
    {
        $em = $this->getEm();
        return $em->getRepository('\Entity\StLanguage')->findBy(array(
            'status' => array(
                    \Application\Config\Config::STATUS_ACTIVE,
                    \Application\Config\Config::STATUS_DEFAULT
                ),
            ),
            array(
                'sort' => 'asc'
            )
        );
    }
    public function getTranslateUrl($lang = NULL)
    {
        $routeInfo = $this->getView()->routeInfo();
        if($lang == null) {
            $lang = $routeInfo->lang;
        }
        if(!$routeInfo->name)
            return '';
        $routeName = explode('_', $routeInfo->name);
        $mainRoute = $routeName[1];
        $routeParams = array();
        foreach ($routeInfo->params as $key => $value) {
            $translateKey = $key . "_" . $lang;
            if(!empty($this->getView()->layout()->{$translateKey})) $value = $this->getView()->layout()->{$translateKey};
            $routeParams[$key] = $value;
        }
        $url = $this->getView()->url($lang."_".$mainRoute, $routeParams);
        return $url;
    }
    
    public function getAllTranslateMessages($textDomain = 'default', $locale = null)
    {
        $translator = $this->getServiceLocator()->get('translator');
        return $translator->getAllMessages($textDomain, $locale);
    }
}
