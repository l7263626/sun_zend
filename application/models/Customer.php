<?php

class Application_Model_Customer extends Application_Model_Abstract
{
    protected $_carsMapper = null;
    protected $_data = array(
       'id'        => null,
       'name'      => null,
       'phone'     => null,
       'mobile'    => null,
       'address'   => null,
       'company'   => null,
       'email'     => null,
       'birthday'  => null        
    );
    
    public function getCarsMapper(){
       if($this->_carsMapper === null){
          $this->_carsMapper = new Application_Model_Mapper_Cars();
       }
       return $this->_carsMapper;
    } 
    
    public function getOwnedCars($ids=null){       
       $where = $this->getCarsMapper()->getDbTable()->select();
       $where->where('customer_id=?',$this->getId());
       if($ids!==null){
           if(!is_array($ids))$ids = (array)$ids;           
           $where->where("id in(".implode(",",$ids).")");
       }
       return $this->getCarsMapper()->fetchAll($where); 
    }
    
    public function getOwnedCar($id=null){       
       if($id===null){
           throw new Exception('請指定車籍id!');
       }else{           
           $select = $this->getCarsMapper()->getDbTable()->select();
           $select->where('id=?',$id);          
       }
       return $this->getCarsMapper()->fetchOne($select); 
    }
    
    public function getOwnedCarNums(){       
       $where = $this->getCarsMapper()->getDbTable()->select();
       $where->where('customer_id=?',$this->getId());     
       return $this->getCarsMapper()->count($where); 
    }
    

}

