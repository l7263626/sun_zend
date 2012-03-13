<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of SubMenu
 *
 * @author chunhsin
 */
class Zend_View_Helper_SubMenu  extends Zend_View_Helper_Abstract{
    //put your code here
    protected $_menuItem = array();
    
    public function SubMenu(){
        return $this;
    }
    
    public function add($text,$url){
        $this->_menuItem[]=array('text'=>$text,'url'=>$url);
    }
    
    public function __toString() {
        $submenu = array();
        foreach($this->_menuItem as $item){
            $submenu[]= "<a href='".$item['url']."'>".$item['text']."</a>";
        }
        return   "<div id=\"sub-menu\">" . implode(" | ",$submenu) . "</div>";
    }
}

?>
