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

class PageController extends AbstractActionController
{
    /**
     * Return list pages with pagination
     */
    public function indexAction()
    {
        $em = $this->UtilityPlugin()->getEm();
        $page = $this->params()->fromRoute('page', 1);
        $limit = 1;
        $offset = ($page - 1) * $limit;
        $data = $em->getRepository('\Entity\StPage')->getBy(array(
            'status' => \Application\Config\Config::STATUS_ACTIVE,
            'language' => $this->UtilityPlugin()->getCurrentLanguage()->getId(),
            'paging' => array(
                'start' => $offset,
                'length' => $limit
            )
        ));
        
        $params = array(
            'route' => array(
                'controller' => 'pages',
                'action' => 'index'
            ),
            'query' => $this->params()->fromQuery(),
        );
        
        $paginator = $this->UtilityPlugin()->getPagination($data, $page, $limit);
        return new ViewModel(array(
            'paginator' => $paginator,
            'params' => $params
        ));
    }

    public function detailAction()
    {
        $id = $this->params()->fromRoute('id', null);
        $em= $this->UtilityPlugin()->getEm();
        $page = $em->getRepository('\Entity\StPage')->getPage($id, $this->UtilityPlugin()->getCurrentLanguage()->getId());
        if(!$page)
            $this->UtilityPlugin()->redirectToPage(404);
        $this->UtilityPlugin()->redirectToTrueUrl($page[0]->getId(), $page['title']);
        $activeLanguages = $this->UtilityPlugin()->getActiveLanguages();
        foreach ($activeLanguages as $lang) {
            $tmpPage = $em->getRepository('\Entity\StPage')->getPage($id, $lang->getId());
            $this->layout()->{"title_".$lang->getCode()} = \ST\Text::rewriteTitle($tmpPage['title']);
        }
        return array(
            'page' => $page
        );
    }
}
