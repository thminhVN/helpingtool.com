<?php
namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\View\Model\ViewModel;
/**
 * This is utility controller plugin for some general functions use regularly 
 *
 * @version 1.0
 * @author Minh Tran <minh@starseed.fr>
 */
class UtilityPlugin extends AbstractPlugin
{

    /**
     * @var \Doctrine\ORM\EntityManager
     */
    private $_em;

    /**
     * Get Entity Manager
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function getEm()
    {
        if ($this->_em === null) {
            $this->_em = $this->getController()
                ->getServiceLocator()
                ->get('doctrine.entitymanager.orm_default');
        }
        return $this->_em;
    }

    /**
     * Get Entity Manager
     *
     * @return \Zend\View\HelperPluginManager
     */
    public function getViewHelper($name)
    {
        return $this->getController()
            ->getServiceLocator()
            ->get('viewhelpermanager')
            ->get($name);
    }

    /**
     * Render ViewModel to string
     *
     * @param \Zend\View\Model\ViewModel $view            
     * @return string
     */
    public function renderViewToString(\Zend\View\Model\ViewModel $view)
    {
        return $this->getController()
            ->getServiceLocator()
            ->get('viewrenderer')
            ->render($view);
    }

    /**
     * Rename navigation item with specific label
     *
     * @param string $navigationName
     *            navigation name in config file
     * @param string $label
     *            item label on navigation want to replace
     * @param string $title
     *            title string use for replacing
     */
    public function changeNavigatorTitle($navigationName, $label, $title)
    {
        $nav = $this->getController()
            ->getServiceLocator()
            ->get($navigationName);
        $page = $nav->findByLabel($label);
        $page->setLabel($title);
    }

    /**
     * Active navigation item with specific label
     *
     * @param string $navigationName
     *            navigation name in config file
     * @param string $label
     *            item label on navigation want to active
     */
    public function activeNavigation($navigationName, $label)
    {
        $nav = $this->getController()
            ->getServiceLocator()
            ->get($navigationName);
        $page = $nav->findByLabel($label);
        $page->setActive(true);
    }

    /**
     * Redirect page right url if people go to our website with right id and wrong title
     *
     * @param int $id
     *            page id
     * @param string $title
     *            right page title
     */
    public function redirectToTrueUrl($id, $title)
    {
        $event = $this->getController()
            ->getServiceLocator()
            ->get('Application')
            ->getMvcEvent();
        $routeName = $event->getRouteMatch()->getMatchedRouteName();
        $fullUrl = $this->getController()
            ->url()
            ->fromRoute($routeName, array(
            'id' => $id,
            'title' => \ST\Text::rewriteTitle($title)
        ));
        if (! empty($_SERVER['QUERY_STRING'])) {
            $fullUrl .= "?" . $_SERVER['QUERY_STRING'];
        }

        if ($_SERVER['REQUEST_URI'] != $fullUrl) {
            return $this->getController()
                ->redirect()
                ->toUrl($fullUrl);
            $event->stopPropagation();
        }
    }

    /**
     * Redirect people to custom page (ex: 404, 500, 503,...)
     *
     * @param int $statusCode
     *            status code after redirect
     * @return \Zend\View\Model\ViewModel
     */
    public function redirectToPage($statusCode)
    {
        $this->getController()
            ->getResponse()
            ->setStatusCode($statusCode);
        $this->getController()->layout("error/front-end/$statusCode");
        $viewModel = new ViewModel();
        $viewModel->setTemplate("error/front-end/$statusCode");
        return $viewModel;
    }

    public function getActiveLanguages()
    {
        return $this->getEm()
            ->getRepository('\Entity\StLanguage')
            ->findBy(array(
            'status' => array(
                \Application\Config\Config::STATUS_ACTIVE,
                \Application\Config\Config::STATUS_DEFAULT
            )
        ));
    }
    
    public function getCurrentLanguage()
    {
        $defaultLang = $this->getEm()->getRepository('\Entity\StLanguage')->findOneByStatus(\Application\Config\Config::STATUS_DEFAULT);
        $currentLangCode = $this->getController()->params()->fromRoute('lang', null);
        if(!empty($currentLangCode))
            return $this->getEm()->getRepository('\Entity\StLanguage')->findOneByCode($currentLangCode);
        return $defaultLang;
    }

