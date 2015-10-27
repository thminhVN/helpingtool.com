<?php
namespace Admin\Form;

use Zend\InputFilter\Factory as InputFactory;

class UserForm extends PopularForm
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
            'name' => 'username',
            'options' => array(
                'label' => 'Username'
            ),
            'attributes' => array(
                'id' => 'username',
                'class' => 'form-control'
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Text',
            'name' => 'displayName',
            'options' => array(
                'label' => 'Display Name'
            ),
            'attributes' => array(
                'id' => 'display-name',
                'class' => 'form-control'
            )
        ));

        $this->add(array(
            'type' => 'Zend\Form\Element\Email',
            'name' => 'email',
            'options' => array(
                'label' => 'Email'
            ),
            'attributes' => array(
                'id' => 'email',
                'class' => 'form-control'
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Password',
            'name' => 'password',
            'options' => array(
                'label' => 'Password'
            ),
            'attributes' => array(
                'id' => 'password',
                'class' => 'form-control'
            )
        ));
        
        $this->add(array(
            'type' => 'Zend\Form\Element\Password',
            'name' => 'confirmPassword',
            'options' => array(
                'label' => 'Confirm Password'
            ),
            'attributes' => array(
                'id' => 'confirmPassword',
                'class' => 'form-control'
            )
        ));
        
        $this->add(array(
            'name' => 'role',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'class' => 'form-control'
            ),
            'options' => array(
                'label' => ' Role',
                'empty_option' => '--Please choose option --',
                'value_options' => $config->getConfig('admin_roles')
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
                'value_options' => $config->getConfig('status')
            )
        ));
        
        // Set input filter
        
        $this->getInputFilter()->add($factory->createInput(array(
            'name' => 'username',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'not_empty'
                ),
                array(
                    'name' => 'DoctrineModule\Validator\UniqueObject',
                    'options' => array(
                        'use_context' => true,
                        'object_manager' => $em,
                        'object_repository' => $em->getRepository('\Entity\StUser'),
                        'fields' => 'username'
                    )
                )
            )
        )));
        
        $this->getInputFilter()->add($factory->createInput(array(
            'name' => 'email',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'not_empty'
                ),
                array(
                    'name' => 'email_address'
                ),
                array(
                    'name' => 'DoctrineModule\Validator\UniqueObject',
                    'options' => array(
                        'use_context' => true,
                        'object_manager' => $em,
                        'object_repository' => $em->getRepository('\Entity\StUser'),
                        'fields' => 'email'
                    )
                )
            )
        )));

        $this->getInputFilter()->add($factory->createInput(array(
            'name' => 'password',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'StringLength',
                    'options' => array(
                        'encoding' => 'UTF-8',
                        'min' => '6'
                    )
                )
            )
        )));
        
        $this->getInputFilter()->add($factory->createInput(array(
            'name' => 'confirmPassword',
            'required' => true,
            'validators' => array(
                array(
                    'name' => 'Identical',
                    'options' => array(
                        'token' => 'password',
                        'message' => 'Not same value with %token%'
                    )
                )
            )
        )));
    }
}
