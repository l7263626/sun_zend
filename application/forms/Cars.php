<?php

class Application_Form_Cars extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
        $this->addElement("hidden","id");
        $this->addElement("text","car_no",array(
            'filters'  => array(
               array('filter'  =>'StringToUpper',
                     'options' => array('encoding'=>'utf-8'))
            ),
            'required' => true,
        ));
        $this->addElement("text","car_type",array(
            'filters'  => array(
               'StringTrim',
               array('filter'  =>'StringToUpper',
                     'options' => array('encoding'=>'utf-8'))
               ),
            'required' => true,
        ));
        $this->addElement("hidden","customer_id",array(
            'filters'  => array('Digits'),
            'required' => true,
        ));
        $this->addElement("hidden","last_maintain_date",array(            
            'filters' => array('null'),
            'validators' => array(
                array('validator'=>'Date',
                      'options'  => array('format'=>'yyyy-m-d')),
            ),
           
        ));
        $this->addElement("hidden","last_maintain_mileage",array(
            'filters' => array('null'),
            'validators' => array('Int'), 
        ));
    }


}

