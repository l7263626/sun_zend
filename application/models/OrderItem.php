<?php

class Application_Model_OrderItem extends Application_Model_Abstract
{
    protected $_itemMapper = null;
    protected $_data = array(
       'id'         => null,
       'order_id'   => null,
       'item'       => null,
       'amount'     => null,
       'unit_price' => null,
       'summary'    => null,
       'comment'    => null        
    );
}

