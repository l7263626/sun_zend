<?php
class Application_Model_Tools_QueryStringParser{
   private $qsArr=array();
   private $_qsArr=array();
   private $qsStr="";
   
   public function __construct(){
      $this->qsArr  = &$_GET;
      $this->_qsArr = $this->qsArr;
   }
   
   public function addQS($qsArr,$update=false){
      $this->reset(); 
      if(is_array($qsArr)){
          foreach($qsArr as $k=>$v){
              if( (isset($this->_qsArr[$k]) && $update) || !isset($this->_qsArr[$k])){
                  $this->_qsArr[$k]=$v;
              }
          }          
      }
      return $this;
   }
   
   public function modQS($qsArr){
       return $this->addQS($qsArr,true);
   }
   
   public function rmQS($qsItem=Array()){
       $this->reset();
       foreach($qsItem as $item){
          if(isset($this->_qsArr[$item])){
             unset($this->_qsArr[$item]);
          } 
       }
       return $this;   
   }
    
   public function toString(){
       $cnt=0;
       foreach($this->_qsArr as $k=>$v){
           $this->qsStr .= (($cnt>0)?"&":""). $k . "=" . $v;
           $cnt++;
       }
       return $this->qsStr;
   }
   
   public function reset(){
       $this->_qsArr = $this->qsArr;//修改用的 
       $this->qsStr=""; 
       return $this;
   }

}
?>
