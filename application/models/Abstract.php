<?php
abstract class Application_Model_Abstract
{
    /*
    下列是可能需要使用者自訂的變數內容
    */
    protected $_data = array();               //上傳表單的欄位名稱

    /*
    上列是可能需要使用者自訂的變數內容
    */
 
    public function __construct(array $options = null)
    {
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }
 
    public function __set($name, $value)
    {
        //$data=array_keys($this->_data);
        //if (!in_array($name,$data)) {
        if (!$this->hasData($name)) {
            //throw new Exception('Invalid property: '.$name);
            $this->{$name} = $value;
        }else{
            $this->setData($name,$value);
        }        
    }
 
    public function __get($name)
    { 
        if (!$this->hasData($name)) {
            throw new Exception('Invalid property:' . $name);
        }
        return $this->getData($name);
    }
    
    public function __call($name,$arguments){
        if(substr($name,0,3)=='set' || substr($name,0,3)=='get'){
            $property_name='';
            for($i=3;$i<strlen($name);$i++){
               if(ord(substr($name,$i,1))>=65 && ord(substr($name,$i,1))<=90 && $i>3){
                  $property_name .= '_';
               }
               $property_name .= strtolower(substr($name,$i,1));
            }
            if (!$this->hasData($property_name)) {
               throw new Exception('Invalid property :'.$property_name);
            }
            if(substr($name,0,3)=='set'){
                return $this->setData($property_name,$arguments[0]);
            }else{
                return $this->getData($property_name);
            }
        }else{
            throw new Exception('Invalid methods:'. $name);
        } 
    }
    
    public function toArray(){
        return $this->_data;
    }
    
    public function setData($name,$value){
        $this->_data[$name]=$value;
        return $this;
    }
     
    public function getData($name){
        return $this->_data[$name];
    }
     
    public function setOptions(array $options)
    {
        foreach ($options as $key => $value) {
            if ($this->hasData($key)) {
                $this->setData($key,$value);
            }
        }
        return $this;
    }
   
    public function hasData($name){
        static $data;
        if($data === null){
            $data=array_keys($this->_data);
        }
        if(in_array($name,$data)){
            return true;
        }else{
            return false;
        }        
    }
}

?>