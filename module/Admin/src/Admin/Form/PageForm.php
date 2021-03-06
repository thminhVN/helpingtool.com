<?php
namespace Admin\Form;

use Zend\InputFilter\Factory as InputFactory;

class PageForm extends PopularForm
{

    public function __construct(\Doctrine\ORM\EntityManager $em = null)
    {
        parent::__construct(null);
        $config = new \Admin\Config\Config();
        $factory = new InputFactory();
        $langs = $em->getRepository('\Entity\StLanguage')->findBy(array(
                'status' => array(
                    \Application\Config\Config::STATUS_ACTIVE,
                    \Application\Config\Config::STATUS_DEFAULT
                )
            ), array(
                'sort' => 'asc'
            )
        );
        $defaultLang = $em->getRepository('\Entity\StLanguage')->findOneByStatus(\Application\Config\Config::STATUS_DEFAULT);
        
        $this->setAttributes(array(
            'method' => 'post',
            'class' => 'form-horizontal',
            'id' => 'form',
            'role' => 'form',
            'data-type' => 'multilanguage'
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'status',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Status',
                'empty_option' => '--Please choose option --',
                'value_options' => $config->getConfig('status')
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'priority',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Priority',
                'empty_option' => '--Please choose option --',
                'value_options' => $config->getConfig('priority')
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'layout',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Layout',
                'empty_option' => '--Please choose option --',
                'value_options' => $config->getConfig('layout')
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'datetimePublished',
            'options' => array(
                'label' => 'Date Published',
            ),
            'attributes' => array(
                'id' => 'datetimePublished',
                'class' => 'form-control datetimepicker'
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Checkbox',
            'name' => 'isMenu',
            'options' => array(
                'label' => 'Show on menu',
                'use_hidden_element' => true,
                'checked_value' => '1',
                'unchecked_value' => '0'
            )
        ));
        
        $categories = $em->getRepository('\Entity\StCategoryDetail')->findBy(array('language' => $defaultLang));
        $values = array();
        foreach ($categories as $categoryDetail){
            $values[$categoryDetail->getCategory()->getId()] = $categoryDetail->getName();
        }
        
        $this->add(array(
            'type' => 'Zend\Form\Element\MultiCheckbox',
            'name' => 'category',
            'options' => array(
                'label' => 'Categories',
                'value_options' => $values,
                'label_attributes' => array(
                    'class' => 'checkbox-inline',
                ),
            )
        ));
        
        $tags = $em->getRepository('\Entity\StTagDetail')->findBy(array('language' => $defaultLang));
        $values = array();
        foreach ($tags as $tag){
            $values[$tag->getTag()->getId()] = $tag->getName();
        }
        
        $this->add(array(
            'type' => 'Zend\Form\Element\MultiCheckbox',
            'name' => 'tag',
            'options' => array(
                'label' => 'Tags',
                'value_options' => $values,
                'label_attributes' => array(
                    'class' => 'checkbox-inline',
                ),
            )
        ));
        
        $this->getInputFilter()->add($factory->createInput(array(
            'name' => 'tag',
            'required' => false,
        )));
        
        foreach ($langs as $lang) {
            $this->add(array(
                'type' => 'Zend\Form\Element\Text',
                'name' => 'title_'.$lang->getCode(),
                'options' => array(
                    'label' => $lang->getName() . ' title'
                ),
                'attributes' => array(
                    'id' => 'title_'.$lang->getCode(),
                    'class' => 'form-control',
                    'data-lang' => $lang->getCode(),
                    'data-lang-id' => $lang->getId(),
                )
            ));
            $this->add(array(
                'type' => 'Zend\Form\Element\Textarea',
                'name' => 'seoDescription_'.$lang->getCode(),
                'options' => array(
                    'label' => $lang->getName() . ' SEO Description'
                ),
                'attributes' => array(
                    'id' => 'seoDescription_'.$lang->getCode(),
                    'class' => 'form-control',
                    'data-lang' => $lang->getCode(),
                    'data-lang-id' => $lang->getId(),
                )
            ));
            
            $this->add(array(
                'type' => 'Zend\Form\Element\Text',
                'name' => 'image_'.$lang->getCode(),
                'options' => array(
                    'label' => $lang->getName() . ' Avatar'
                ),
                'attributes' => array(
                    'input-type' => 'image',
                    'id' => 'image_'.$lang->getCode(),
                    'class' => 'form-control',
                    'data-lang' => $lang->getCode(),
                    'data-lang-id' => $lang->getId(),
                )
            ));
            
            $this->add(array(
                'type' => 'Zend\Form\Element\Textarea',
                'name' => 'description_'.$lang->getCode(),
                'options' => array(
                    'label' => $lang->getName() . ' Description'
                ),
                'attributes' => array(
                    'id' => 'description_'.$lang->getCode(),
                    'class' => 'form-control ckeditor',
                    'data-lang' => $lang->getCode(),
                    'data-lang-id' => $lang->getId(),
                )
            ));
            
        }
    }
}
