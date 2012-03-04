<?php
class Application_Model_Tools_ParseObject{
    static function parse($option){
        if($option['obj']===null || !is_object($option['obj'])){
            throw new Exception('option[obj]必須指定且為一類別實體!');
        }
        if($option['action']===null || !$option['action'] instanceof Zend_Controller_Action){
            throw new Exception('option[action]必須指定且為Zend_Controller_Action類別!');
        }
        
        $action = $option['action'];
        $obj = $option['obj'];
        $top = isset($option['top'])?"top/".$option['top']."/":"";
        //$obj
        $methods = get_class_methods($obj);
        if($methods){
            echo "<div>方法</div>";
            foreach($methods as $m){
               echo $m ."() , ";
            }        
        }
        $vars = get_object_vars($obj);
        if($vars){
            echo "<div>屬性</div>";
            foreach($vars as $n => $v){
               $extra_i = null;
               $typecolor = "black";
               $var_detail_link = "<span style='color:#c60'>$".$n."</span>";
               if(is_string($v)){
                   $extra_i = $v;
                   $typecolor = "red";               
               }elseif(is_array($v)){
                   $extra_i = implode(", ",array_keys($v));
                   $typecolor = "green";
                   $var_detail_link = "<a href='".$action->basePath()."/info/{$n}/{$top}type/array' style='color:#c60'>$".$n."</a>";
               }elseif(is_object($v)){
                   $typecolor = "blue";
                   $var_detail_link = "<a href='".$action->basePath()."/info/{$n}/{$top}type/object' style='color:#c60'>$".$n."</a>";
               }elseif(is_null($v)){
                   $typecolor = "grey";               
               }           
               echo sprintf("%s (<span style='color:%s'>%s</span>) %s<br/> ",$var_detail_link , $typecolor,gettype($v),($extra_i)?":".$extra_i:"");
            }          
        }
      
    }
}
?>
