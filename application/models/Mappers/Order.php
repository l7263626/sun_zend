<?php

class Application_Model_Mapper_Order extends Application_Model_Mapper_Abstract
{
    protected $_dbTable = 'Application_Model_DbTable_Order';    
    protected $_objectClass = 'Application_Model_Order';

    public function save(Application_Model_Abstract $obj)
    {
//         $data = array(
//             'customer_id'     => $obj->getCustomerId(),
//             'car_id'          => $obj->getCarId(),
//             'in_date'         => $obj->getInDate(),
//             'in_mileage'      => $obj->getInMileage(),
//         );
//         
//         if($obj->getOutDate()!==null)       $data['out_date']        = $obj->getOutDate();
//         if($obj->getOriginalCharge()!=null) $data['original_charge'] = $obj->getOriginalCharge();
//         if($obj->getRealCharge()!==null)    $data['real_charge']     = $obj->getRealCharge();
//         if($obj->getDiscount()!==null)      $data['discount']        = $obj->getDiscount();
//         if($obj->getComment()!==null)       $data['comment']         = $obj->getComment();        
//         if($obj->getId()!==null)            $data['id']              = $obj->getId();
        $data = $obj->toArray();
        $newId = $this->_save($data);
        
        //更新車輛資訊
        if($obj->getCarId() && ($obj->getInDate() || $obj->getInMileage())){
            $cars = new Application_Model_Cars();
            $mapper = new Application_Model_Mapper_Cars();
            $mapper->find($data['car_id'],$cars);
            $cars->setLastMaintainDate($data['in_date']);
            $cars->setLastMaintainMileage($data['in_mileage']);
            $mapper->save($cars);       
        }
        return $newId;        
        
    }

}

?>