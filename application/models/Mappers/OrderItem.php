<?php

class Application_Model_Mapper_OrderItem extends Application_Model_Mapper_Abstract
{
    protected $_dbTable = 'Application_Model_DbTable_OrderItem';    
    protected $_objectClass = 'Application_Model_OrderItem';
 
//     public function save(Application_Model_Abstract $obj)
//     {
//         $data = array(
//             'order_id'   => $obj->getOrderId(),
//             'item'       => $obj->getItem(),
//             'amount'     => $obj->getAmount(),
//             'unit_price' => $obj->getUnitPrice(),
//             'summary'    => $obj->getSummary(),
//             'comment'    => $obj->getComment(),
//         );
//         
//         if($obj->getId()) $data['id'] = $obj->getId();           
//         $this->_save($data);
//     }
}

?>