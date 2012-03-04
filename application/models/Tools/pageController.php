<?php
class Application_Model_Tools_pageController{
   protected $_totalNums     = 0;
   protected $_divideNums    = 10;
   protected $_pages         = 0;
   protected $_pageContainer = array();
   protected $_type          = 'simple';
   protected $_pageId        = 'p';
   protected $_qsParser;
   protected $_separator = 'images/page_b.jpg';
   
   public function __construct($options=null){
       if($options!==null){
           $this->setOptions($options);
       }
       $this->compute();
   }
   
   public function setOptions($options){
      if(is_array($options)){
          foreach($options as $key=>$value){
              switch($key){
                  case "divideNums":
                     $this->_divideNums = $value;
                     break;
                  case "totalNums":
                     $this->_totalNums = $value;
                     break;
                  case "type":
                     $this->_type = $value;
                     break; 
                  case "pageId":
                     $this->_pageId = $value;
                     break;
                  case "qsParser":
                     $this->_qsParser = $value;
                     break;           
              }
          }
      }
   }
   public function compute(){
       $this->_pages=@ceil($this->_totalNums/$this->_divideNums);
       $this->makeArray();
   }
   
   public function getHTML(){
       $this->compute();
       switch($this->_type){
           case "morerich":
              return $this->getMoreRich();
              break;
           case "simple":
           default:
              return implode(" ",$this->_pageContainer);
              break;
       }
   }
   public function makeArray(){       
       for($i=1;$i<=$this->_pages;$i++){
          $qs = $this->_qsParser->modQS(array($this->_pageId=>$i))->toString();
          $this->_pageContainer[$i-1]= ($i==$_GET[$this->_pageId] || ($_GET[$this->_pageId]=="" && $i==1))? "<span class='mark'>".$i."</span>":"<a href='?".$qs."'>".$i."</a>";
       }
   }   
   
   public function getContainer(){
       return $this->_pageContainer;
   }
   
   public function getMoreRich(){
       //$tmp_str=implode(" <img src='".$this->_separator."'/> ",$this->_pageContainer);
       $tmp_str="";
       if($this->_pages>0){
           for($i=0;$i<count($this->_pageContainer);$i++){
               $tmp_str .= "<td class='page-links'>". $this->_pageContainer[$i] . "</td>";
               if($i != count($this->_pageContainer)-1){
                   $tmp_str .= "<td class='page-links'>" . sprintf("<img src='%s'/>",$this->_separator) . "</td>" ;
               }
           }
           $first_qs = $this->_qsParser->modQS(array($this->_pageId=>1))->toString();
           $last_qs = $this->_qsParser->modQS(array($this->_pageId=>count($this->_pageContainer)))->toString();
           $tmp_str = "<td class='page-w'><a  href='?".$first_qs."'>第一頁</a></td>" . $tmp_str . "<td class='page-w'><a  href='?".$last_qs."'>最後</a></td>";             
       }
       return  $tmp_str;       
   }

}
?>
