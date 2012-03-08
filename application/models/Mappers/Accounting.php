<?php

class Application_Model_Mapper_Accounting extends Application_Model_Mapper_Abstract
{
    protected $_dbTable = 'Application_Model_DbTable_Accounting';    
    protected $_objectClass = 'Application_Model_Accounting';
    
    public function save(Application_Model_Abstract $obj)
    {
        $options = new Application_Model_Mapper_Options();
        $balance = $options->getBalance();
        if($obj->income>0){
            $balance +=$obj->income;
        }else{
            $balance -=$obj->outgo;
        }
        $obj->setBalance($balance);
        if(parent::save($obj))
        {            
            $options->setBalance($balance);
        }
    }

}

?>