    /**
     * Get all languages from database to fiter in admin
     * @return array
     */
    public function getFilterLanguages()
    {
        $result = array(
            'type' => 'radio',
            'values' => array(),
            'checked' => array()
        );
        $languages = $this->getActiveLanguages();
        foreach ($languages as $lang) {
            $result['values'][$lang->getId()] = $lang->getName();
            if ($lang->getStatus() == \Application\Config\Config::STATUS_DEFAULT)
                $result['checked'][] = $lang->getId();
        }
        return $result;
    }

    /**
     * Get all data from object to set to form
     * 
     * @param \Zend\Form\Form $form
     * @param unknown $object
     * @return array
     */
    public function getFormData(\Zend\Form\Form $form, $object)
    {
        $data = array();
        $em = $this->getEm();
        foreach ($form as $element) {
            $name = $element->getName();
            $getMethod = "get" . ucfirst($name);
            $value = '';
            $langCode = $element->getAttribute('data-lang');
            if (! empty($langCode)) {
                $translateClass = get_class($object) . "Detail";
                $foreignKeys = $this->_getTranslateForeignKeys($translateClass);
                $translateObject = $em->getRepository($translateClass)->findOneBy(array(
                    $foreignKeys['parent_field'] => $object,
                    $foreignKeys['language_field'] => $element->getAttribute('data-lang-id')
                ));
                $getTranslateMethod = str_replace("_$langCode", '', $getMethod);
                $value = $translateObject->{$getTranslateMethod}();
            } else {
                if (method_exists($object, $getMethod)) {
                    $value = $object->{$getMethod}();
                    if (is_object($value)) {
                        $ref = new \ReflectionMethod(get_class($object), $getMethod);
                        // get return type
                        $returnType = \ST\Text::exx($ref->getDocComment(), '@return ', ' ');
                        if (preg_match("/Collection/", $returnType)) {
                            $array = array();
                            foreach ($value as $item) {
                                $array[] = $item->getId();
                            }
                            $value = $array;
                        } elseif (preg_match("/\\DateTime/", $returnType)) {
                            $value = $value->format(_ST_DATE_TIME_FORMAT);
                        } else {
                            $value = $value->getId();
                        }
                    }
                }
            }
            $data[$name] = $value;
        }
        return $data;
    }

    /**
     * Binding all data to entity
     *
     * @param string $className entity class name 
     * @param array $data data to binding to object
     * @throws \Exception
     * @return int id object after binding
     */
    public function bindingObject($className, $data)
    {
        $em = $this->getEm();
        $em->getConnection()->beginTransaction();
        $isEdit = false;
        try {
            $now = new \DateTime();
            $now->setTimestamp(_ST_TIME_NOW);
            // if we have object id
            if (intval(@$data['id']) > 0) {
                $isEdit = true;
                $object = $em->getRepository($className)->findOneById($data['id']);
            } else {
                $object = new $className();
                if (method_exists($object, 'setCreator'))
                    $object->setCreator($this->getController()->identity());
                if (method_exists($object, 'setDatetimeCreated'))
                    $object->setDatetimeCreated($now);
            }
            
            if (method_exists($object, 'setUpdator'))
                $object->setUpdator($this->getController()->identity());
            if (method_exists($object, 'setDatetimeUpdated'))
                $object->setDatetimeUpdated($now);
                
            // binding normal property first
            foreach ($data as $key => $value) {
                if (strpos($key, '_') == false) {
                    $this->_bindingField($object, $key, $value);
                    unset($data[$key]);
                }
            }
            $em->persist($object);
            // then binding translated object if this class have translation
            if (count($data) > 0) {
                $langs = $this->getActiveLanguages();
                $translateClass = $className . "Detail";
                //Get foreign keys from translate table
                $foreignKeys = $this->_getTranslateForeignKeys($translateClass);
                foreach ($langs as $lang) {
                    if ($isEdit) { //edit mode
                        //Check translate object already exist or not first
                        $translateObject = $em->getRepository($translateClass)->findOneBy(array(
                            $foreignKeys['parent_field'] => $object,
                            $foreignKeys['language_field'] => $lang
                        ));
                        if (! $translateObject)
                            $translateObject = new $translateClass();
                    } else {
                        $translateObject = new $translateClass();
                    }
                    $translateObject->{'set' . ucfirst($foreignKeys['language_field'])}($lang);
                    $translateObject->{'set' . ucfirst($foreignKeys['parent_field'])}($object);

                    //Get all property of translate class with this language from post data 
                    $matches = preg_grep("/.*_" . $lang->getCode() . "/", array_keys($data));
                    foreach ($matches as $translateProperty) {
                        $tempArray = explode('_', $translateProperty);
                        $key = $tempArray[0];
                        $this->_bindingField($translateObject, $key, $data[$translateProperty]);
                    }
                    $em->persist($translateObject);
                }
            }
            $em->flush();
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollback();
            throw new \Exception($e->getMessage());
        }
        return (int) $object->getId();
    }
    
