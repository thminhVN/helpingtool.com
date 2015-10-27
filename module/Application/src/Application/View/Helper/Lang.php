<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * This is a shotten view helper to get immediately language param from routeInfo class
 * return current language param from route if not will return default language from url
 *
 * @version 1.0
 * @author Minh Tran <minh@starseed.fr>
 */
class Lang extends AbstractHelper
{
    public function __invoke()
    {
        $lang = $this->getView()->routeInfo()->lang;
        if (empty($lang)) {
            $em = $this->getView()
                ->utility()
                ->getEm();
            $defaultLang = $em->getRepository('\Entity\StLanguage')->findOneByStatus(\Application\Config\Config::STATUS_DEFAULT);
            $lang = $defaultLang->getCode();
        }
        return $lang;
    }
}
