<?php
/*
 * jQuery File Upload Plugin PHP Class 6.4.2
 * https://github.com/blueimp/jQuery-File-Upload
 *
 * Copyright 2010, Sebastian Tschan
 * https://blueimp.net
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */
namespace Admin\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Session\Container;

class AdminPlugin extends AbstractPlugin
{
    public function getFormData($form, $object){
        $data = array();
        foreach ($form as $element){
            $name = $element->getName();
        	$value = '';
        	$lang_code = $element->getAttribute('data-lang');
            if(!empty($lang_code))
        	{
        	    $tmp_name = str_replace('_'.$lang_code, '', $name);
                if(method_exists($object, 'get'.ucfirst(\ST\Text::underscoreToCamelCase($tmp_name)))){
            	    $content = $object->{'get'.ucfirst(\ST\Text::underscoreToCamelCase($tmp_name))}();
            	    $lang = $this->em->getRepository('\Entity\StLanguage')->findOneBy(array('code' => $lang_code));
                    if($content != null && $content != ''){
            	       $description = $this->em->getRepository('\Entity\StContentDescription')->findOneBy(array('content' => $content->getId(), 'lang' => $lang->getId()));
            	       $value = $description->getDescription();
                    }
                }
        	}
        	else 
        	{
                if(method_exists($object, 'get'.ucfirst(\ST\Text::underscoreToCamelCase($name)))){
        	       $value = $object->{'get'.ucfirst(\ST\Text::underscoreToCamelCase($name))}();
        	       if(is_object($value))
        	       {
        	           $ref = new \ReflectionMethod(get_class($object), 'get'.ucfirst(\ST\Text::underscoreToCamelCase($name)));
        	           //get class of parameter
        	           $return_type = explode("@return ", $ref->getDocComment());
        	           $return_type  = $return_type[1];
        	           $return_type = explode(' ', $return_type);
        	           $return_type  =$return_type[0];
            	       if(preg_match("/Collection/", $return_type))
            	       {
            	           $array = array();
            	           foreach ($value as $item){
            	               $array[] = $item->getId();
            	           }
            	           $value = $array;
            	       }
            	       else 
            	       {
            	           $value = $value->getId();
            	       }
        	       }
                }
        	}
        	$data[$name] = $value;
        }
        return $data;
    }
    
    public function bindingObject($class_name, $data, $method = 'add'){
        $accepted_methods = array('add', 'edit');
        if(in_array($method, $accepted_methods))
        {
            $now = _TIME_NOW_;
            $em = $this->_getEm();
            //$lang_list = $em->getRepository('\Entity\StLanguage')->findAll();
            $lang_codes_prefix = array();
            $already_add = array();
            $object_array= array();
            if($method == 'add') //if edit just change update time
            {
                $object = new $class_name;
                $data['creation_date'] = $data['updated'] = $now;
                if(method_exists($object, 'setCreator')){
                    $session = new Container('user');
                    $creator = $em->getRepository('\Entity\StMember')->findOneBy(array('id' => $session->userid));
                    $object->setCreator($creator);
                }
            }
            else
            {
                $object = $em->getRepository($class_name)->findOneBy(array('id' => $data['id']));
                $data['updated'] = $now;
            }
            	
            $em->getConnection()->beginTransaction();
            try{
                foreach ($data as $key => $value) //check all param in form
                {
                    $exist_value_in_field = true;
                    $prefix_name = substr($key, -3);
    
                    if(in_array($prefix_name, $lang_codes_prefix)) //if this is a multi language field
                    {
                        $main_name = substr($key, 0, -3);
                        if(!in_array($main_name, $already_add)){
                            if(method_exists($object, 'set'.ucfirst(\ST\Text::underscoreToCamelCase($main_name)))){
                                $already_add[] = $main_name;
                                if($method == 'add' || !$exist_value_in_field)
                                {
                                    $content = new \Entity\StContent();
                                    $content->setCreated($now);
                                }
                                else
                                {
                                    $content = $object->{'get'.ucfirst(\ST\Text::underscoreToCamelCase($main_name))}();
                                    if($content == null || $content == ''){
                                        $content = new \Entity\StContent();
                                        $content->setCreated($now);
                                        $exist_value_in_field = false;
                                    }
                                }
                                $content->setUpdated($now);
                                $em->persist($content);
                                foreach ($lang_list as $item){
    
                                    $text = $data[$main_name.'_'.$item->getCode()];
                                    if($method == 'add' || !$exist_value_in_field)
                                    {
                                        $content_description = new \Entity\StContentDescription();
                                        $content_description->setContent($content);
                                        $content_description->setLang($item);
                                    }
                                    else
                                    {
                                        $content_description = $em->getRepository('\Entity\StContentDescription')->findOneBy(array('content' => $content, 'lang' => $item));
                                    }
                                    $content_description->setDescription($text);
                                    $em->persist($content_description);
                                }
                                $object->{'set'.ucfirst(\ST\Text::underscoreToCamelCase($main_name))}($content);
                            }
                        }
                    }
                    else // this is a normal field
                    {
                        if(method_exists($object, 'set'.ucfirst(\ST\Text::underscoreToCamelCase($key)))){
                            $already_add[] = $key;
                            $ref = new \ReflectionMethod($class_name, 'set'.ucfirst(\ST\Text::underscoreToCamelCase($key)));
                            //get class of parameter
                            $param_type = explode("@param ", $ref->getDocComment());
                            $param_type = $param_type[1];
                            $param_type = explode(' ', $param_type);
                            $param_type  =$param_type[0];
    
                            if(preg_match("/\\Entity/", $param_type))
                            {
                                $item = $em->getRepository($param_type)->findOneBy(array('id' => $value));
                                $object->{'set'.ucfirst(\ST\Text::underscoreToCamelCase($key))}($item);
                            }
                            elseif (preg_match("/Collection/", $param_type))
                            {
                                $param_type = explode("@param ", $ref->getDocComment());
                                $param_type = $param_type[1];
                                $param_type = explode(' ', $param_type);
                                $param_type  =$param_type[1];
                                $item = $em->getRepository($param_type)->findBy(array('id' => $value));
                                $object->{'set'.ucfirst(\ST\Text::underscoreToCamelCase($key))}($item);
                            }
                            else
                            {
                                $object->{'set'.ucfirst(\ST\Text::underscoreToCamelCase($key))}($value);
                            }
    
                        }
                    }
                }
                if(method_exists($object, 'setUpdatedBy')){
                    $session = new Container('user');
                    $updator = $em->getRepository('\Entity\StUser')->findOneBy(array('id' => $session->userid));
                    $object->setUpdatedBy($updator);
                }
                $em->persist($object);
                $em->flush();
                $em->getConnection()->commit();
                $em->close();
                return $object->getId();
            }
            catch (\Exception $e)
            {
                $em->getConnection()->rollback();
                $em->close();
                throw new \Exception($e->getMessage());
            }
        }
    }
    
