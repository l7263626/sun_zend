<?php

class AdminController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */  
        $auth = Zend_Auth::getInstance();
        if(!$auth->hasIdentity()){
           $this->_redirect("/index/login");
        }
        $this->view->identity = $auth->getIdentity();              
        $this->boot = $this->getInvokeArg('bootstrap');
        $this->db = $this->boot->getResource('db');
        //初始化layout
        $this->layout=$this->_helper->layout();
        $this->layout->setLayout('main');
        require "../application/configs/menu.php";
        $this->layout->menu = $mainMenu;
        $this->layout->pageTitle = "【陽陞汽車】設定-";
        
    }

    public function indexAction()
    {
        // action body
        $this->_forward("document","admin");
    }

    public function documentAction()
    {
        // action body
        $this->view->pageLocation()->add("首頁")->add("設定")->add("公司基本資料");
        //$this->view->pageLocation = "◎首頁 > 設定 > 公司基本資料";
        $this->layout->pageTitle .= "公司基本資料"; 
        
        //表單上要儲存的欄位名稱
        $inColumns = array("company","telephone","mobile","address");
        //要儲存進去的資料表名稱
        $table = "options";
        $option_cat = "company";
        if($this->_request->isPost()){
           foreach($inColumns as $col){
               $stmt = $this->db->query("select * from ".$table . " where option_cat = '{$option_cat}' and option_name = '{$col}'");
               if($stmt->rowCount()){
                   $this->db->update($table,array("option_value"=>$_POST[$col]),"option_cat='{$option_cat}' and option_name='{$col}'");                  
               }else{
                   $this->db->insert($table,array('option_cat'=>$option_cat,"option_name"=>$col,"option_value"=>$_POST[$col]));                   
               }               
           }
           $this->_redirect("/admin/document");           
        }
        //$option_company = $this->db->fetchAll("select * from ".$table." where option_cat ='{$option_cat}'");
        $stmt = $this->db->query("select * from ".$table." where option_cat ='{$option_cat}'");
        //foreach($option_company as $row){
        while($row = $stmt->fetch()){
            $this->view->{$row['option_name']} = $row['option_value'];            
        }
               
    }

    public function customerAction()
    {
        // action body
        $this->view->pageLocation()->add('首頁')->add('設定')->add('客戶資料');        
        $this->layout->pageTitle .= "客戶資料"; 
        $customerMapper = new Application_Model_Mapper_Customer(array(
            'divideNums'=> 20,             
            'pageId' =>  'p' 
        ));
        $this->view->headScript()->appendFile("/javascript/checkbox_multicheck.js");
        $this->view->inlineScript()->appendScript("
            $('#delete_smt').click(function(){
                if($(':checkbox:gt(0)').map(function(){
                    if(this.checked) return this;                         
                }).size()>0){
                    if(confirm('確定刪除?')){
                       $('#customer_list_frm').submit();
                    }                    
                }else{
                    alert('請先選擇欲刪除的項目!');
                }                
            });
        ");
        $this->view->customerArray = $customerMapper->fetchAll();
        $this->view->pageController = $customerMapper->getPageController()->getHTML();   
    }
    public function carsAction(){
        $this->view->pageLocation()->add("首頁")->add("設定")->add("車籍資料");
        //$this->view->pageLocation = "◎首頁 > 設定 > 車籍資料";
        $this->layout->pageTitle .= "車籍資料";
        $customerMapper = new Application_Model_Mapper_Customer();
        $customer = new Application_Model_Customer();
        $customerMapper->find($this->_getParam('owner'),$customer);        
        $carsMapper = new Application_Model_Mapper_Cars();
        $select = $carsMapper->getDbTable()->select();
        $select->where('customer_id=?',$customer->id);
        $carsArray = $carsMapper->fetchAll($select); 
        $this->view->owner = $customer;
        $this->view->carsArray = $carsArray;         
    }

    public function supplierAction()
    {
        // action body
        $this->view->pageLocation()->add("首頁")->add("設定")->add("廠商資料");
        //$this->view->pageLocation = "◎首頁 > 設定 > 廠商資料";
        $this->layout->pageTitle .= "廠商資料";        
        $supplierMapper = new Application_Model_Mapper_Supplier(array(
            'divideNums'=> 20,             
            'pageId' =>  'p' 
        ));
        $this->view->headScript()->appendFile("/javascript/checkbox_multicheck.js");
        $this->view->inlineScript()->appendScript("
            $('#delete_smt').click(function(){
                if($(':checkbox:gt(0)').map(function(){
                    if(this.checked) return this;                         
                }).size()>0){
                    if(confirm('確定刪除?')){
                       $('#supplier_list_frm').submit();
                    }                    
                }else{
                    alert('請先選擇欲刪除的項目!');
                }                
            });
        ");
        $this->view->supplierArray = $supplierMapper->fetchAll();
        $this->view->pageController = $supplierMapper->getPageController()->getHTML();   
    }

    public function checkitemAction()
    {
        // action body
        if($this->_request->isPost()){                      
           //print_r($_POST);
            $k=0;           
            foreach($_POST['id'] as $id){
                $newsort = $k+1;
                $this->db->update('checkitems',array('sort'=>$newsort),array('id=?'=>$id));
                $k++;
            }
            die();
        }
        $this->view->pageLocation()->add("首頁")->add("設定")->add("檢查項目");
        $this->layout->pageTitle .= "檢查項目";        
        $this->view->HeadScript()->appendFile("/javascript/jquery-ui-1.8.16.custom.min.js");
        $this->view->HeadScript()->appendFile("/javascript/jquery.ui/jquery.ui.core.js");
        $this->view->HeadScript()->appendFile("/javascript/jquery.ui/jquery.ui.widget.js");  
        $this->view->HeadScript()->appendFile("/javascript/jquery.ui/jquery.ui.mouse.js");
        $this->view->HeadScript()->appendFile("/javascript/jquery.ui/jquery.ui.sortable.js");
        $this->view->inlineScript()->appendScript("
           $('#sortable').sortable({
              update:function(e,ui){
                 $.ajax({
                     data:$('#ajaxfrm').serialize(),
                     type:'post',
                     success:function(str){
                        //alert(str);
                     }
                 })   
              }
           });
           $('#sortable').disableSelection();
        ");
        $this->view->checkitems =  $this->db->fetchAll("select * from `checkitems` order by sort,id");
    }

    public function addAction()
    {
        // action body
        if($this->_request->isPost()){
            if($this->_getParam('target')=="checkitem"){
                //echo $this->_request->getPost('item');
                $this->db->insert("checkitems",array('item'=>$this->_request->getPost('item')));
                $this->_redirect("/admin/checkitem");                
            }elseif($this->_getParam('target')=="customer"){
                $form = new Application_Form_Customer(); 
                if($form->isValid($this->_request->getPost())){
                    $customerMapper = new Application_Model_Mapper_Customer();
                    $customer = new Application_Model_Customer($form->getValues());
                    $customerMapper->save($customer);
                    $this->_redirect("/admin/customer");                
                }
            }elseif($this->_getParam('target')=="supplier"){
                $form = new Application_Form_Supplier(); 
                if($form->isValid($this->_request->getPost())){
                    $supplierMapper = new Application_Model_Mapper_Supplier();
                    $supplier = new Application_Model_Supplier($form->getValues());
                    $supplierMapper->save($supplier);
                    $this->_redirect("/admin/supplier");                
                }
            }elseif($this->_getParam('target')=="cars"){
                $form = new Application_Form_Cars();                
                if($form->isValid($this->_request->getPost())){
                    $carsMapper = new Application_Model_Mapper_Cars();
                    $cars = new Application_Model_Cars($form->getValues());
                    $carsMapper->save($cars);
                    $this->_redirect("/admin/customer");                
                }                
            }
            $this->view->errorData = $this->_request->getPost();
            if($form !== null){
                $this->view->errorMessages = array();
                foreach($form->getMessages() as $col => $errorMessages){
                    $this->view->errorMessages[$col] = implode(",",$errorMessages);
                }            
            }
        }
        $this->view->pageLocation = "◎首頁 >  設定 > 新增 > ";
        $this->layout->pageTitle .= "新增";
        $sub_script_name = $this->_getParam('target')."_add.phtml";
        if($this->_getParam('target')=="checkitem"){
            $this->view->pageLocation .= "檢查項目";            
        }elseif($this->_getParam('target')=="customer"){
            $this->view->pageLocation .= "客戶資料";                  
        }elseif($this->_getParam('target')=='cars'){
            $this->view->pageLocation .= "車籍資料";
            $this->view->owner = $this->_getParam('owner');
        }elseif($this->_getParam('target')=="supplier"){
            $this->view->pageLocation .= "廠商資料";
        } 
        if($this->view->getScriptPath("admin/".$sub_script_name)){
            $this->view->sub_script = $this->view->getScriptPath("admin/".$sub_script_name);
        }else{
            throw new Exception($sub_script_name." do not exists!");
        }       
    }
    
    public function updateAction()
    {
        // action body
        if($this->_request->isPost()){
            if($this->_getParam('target')=="checkitem"){
                //echo $this->_request->getPost('item');
                $this->db->update("checkitems",array('item'=>$this->_request->getPost('item')),array("id=?"=>$this->_request->getPost('id')));
                $this->_redirect("/admin/checkitem");                
            }elseif($this->_getParam('target')=="customer"){
                $form = new Application_Form_Customer();
                $customerMapper = new Application_Model_Mapper_Customer();
                $customer = new Application_Model_Customer();
                if($form->isValid($this->_request->getPost())){                    
                    $customer->setOptions($form->getValues());
                    $customerMapper->save($customer);
                    $this->_redirect("/admin/customer");                
                }
                $customer->setOptions($this->_request->getPost());
            }elseif($this->_getParam('target')=="supplier"){
                $form = new Application_Form_Supplier();
                $supplierMapper = new Application_Model_Mapper_Supplier();
                $supplier = new Application_Model_Supplier();
                if($form->isValid($this->_request->getPost())){                    
                    $supplier->setOptions($form->getValues());
                    $supplierMapper->save($supplier);
                    $this->_redirect("/admin/supplier");                
                }
                $supplier->setOptions($this->_request->getPost());
            }elseif($this->_getParam('target')=="cars"){
                $form = new Application_Form_Cars();
                $cars = new Application_Model_Cars();
                $carsMapper = new Application_Model_Mapper_Cars();
                if($form->isValid($this->_request->getPost())){
                    $cars->setOptions($form->getValues());
                    $carsMapper->save($cars);
                    $this->_redirect("/admin/cars/owner/".$cars->getCustomerId());
                }
                $cars->setOptions($this->_request->getPost());
            }
            if($form !== null){
                $this->view->errorMessages = array();
                foreach($form->getMessages() as $col => $errorMessages){
                    $this->view->errorMessages[$col] = implode(",",$errorMessages);
                }            
            }
        }
        $this->view->pageLocation = "◎首頁 >  設定 > 更新 > ";
        $this->layout->pageTitle .= "更新";
        
        $sub_script_name = $this->_getParam('target')."_update.phtml";
        $this->view->sub_script = $this->view->getScriptPath("admin/".$sub_script_name);        
        if(!$this->view->getScriptPath("admin/".$sub_script_name)){
            throw new Exception('不存在'.$sub_script_name."!");
        }
        
        if($this->_getParam('target')=="checkitem"){
            $this->view->pageLocation .= "檢查項目";            
            $row = $this->db->fetchRow("select * from checkitems where id=?",array($this->_getParam('id')));            
            if($row){
                $this->view->data = $row;                
            }  
        }elseif($this->_getParam('target')=="customer"){
            $this->view->pageLocation .= "客戶資料";
            if(!isset($customer)){
                $customer = new Application_Model_Customer();
                $customerMapper = new Application_Model_Mapper_Customer();
                $customerMapper->find($this->_getParam('id'),$customer);
            }
            $this->view->data = $customer;
        }elseif($this->_getParam('target')=="supplier"){
            $this->view->pageLocation .= "廠商資料";
            if(!isset($supplier)){
                $supplier = new Application_Model_Supplier();
                $supplierMapper = new Application_Model_Mapper_Supplier();
                $supplierMapper->find($this->_getParam('id'),$supplier);
            }
            $this->view->data = $supplier;
        }elseif($this->_getParam('target')=="cars"){
            $this->view->pageLocation .= "車籍資料";
            if(!isset($cars)){
                $customer = new Application_Model_Customer();
                $customerMapper = new Application_Model_Mapper_Customer();
                $customerMapper->find($this->_getParam('owner'),$customer);
                $this->view->ownedCar = $customer->getOwnedCar($this->_getParam('car'));            
            }else{
                $this->view->ownedCar = $cars;
            }
        }      
    }
    
    public function deleteAction(){        
        if($this->_request->isPost()){
            if($this->_getParam('target')=="customer"){
                $customerMapper = new Application_Model_Mapper_Customer();
                $customerMapper->delete($this->_request->getPost('delete'));
                $this->_redirect("/admin/customer");
            }elseif($this->_getParam('target')=="supplier"){
                $supplierMapper = new Application_Model_Mapper_Supplier();
                $supplierMapper->delete($this->_request->getPost('delete'));
                $this->_redirect("/admin/supplier");            
            }
        }
        $this->_redirect("/");
    }
    
    public function basePath()
    {
        return "/" . $this->_getParam('controller') . "/" . $this->_getParam('action'); 
    }

    public function postDispatch()
    {
        if($this->_getParam("info")){
            $object_var = $this->_getParam("info");
            $object_type = $this->_getParam("type");            
            if($object_top = $this->_getParam('top')){
                $top_arr = explode('-',$object_top);
            }            
            $top_object = $this;
            $top_str = (($object_top)? $object_top . "-":"").$object_var;            
            if(!empty($top_arr)){
                foreach($top_arr as $top){
                    $top_object = $top_object->{$top};                    
                }
            }           
            $object_class = ($this->_getParam("type")=="object")?":".get_class($top_object->{$object_var}):""; //如果是物件就顯示類別名稱
            echo "<div style='margin-left:20px;margin-bottom:20px;'>";
            echo "<div>屬性 ".$top_str." 的內容(".$object_type. $object_class . ")</div>";
            if($object_type == "array"){
                echo "<pre>";
                print_r($top_object->{$object_var});
                echo "</pre>";            
            }elseif($object_type == "object"){
                Application_Model_Tools_ParseObject::parse(array('obj'=>$top_object->{$object_var},'action'=>$this,'top'=>$top_str));
            }            
            echo "</div>";
        }
        Application_Model_Tools_ParseObject::parse(array('obj'=>$this,'action'=>$this));        
        
    }
}
?>
