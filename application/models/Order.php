<?php

class Application_Model_Order extends Application_Model_Abstract
{
    protected $_customer = null;
    protected $_car = null;
    protected $_item = null;
    protected $_data = array(
       'id'              => null,
       'customer_id'     => null,
       'car_id'          => null,
       'in_date'         => null,
       'in_mileage'      => null,
       'out_date'        => null,
       'original_charge' => null,
       'real_charge'     => null,
       'discount'        => null,
       'comment'         => null,        
    );
    
    public function getCustomer()
    {
        if($this->_customer === null && $this->customer_id !==null)
        {
            $mapper = new Application_Model_Mapper_Customer();
            $customer = new Application_Model_Customer();
            $mapper->find($this->customer_id,$customer);
            $this->_customer = $customer;
        }
        return $this->_customer;
    }

    public function getCar()
    {
        if($this->_car === null && $this->car_id !==null)
        {
            $mapper = new Application_Model_Mapper_Cars();
            $car = new Application_Model_Cars();
            $mapper->find($this->car_id,$car);
            $this->_car = $car;
        }
        return $this->_car;
    }
    
    protected function _getItemMapper()
    {
        if($this->_item === null)
        {
            $mapper = new Application_Model_Mapper_OrderItem();
            $this->_item = $mapper;
        }
        return $this->_item;
    }
    
    public function getOrderItem()
    {
        $mapper = $this->_getItemMapper();            
        $select = $mapper->getDbTable()->select();
        $select->where('order_id=?',$this->id);
        $item = $mapper->fetchAll($select);
        return $item;
    }
    
    public function addItem($item)
    {        
        $this->_getItemMapper()->save($item);
        $this->update();
    }
    
    public function delItem($id)
    {
        $this->_getItemMapper()->delete($id);
        $this->update();
    }
    
    public function update()
    {
        if($this->getOrderItem()){
            $new_summary = 0;
            foreach($this->getOrderItem() as $item){
                $new_summary += $item->getSummary();
            }
            $this->setOriginalCharge($new_summary);                      
        }       
    }
}

