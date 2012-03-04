<?php

class Application_Form_Order extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->addElement("hidden","id",array(
            'validators' => array('Int'),   
        ));
        $this->addElement("text","customer_id",array(
            'required' => true,
            'validators' => array('Int'),   
        ));
        $this->addElement("text","car_id",array(
            'required' => true,   
            'validators' => array('Int'),
        ));
        $this->addElement("text","in_date",array(
            'required' => true,   
            'validators' => array(
                array('validator'=> 'Date',
                      'options'  => array('format'=>'yyyy-m-d')),
            ),
        ));
        $this->addElement("text","in_mileage",array(
            'required' => true,
            'validators' => array("Int"),   
        ));
        $this->addElement("text","out_date",array(
            'validators' => array(
                array('validator'=> 'Date',
                      'options'  => array('format'=>'yyyy-m-d')),
            ),        
        ));
        $this->addElement("text","original_charge",array(
            'validators' => array("Int"),
        ));
        $this->addElement("text","real_charge",array(
            'validators' => array("Int"),
        ));
        $this->addElement("text","discount");
        $this->addElement("textarea","comment");
    }


}

