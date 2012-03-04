<?php

abstract class Application_Model_Mapper_Abstract
{
    protected $_dbTable;
    protected $_objectClass;
    /*
    **$_options['divideNums'] 每頁顯示數量
    **$_options['totalNums']  總數
    **$_options['pageId']     頁數變數名稱
    */
    protected $_options = array();
    protected $_pageController = null;
    
    public function __construct($options=null){
       if($options!==null){
           $this->setOptions($options);
       }
       if($this->_objectClass === null){
           throw Exception("請指定objectClass!");
       }
       if($this->_dbTable === null){
           throw Exception("請指定dbTable!");
       }else{
           $this->setDbTable($this->_dbTable);
       }   
    } 
         
    public function setOptions($options){
       if(is_array($options)){
          $this->_options = array_merge($this->_options,$options);
       }       
    }
    
    public function getOptions(){
       return $this->_options;
    } 
    
    public function setDbTable($dbTable)
    {
        if (is_string($dbTable)) {
            $dbTable = new $dbTable();            
        }
        if (!$dbTable instanceof Zend_Db_Table_Abstract) {
            throw new Exception('Invalid table data gateway provided');
        }
        $this->_dbTable = $dbTable;
        return $this;
    }
 
    public function getDbTable()
    {
        return $this->_dbTable;
    }
 
    public function save(Application_Model_Abstract $obj)
    {
        $data = $obj->toArray();
        return $this->_save($data);

    }
    
    protected function _save($data){
        foreach($data as $col => $v){
            if($v===null){
                unset($data[$col]);
            }
        }
        if (!isset($data['id'])) {            
            $this->getDbTable()->insert($data);
            return $this->getDbTable()->getAdapter()->lastInsertId();
        } else {
            $this->getDbTable()->update($data, array('id = ?' => $data['id']));
            return $data['id'];
        }    
    }
 
    public function find($id, Application_Model_Abstract $obj)
    {
        $result = $this->getDbTable()->find($id);
        if (0 == count($result)) {
            return;
        }
        $row = $result->current()->toArray();
        
        $obj->setOptions($row);        
    }
 
    public function fetchAll($where=null,$order=null)
    {
        $resultSet = $this->getDbTable()->fetchAll($where,$order);
        $entries = array();
        if(isset($this->_options['divideNums'])){
            $this->_options['totalNums']=$resultSet->count();
            $offset=(((int)$_GET[$this->_options['pageId']]?$_GET[$this->_options['pageId']]:1)-1)*$this->_options['divideNums'];
            $nums=0;            
            while(($offset+$nums) < $this->_options['totalNums'] && $nums<$this->_options['divideNums'] ) {
                $row = $resultSet->offsetGet($offset+$nums)->toArray();
                $entry = new $this->_objectClass();
                $entry->setOptions($row);             
                $entries[] = $entry;
                $nums++;
            }        
        }else{
            $entries = $this->fetchArray($where,$order);   
        }
        return $entries;
    }
    
    public function count($where)
    {
        $resultSet = $this->getDbTable()->fetchAll($where);
        return $resultSet->count();
    }
        
    public function fetchArray($where=null,$order=null)
    {
        $resultSet = $this->getDbTable()->fetchAll($where,$order);
        $entries = array();
        while($row = $resultSet->current()) {                   
            $entry = new $this->_objectClass();
            $entry->setOptions($row->toArray());             
            $entries[] = $entry;
            $resultSet->next();
        }
        return $entries;
    }    

    public function fetchOne($where=null,$order=null)
    {
        $resultSet = $this->getDbTable()->fetchAll($where,$order);
        $entry = new $this->_objectClass();
        $row = $resultSet[0];
        if($row!==null){
           $entry->setOptions($row->toArray());
           return $entry;   
        }
        
    }
    
    public function delete($idArr){
        if($idArr){
            if(!is_array($idArr))$idArr= (array)$idArr;
//             $db = $this->getDbTable()->getAdapter();
//             $s = $db->select(); //刪除訂單後續資料用的
            $select = $this->getDbTable()->select(); //刪除訂單資料用的
            foreach($idArr as $id){
//                $obj=new Application_Model_Customer();
//                $this->find($id,$obj);
//                $s->orWhere('orderid=?',$obj->getOrderid());
               $select->orWhere('id=?',$id);
            }
            $where = implode(' ', $select->getPart('where'));

            //刪除主要訂單資料
            return  $this->getDbTable()->delete($where);            
        }
        return false;
    }
    
    public function getPageController(){
       if($this->pageController === null){
           $options = $this->getOptions();
           $options['qsParser'] = new Application_Model_Tools_QueryStringParser();
           $this->pageController = new Application_Model_Tools_pageController($options);
       }
       return $this->pageController;
    
    }

}

?>