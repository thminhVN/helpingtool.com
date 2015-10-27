<?php
namespace Admin\Form;

use Zend\InputFilter\Factory as InputFactory;

class LanguageForm extends PopularForm
{

    public function __construct(\Doctrine\ORM\EntityManager $em = null)
    {
        parent::__construct(null);
        $config = new \Admin\Config\Config();
        $factory = new InputFactory();
        
        $this->setAttributes(array(
            'method' => 'post',
            'class' => 'form-horizontal',
            'id' => 'form',
            'role' => 'form'
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'code',
            'options' => array(
                'label' => 'Code'
            ),
            'attributes' => array(
                'id' => 'code',
                'class' => 'form-control'
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'name',
            'options' => array(
                'label' => 'Name'
            ),
            'attributes' => array(
                'id' => 'name',
                'class' => 'form-control'
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'locale',
            'options' => array(
                'label' => 'Locale'
            ),
            'attributes' => array(
                'id' => 'locale',
                'class' => 'form-control'
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'flag',
            'options' => array(
                'label' => 'Flag'
            ),
            'attributes' => array(
                'input-type' => 'image',
                'id' => 'flag',
                'class' => 'form-control',
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'dateFormat',
            'options' => array(
                'label' => 'Date Format'
            ),
            'attributes' => array(
                'id' => 'dateFormat',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'timeFormat',
            'options' => array(
                'label' => 'Time Format'
            ),
            'attributes' => array(
                'id' => 'timeFormat',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Select',
            'name' => 'status',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => ' Status',
                'empty_option' => '--Please choose option --',
                'value_options' => $config->getConfig('language_status')
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'currency',
            'options' => array(
                'label' => 'Currency'
            ),
            'attributes' => array(
                'id' => 'currency',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'sort',
            'options' => array(
                'label' => 'Sort'
            ),
            'attributes' => array(
                'id' => 'sort',
                'class' => 'form-control'
            )
        ));
    }
}
