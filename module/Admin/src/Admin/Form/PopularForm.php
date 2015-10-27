<?php

namespace Admin\Form;

use Zend\Form\Form;
use Zend\InputFilter\Factory as InputFactory;

class PopularForm extends Form{

    public $factory;

	public function __construct($em = null){
		parent::__construct(null);

		$this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden'
		));
	}
	
	public function getInputFactory()
	{
	    return new InputFactory();
	}
	
	public function renderRadioButton(\Zend\Form\Element\Radio $element, $disabled = false){
	    $html = '';
	    if($element->getAttribute('type') == 'radio')
	    {
	    	if($disabled){
	    		$disabled = ' disabled';
	    	}
	        $html.='<div class="btn-group" data-toggle="buttons" id="'.$element->getAttribute('name').'">'.PHP_EOL;
	    	$defaul_value = $element->getAttribute('default');
	        $defaul_value = $element->getValue() == null ? $defaul_value : $element->getValue();
	        foreach ($element->getValueOptions() as $key => $value)
	        {
	            $checked = $defaul_value == $key ? 'checked="checked"' : '';
	            $active = $defaul_value == $key ? 'active' : '';
	            $html.='<label class="btn btn-default' . $disabled . ' ' . $active . '"><input type="radio" name="'.$element->getName().'" value="'.$key.'" '.$checked.' /> '.$value.'</label>'.PHP_EOL;
	            
	        }

			$html.='</div>'.PHP_EOL;
	    }
	    return $html;
	    
	}
	
	public function renderInputText(\Zend\Form\Element $element, $viewHelper)
    {
        $html='';
        $types = array('text', 'password', 'email');
            if((in_array($element->getAttribute('type'), $types))){
                //$html .= '<div class="form-group">';
                if($element->getAttribute('input-type') != 'image') {
                    if($element->getAttribute('data-addon') != null){
                        $html .= '<div class="input-group"><span class="input-group-addon ' . $element->getAttribute('data-addon') . '"></span>';
                        $html .= $viewHelper->formElement($element);
                        $html.='</div>';
                    }
                    else
                    {
                    	$html .= $viewHelper->formElement($element);
                    }
                } else {
                    $html.='<div class="input-group"> 
                	   <span class="input-group-addon"><i class="glyphicon glyphicon-picture"></i></span>';
                    $html.= $viewHelper->forminput($element);
                    $html.='<span class="input-group-btn">
                       		<a href="'.$viewHelper->serverurl($viewHelper->url('admin/default',array('controller' => "media", 'action' => 'index'), array('query' => array('target' => '[name="'.$element->getName().'"]', 'type' => 'single')))).'" class="btn btn-success window-open"><i class="glyphicon glyphicon-zoom-in"></i> Browser</a>
                      	    <a data-type="single" data-target="'.urlencode('[name="'.$element->getName().'"]').'" href="javascript:void(0)" class="btn btn-info page-preview-image"><i class="glyphicon glyphicon-eye-open"></i> Preview</a> 	
                       </span>           
                    </div>';
                }
                $html.= $viewHelper->formelementerrors($element, array('class' => 'error'));
                //$html.= '</div>';
            }
        return $html;
    }
}