    public function prepareParams(){
        $controller = $this->getController();
        // LIMIT PAGING
        $s_params['paging']['start'] = ($controller->params()->fromQuery('iDisplayStart', 0));
        $s_params['paging']['length'] = ($controller->params()->fromQuery('iDisplayLength', 0));
         
        $data = $controller->params()->fromQuery();
//         $data['sort_columns'] = explode(',', $data['sort_columns']);
    
        $matches = preg_grep("/search-/", array_keys($data));
        foreach($matches as $m)
        {
            $name = str_replace('search-', '', $m);
            $s_params[$name] = $data[$m];
        }
    
        $s_params['order']['orderby'] = $data['sort_columns'][$data['iSortCol_0']];
        $s_params['order']['orderdir'] = $data['sSortDir_0'];
    
        return $s_params;
    }
    
    public function setAttribute($entityClass) {
        $result = array(
            'message' => '',
            'success' => false
        );
        try {
            $postParams = $this->getController()->params()->fromPost();
            $em = $this->_getEm();
            $object = $em->getRepository($entityClass)->findOneById(@$postParams['id']);
            if(!$object) {
                $result['message'] = 'This object isn\'t existed';
                return $result;
            }
            $attribute = @$postParams['attribute'];
            $attribute = ucfirst($attribute);
            $method = "set$attribute";
            if(!method_exists($object, $method)){
                $result['message'] = 'Can not find this attribute';
                return $result;
            }
            $object->{$method}($postParams['value']);
            $em->persist($object);
            $em->flush();
            $result['message'] = 'Updated';
            $result['success'] = true;
        } catch (\Exception $e) {
            $result['message'] = $e->getMessage();
        }
        
        return $result;
    }
    
    public function remove($entityClass) {
        $result = array(
            'message' => '',
            'success' => false
        );
        $em = $this->_getEm();
        $em->getConnection()->beginTransaction();
        try {
            $ids = $this->getController()->params()->fromPost('ids', 0);
            for($i = 0; $i < count($ids); $i++) {
                $object = $em->getRepository($entityClass)->findOneById($ids[$i]);
                $em->remove($object);
            }
            $result['message'] = 'Removed';
            $result['success'] = true;
            $em->flush();
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $result['message'] = $e->getMessage();
            $em->getConnection()->rollback();
        }
        return $result;
    }
    
    public function changeOrder($entityClass){
        $result = array(
            'message' => '',
            'success' => false
        );
        $request = $this->getController()->getRequest();
        if($request->isPost()){
            $datas = $this->getController()->params()->fromPost('data');
            $em = $this->_getEm();
            $em->getConnection()->beginTransaction();
            try{
                foreach ($datas as $data){
                    $object = $em->getRepository($entityClass)->findOneBy(array('id'=>$data['id']));
                    $object->setSort($data['order']);
                    $em->persist($object);
                }
                $em->flush();
                $em->getConnection()->commit();
                $result['success'] = true;
                $result['message'] = 'Updated';
            }
            catch(\Exception $e){
                $em->getConnection()->rollback();
                $result['message'] = $e->getMessage();
            }
        }
        return $result;
    }
    
    private function _getEm(){
        return $this->getController()->UtilityPlugin()->getEm();
    }
}