    function getPagination($records, $page, $limit)
    {
        //record is null return null
        if(count($records) < 1)
            return null;
        $adapter = new \Application\Controller\Plugin\DoctrinePaginatorAdapter($records);
        $paginator = new \Zend\Paginator\Paginator($adapter);
        $paginator->setCurrentPageNumber($page);
        $paginator->setItemCountPerPage($limit);
        return $paginator;
    }
    /**
     * 
     * @param unknown $object
     * @param string $key property name
     * @param mixed $value binding value
     * @return unknown
     */
    private function _bindingField(&$object, $key, $value)
    {
        $em = $this->getEm();
        $key = ucfirst($key);
        $methodName = "set$key";
        // if this object has set method
        if (method_exists($object, $methodName)) {
            $ref = new \ReflectionMethod(get_class($object), $methodName);
            // get param type from this method
            $paramType = \ST\Text::exx($ref->getDocComment(), '@param ', ' ');
            if (preg_match("/\\Entity/", $paramType)) { // param is object
                $item = $em->getRepository($paramType)->findOneById($value);
                $object->{$methodName}($item);
            } elseif (preg_match("/\\DateTime/", $paramType)) { //param is Datetime
                $item = \Datetime::createFromFormat(_ST_DATE_TIME_FORMAT, $value);
                $object->{$methodName}($item);
            } else { //param is basic type (string, int,...)
                $object->{$methodName}($value);
            }
        } else { // if this object hasn't set method check object has add method
            if (method_exists($object, "add$key")) { // if this field is array objects
                $ref = new \ReflectionMethod(get_class($object), "add$key");
                // get param class
                $paramType = \ST\Text::exx($ref->getDocComment(), '@param ', ' ');
                //remove all old values
                $currentValues = $object->{"get$key"}();
                if (count($currentValues) > 0) {
                    foreach ($currentValues as $currentValue) {
                        $object->{"remove$key"}($currentValue);
                    }
                }
                //set new values
                $newValues = $em->getRepository($paramType)->findBy(array(
                    'id' => $value
                ));
                if (count($newValues) > 0) {
                    foreach ($newValues as $newValue) {
                        $object->{"add$key"}($newValue);
                    }
                }
            }
        }
        return $object;
    }

    /**
     * Find and return foreign keys include: parent and language
     * @param string $class
     * @return array|null
     */
    private function _getTranslateForeignKeys($class)
    {
        $result = array(
            'parent_field' => '',
            'language_field' => ''
        );
        $parentClass = str_replace('Detail', '', $class);
        if (strpos($parentClass, "\\") != 0) {
            $parentClass = "\\" . $parentClass;
        }
        $ref = new \ReflectionClass($class);
        foreach ($ref->getProperties() as $property) {
            $refPro = new \ReflectionProperty($property->class, $property->name);
            $paramType = \ST\Text::exx($refPro->getDocComment(), '@var ', ' ');
            $paramType = trim($paramType);
            if ($paramType == $parentClass)
                $result['parent_field'] = $property->name;
            if ($paramType == '\Entity\StLanguage')
                $result['language_field'] = $property->name;
        }
        return $result;
    }

    /**
     * Parse Entity Object to array
     * 
     * @param unknown $object
     * @return array
     */
    public function doctrine2Array($object)
    {
        $hydrator = new \DoctrineModule\Stdlib\Hydrator\DoctrineObject($this->getEm());
        return $hydrator->extract($object);
    }
}

?>