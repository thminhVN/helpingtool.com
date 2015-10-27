<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    /**
     * Return homepage view
     * 
     */
    public function indexAction()
    {
        $em = $this->UtilityPlugin()->getEm();
        $pages = $em->getRepository('\Entity\StPage')->getBy(array(
            'status' => array(\Application\Config\Config::STATUS_ACTIVE),
            'language' => array($this->UtilityPlugin()->getCurrentLanguage()->getId()),
        ));
        return new ViewModel(array(
            'pages' => $pages
        ));
    }
}
