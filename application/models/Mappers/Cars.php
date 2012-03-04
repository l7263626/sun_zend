<?php
class Application_Model_Mapper_Cars extends Application_Model_Mapper_Abstract
{
    protected $_dbTable = 'Application_Model_DbTable_Cars';    
    protected $_objectClass = 'Application_Model_Cars'; 
 
//     public function save(Application_Model_Abstract $obj)
//     {
//         $data = array(
//             'car_no'      => $obj->getCarNo(),
//             'car_type'    => $obj->getCarType(),
//             'customer_id' => $obj->getCustomerId(),   
//         );
//         if($obj->getId())$data['id'] = $obj->getId();        
//         if($obj->getLastMaintainDate())$data['last_maintain_date'] = $obj->getLastMaintainDate(); 
//         if($obj->getLastMaintainMileage())$data['last_maintain_mileage'] = $obj->getLastMaintainMileage();
//         $this->_save($data);
//     }    
}

?>