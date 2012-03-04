<?php

class Application_Model_Mapper_Options extends Application_Model_Mapper_Abstract
{
    protected $_dbTable = 'Application_Model_DbTable_Options';    
    protected $_objectClass = 'Application_Model_Options';
    
    function getBalance()
    {
        $select = $this->getDbTable()->select();
        $select->where('option_cat=?','account');
        $select->where('option_name=?','balance');
        $row = $this->getDbTable()->fetchRow($select);
        if($row !== null){
            return $row->option_value;
        }
    }
    
    function setBalance($bal)
    {
        $data['option_value']=$bal;
        $where['option_cat=?']='account';
        $where['option_name=?']='balance';
        $this->getDbTable()->update($data,$where);
    }
}

?>