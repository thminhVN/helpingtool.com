<?php
namespace Admin\Form;

use Zend\InputFilter\Factory as InputFactory;

class CategoryForm extends PopularForm
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
        
        $categories = $em->getRepository('\Entity\StCategoryDetail')->findBy(array('language' => $defaultLang));
        $values = array();
        foreach ($categories as $categoryDetail){
            $values[$categoryDetail->getCategory()->getId()] = $categoryDetail->getName();
        }
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'parent',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => 'Parrent Category',
                'empty_option' => '--Please choose option --',
                'value_options' => $values
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

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'sort',
            'options' => array(
                'label' => 'Order'
            ),
            'attributes' => array(
                'id' => 'sort',
                'class' => 'form-control'
            )
        ));
        
        foreach ($langs as $lang) {
            $this->add(array(
                'type' => 'Zend\Form\Element\Text',
                'name' => 'name_'.$lang->getCode(),
                'options' => array(
                    'label' => $lang->getName() . ' Name'
                ),
                'attributes' => array(
                    'id' => 'name_'.$lang->getCode(),
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
                    'class' => 'form-control',
                    'data-lang' => $lang->getCode(),
                    'data-lang-id' => $lang->getId(),
                )
            ));
        }
        
        // Set input filter
        $this->getInputFilter()->add($factory->createInput(array(
            'name' => 'parent',
            'required' => false,
        )));
    }
}
