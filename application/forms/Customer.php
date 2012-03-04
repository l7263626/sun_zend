<?php

class Application_Form_Customer extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
         $this->setMethod("post");
         
         $this->addElement("hidden","id");
         $this->addElement("text","name",array(
             'label'    => "姓名:",
             'required' => true,
             'filters'  => array('StringTrim'),
         ));
         $this->addElement("text","phone",array(
             'label'    => "電話:",
             'required' => true,
             'filters'  => array('StringTrim'),
             'errorMessages' => array('無效電話號碼!格式為00-0000000'),
             'validators' => array(
                 array('validator' =>'Regex', 
                       'options'=>array('pattern'=>'/^\d[\d-]+\d$/')),
             )
         ));
         $this->addElement("text","mobile",array(
             'label'      => "手機:",
             'required'   => true,
             'filters'    => array('StringTrim'),
             'errorMessages' => array('無效手機號碼!格式為0900-000000'),
             'validators' => array(
                 array('validator' =>'Regex',
                       'options'=>array('pattern'=>'/^09\d{2}-\d{6}$/')),
             )
         ));
         $this->addElement("text","address",array(
             'label'    => "地址:",
             'required' => true,
             'filters'  => array('StringTrim'),
         ));
         $this->addElement("text","company",array(
             'label'    => "公司:",
             'required' => true,
             'filters'  => array('StringTrim'),
         ));
         $this->addElement("text","email",array(
             'label'    => "電子郵件:",
             'required' => true,
             'filters'  => array('StringTrim'),
             'validators' => array('EmailAddress'),
         ));
         $this->addElement("text","birthday",array(
             'label'    => "生日:",
             'required' => true,
             'filters'  => array('StringTrim'),
             'validators' => array(
                 array('validator'=>'Date',
                       'options'=>array('format'=>'yyyy-m-d')),
             ),
         ));
    }


}

