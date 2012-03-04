<?php

class Application_Form_OrderItem extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->addElement("hidden","id",array(
            'validators' => array("Int"),
        ));
        $this->addElement("text","order_id",array(
            'required'   => true,
            'validators' => array("Int"),
        ));
        $this->addElement("text","item",array(
            'required' => true,
        ));
        $this->addElement("text","amount",array(
            'required' => true,
            'validators' => array("Int"),
        ));
        $this->addElement("text","unit_price",array(
            'required' => true,
            'validators' => array("Int"),
        ));
        $this->addElement("text","summary",array(
            'required' => true,
            'validators' => array("Int"),
        ));
        $this->addElement("text","comment"); 
    }


}

