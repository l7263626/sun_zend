<?php

class Application_Model_Mapper_Customer extends Application_Model_Mapper_Abstract
{
    protected $_dbTable = 'Application_Model_DbTable_Customer';
    protected $_objectClass = 'Application_Model_Customer';     
 
//     public function save(Application_Model_Abstract $obj)
//     {
//         $data = array(
//             'name'     => $obj->getName(),
//             'phone'    => $obj->getPhone(),
//             'mobile'   => $obj->getMobile(),
//             'address'  => $obj->getAddress(),
//             'company'  => $obj->getCompany(),
//             'email'    => $obj->getEmail(),
//             'birthday' => $obj->getBirthday(),   
//         );        
//         $this->_save($data);
//     }
}

?>