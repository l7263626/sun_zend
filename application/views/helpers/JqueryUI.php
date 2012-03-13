<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of JqueryUI
 *
 * @author chunhsin
 */
class Zend_View_Helper_JqueryUI extends Zend_View_Helper_Abstract 
{
    //put your code here
    protected $_dir = '/javascript/jquery.ui/';
    protected $_model = array(
        'sortable'   =>    array(
            'ui' => array('core','widget','mouse','sortable'),
        ),
        'draggable'  =>    array(
            'ui' => array('core','widget','mouse','draggable'), 
        ),
        'droppable'  =>    array(
            'ui' => array('core','widget','mouse','draggable','droppable'),
        ),
        'resizable'  =>    array(
             'ui' => array('core','widget','mouse','resizable'),
        ),
        'selectable'  =>   array(
             'ui'  => array('core','widget','mouse','selectable'), 
        ),
    );
    protected $_loaded = array();
    
    public function JqueryUI(){
        return $this;    
    }
    
    public function load($name,$package='model'){
        $name = strtolower($name);
        switch($package){
            case "model":
                $this->loadModel($name);
                break;
             default:
                $this->loadFile($name,$package); 
                break;
        }
       
    }
    
    public function loadModel($name){
        if($this->_model[$name] !==null ){
            $load = $this->_model[$name];
        }else{
            throw new Exception('不存在指定的jquery.ui名稱:' . $name);
        }
        foreach($load as $type => $items){
            foreach($items as $item){
                $this->loadFile($item, $type);
            }
        }         
    }
    
    public function loadFile($item,$type){
        if(!$this->_loaded[$type][$item]){
            $this->_loaded[$type][$item] = true;
            $scriptFile = $this->_dir . "jquery." . $type . '.' . $item . '.js';
            $this->view->headScript()->appendFile($scriptFile);
        }        
    }
}

?>
