<?php

class Application_Form_TodoList extends Zend_Form
{

    public function init()
    {
        /* Form Elements & Other Definitions Here ... */
         $this->setMethod("post");
         
         $this->addElement("hidden","id");
         
         $this->addElement("text","title",array(
             'label'    => "標題:",
             'required' => true,
             'filters'  => array('StringTrim'),
         ));

         $this->addElement("textarea","detail",array(
             'label'    => "細節:",
             'required' => true,
             'filters'  => array('StringTrim'),
         ));
         
         $this->addElement("text","prio",array(
             'label'    => "排序:",
             'required' => false,
             'filters'  => array('Int'),
             'validators' => array("Int")
         ));
         
         $this->addElement("text","done",array(
             'label'      => "完成日:",
             'required'   => false,
             'validators' => array(
                 array('validator' =>'Date',
                       'options'=>array('formate'=>'yyyy-mm-dd')),
             )
         ));

    }


}

