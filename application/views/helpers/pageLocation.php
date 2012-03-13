<?php
class Zend_View_Helper_PageLocation
{
    protected static $_stack = array(); 
    
    public function pageLocation()
    {
         return $this;    
    }    
    
    public function add($name)
    {
        $this->_stack[] = $name;
        return $this;
    }
    
    public function __toString(){
        if(!empty($this->_stack)){
            return "<div id=\"page-location\">◎".implode(" > ",$this->_stack)."</div>";
        }else{
            return "<div id=\"page-location\">◎</div>";
        }        
    }
}
?>
