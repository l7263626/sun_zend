<?php

class FormController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */        
        $auth = Zend_Auth::getInstance();
        if(!$auth->hasIdentity()){
           $this->_redirect("/index/login");
        } 
        $this->view->identity = $auth->getIdentity();  
        $this->layout=$this->_helper->layout();
        $this->layout->setLayout('main');
        require "../application/configs/menu.php";
        $this->layout->menu = $mainMenu;
        $this->layout->pageTitle = "【陽陞汽車】表單-";
    }

    public function indexAction()
    {
        // action body
         $this->_forward("checklist","form");
    }

    public function checklistAction()
    {
        // action body
        $this->layout->pageTitle .= "檢查單";
        $this->view->pageLocation()->add("首頁")->add("表單")->add("檢查單");
        $options['divideNums']=20;
        $orderMapper = new Application_Model_Mapper_Order($options);
        $this->view->data = $orderMapper->fetchAll();
        $this->view->pager = $orderMapper->getPageController()->getHTML();
    }

    public function applicationAction()
    {
        // action body
        $this->layout->pageTitle .= "委修單";
        $this->view->pageLocation()->add("首頁")->add("表單")->add("委修單");
        $this->view->headScript()->appendFile("/javascript/checkbox_multicheck.js");        
        
        $mapper = new Application_Model_Mapper_Order();
        $this->view->data = $mapper->fetchAll();        
    }

    public function accountAction()
    {
        // action body
        $this->layout->pageTitle .= "收支表";
        $this->view->pageLocation()->add("首頁")->add("表單")->add("收支表");
        $mapper = new Application_Model_Mapper_Accounting();
        $this->view->data = $mapper->fetchAll();
    }

    public function payedAction()
    {
        // action body
        $this->view->pageLocation()->add("首頁")->add("表單")->add("支出表");
    }
    
    public function viewAction()
    {
        $this->view->pageLocation()->add("首頁")->add("表單")->add("檢視");
        $this->layout->pageTitle .= "檢視";
        $sub_script_name = $this->_getParam('target')."_view.phtml";
        switch($this->_getParam('target')){
            case "application":
                $this->view->pageLocation()->add("委修單");
                $order_id = $this->_getParam('id');
                $mapper = new Application_Model_Mapper_Order();
                $order = new Application_Model_Order();
                $mapper->find($order_id,$order);
                $this->view->order = $order;
                $this->view->orderitem = $order->getOrderItem();
                break;
        }
        if($this->view->getScriptPath("form/".$sub_script_name)){
            $this->view->sub_script = $this->view->getScriptPath("form/".$sub_script_name);
        }else{
            throw new Exception($sub_script_name." do not exists!");
        }           
    }
    public function updateAction()
    {
        $this->view->pageLocation()->add("首頁")->add("表單")->add("更新");
        $this->layout->pageTitle .= "更新";
        $sub_script_name = $this->_getParam('target')."_update.phtml";
        switch($this->_getParam('target')){
            case "application":
                if($this->_request->isPost()){
                    $mapper = new Application_Model_Mapper_Order();
                    $order = new Application_Model_Order();
                    $mapper->find($this->_getParam('id'),$order);
                    if($this->_request->getPost('comment')){
                        $order->setComment($this->_request->getPost('comment'));
                    }
                    if($this->_request->getPost('real_charge')){
                        $order->setRealCharge($this->_request->getPost('real_charge'));
                    }
                    if($this->_request->getPost('checkout')){
                        /* 出廠:
                         * 1)計算折扣 2)寫入出廠日期 3)寫入收支表
                         */
                        $order->setOutDate(date('Y-m-d'));
                        $discount = 1 - ( $order->real_charge / $order->original_charge );
                        $order->setDiscount($discount);
                        $order->writeAccount();//寫入收支表
                        $mapper->save($order);
                        $this->_redirect('/form/application/');
                    }else{
                        /*儲存資料
                         */
                        $mapper->save($order);
                        $this->_redirect('/form/update/target/application/id/'.$order->id);
                    }                    
                }
                $this->view->pageLocation()->add("委修單");
                $order_id = $this->_getParam('id');
                $mapper = new Application_Model_Mapper_Order();
                $order = new Application_Model_Order();
                $mapper->find($order_id,$order);
                if($order->getOutDate())$this->_redirect('/form/view/target/application/id/'.$order->getId());
                $this->view->order = $order;
                $this->view->orderitem = $order->getOrderItem();               
                $this->view->headScript()->appendScript("
                    var btn_add_new_row = $('<a href=\"javascript:\" class=\"link_add_row\">新增</a>').click(add_new_row).get(0);
                    var btn_del_row = $('<a href=\"javascript:\" class=\"link_del_row\">刪除</a>').click(del_row).get(0);
                    function del_row(){
                        if(confirm('確定刪除?')){
                            var row = $(this).parent().parent(); 
                            if($(row).attr('key')!=undefined){
                                var data = new Object();
                                data.order_id = {$order_id};
                                data.id = $(row).attr('key');
                                //刪除表格列
                                $(row).remove();
                                //重新計算合計值
                                $('#order_item').trigger('change');
                                //刪除資料庫數據
                                $.post('/form/delorderitem',data); 
                            }                        
                        }
                       
                    }
                    function add_new_row(){
                        var new_row = $('#prepared_row').clone(true).show().get(0);
                        $(new_row).find(':input:last').after(btn_add_new_row);
                        if($('#sum_row').prev().find('a').size()==0){
                           $('#sum_row').prev().find(':input:last').after($(btn_del_row).clone(true));
                        }
                        $('#sum_row').before(new_row);
                    }
                    function save_row(){
                        var row = this;
                        var url = '/form/saveorderitem/order/{$order->id}';
                        var data = new Object();
                        if($(row).attr('key')!=''){
                            data.id = $(row).attr('key');                            
                        }                                                
                        $(row).find(':input').each(function(){
                             data[this.name] = this.value;
                        });
                        if(data.item!='' && data.amount>0 &&  data.unit_price>0 && row.ajaxing == undefined){
                            row.ajaxing = true;
                            $.post(url,data,function(res){
                                 if(res.lastInsertId != undefined){
                                     $(row).attr('key',res.lastInsertId);
                                     add_new_row();
                                 }
                                 row.ajaxing = null;
                            },'json');                        
                        } 
                    }
                    function button_action(){
                        var id_str = this.id;
                        switch(id_str){
                           case 'btn_smt':
                               //alert('儲存');
                               $('#frm_application').submit();
                               break;
                           case 'btn_pnt':
                               alert('列印');
                               break;
                           case 'btn_chk':
                               //alert('出廠');
                               var real_sum = parseInt($('#real_summary').val());
                               if(real_sum >= 0){
                                   //alert('true');
                                   $('#frm_application').append('<input name=\"checkout\" value=\"1\"/>');
                                   $('#frm_application').submit(); 
                               }else{
                                   alert('請正確輸入實收金額!');
                               }
                               break;
                        }
                    }    
                    $(document).ready(function(){                        
                        $(':button').click(button_action);
                        $('.data_row').filter('[id!=prepared_row]').find(':input:last').after($(btn_del_row).clone(true));
                        $('.data_row').find(':input[name=item],:input[name=comment]').change(function(){
                            $(this).parent().parent().find(':input[name=summary]').trigger('change');
                        });
                        $('.data_row').find(':input[name=amount]').change(function(){
                            var sum = this.value * $(this).parent().next().find('[name=unit_price]').val();
                            $(this).parent().next().next().find(':input').val(sum);
                            $(this).parent().next().next().find(':input').trigger('change');
                        });
                        $('.data_row').find(':input[name=unit_price]').change(function(){
                            var sum = this.value * $(this).parent().prev().find('[name=amount]').val();
                            $(this).parent().next().find(':input').val(sum);
                            $(this).parent().next().find(':input').trigger('change');
                        });
                        $('.data_row').find(':input[name=summary]').change(function(){
                             $('#order_item').trigger('change');
                             $(this).parent().parent().trigger('saverow');
                        });
                        $('#order_item').bind('change',function(){
                             var sum = 0;
                             $(this).find(':input[name=summary]').each(function(){
                                 sum += parseInt(this.value);                                 
                             });
                             $('#total_summary').text(sum);
                             $('#order_summary').text(sum);                             
                        });
                        $('.data_row').bind('saverow',save_row);
                        add_new_row(); 
                    });                
                ");                
                break;
        }
        if($this->view->getScriptPath("form/".$sub_script_name)){
            $this->view->sub_script = $this->view->getScriptPath("form/".$sub_script_name);
        }else{
            throw new Exception($sub_script_name." do not exists!");
        }         
    }
    
    public function addAction()
    {
        if($this->_request->isPost()){
             switch($this->_getParam('target'))
             {
                 case "account":
                     $account = new Application_Model_Accounting($_POST);
                     $mapper = new Application_Model_Mapper_Accounting();                                          
                     if($_POST['type']=='income'){
                         $account->setIncome($_POST['amount']);
                     }else{
                         $account->setOutgo($_POST['amount']);
                     }
                     $account->setIdate(date("Y-m-d"));
                     $mapper->save($account);
                     $this->_redirect("/form/account");
                     break;
                 case "application":
                     $form = new Application_Form_Order();
                     $this->_request->setPost('in_date',date('Y-m-d'));
                     $mapper = new Application_Model_Mapper_Order();
                     $order = new Application_Model_Order();
                     if($form->isValid($this->_request->getPost())){
                         $order->setOptions($form->getValues());
                         try{
                             $newID = $mapper->save($order);
                             //$newID = $mapper->getDbTable()->getAdapter()->lastInsertId();
                             $this->_redirect("/form/update/target/application/id/".$newID);                         
                         }catch(Exception $e){
                             echo $e->getMessage();
                             die();
                         }                         
                     }                     
                     $errorMessages = $form->getMessages();
                     break;
             }
        }
        $this->view->pageLocation()->add("首頁")->add("設定")->add("新增");
        $this->layout->pageTitle .= "新增";
        $sub_script_name = $this->_getParam('target')."_add.phtml";
        switch($this->_getParam('target')){
            case "application":
                $this->view->pageLocation()->add("委修單");
                $this->view->headStyle()->appendStyle("style/jquery-ui-1.8.16.custom.css");
                $this->view->headScript()->appendFile("/javascript/jquery-ui-1.8.16.custom.min.js");
                $this->view->headScript()->appendFile("/javascript/jquery.ui/jquery.ui.core.js");
                $this->view->headScript()->appendFile("/javascript/jquery.ui/jquery.ui.widget.js");
                $this->view->headScript()->appendFile("/javascript/jquery.ui/jquery.ui.position.js");
                $this->view->headScript()->appendFile("/javascript/jquery.ui/jquery.ui.autocomplete.js");
                $this->view->headScript()->appendScript("
                    $(document).bind('ready',function(){
                        $('#car_id').change(function(e){
                            var idx = this.selectedIndex;
                            var opt = $(this).find('option').get(idx);
                            $('#car_type').text($(opt).attr('cartype'));
                        });
                        $('#customer_name').autocomplete({
                            source: '/form/get/target/customer/',
                            select:function(e,ui){
                               $('#customer_id').val(ui.item.id);
                               $('#customer_phone').text(ui.item.phone);
                               $('#customer_mobile').text(ui.item.mobile);
                               $('#customer_address').text(ui.item.address);
                               $('#customer_company').text(ui.item.company);
                               $.ajax({
                                   url:'/form/get/target/cars/customer/'+ui.item.id,
                                   type:'get',
                                   dataType:'json',
                                   success:function(data){
                                       //alert(data);
                                       if($('#car_id')[0].disabled==true){
                                          $('#car_id')[0].disabled=false;
                                       }
                                       $('#car_id').children().remove();
                                       $('#car_id').append('<option value=\"\">請選擇</option>');
                                       for(var i=0;i<data.length;i++){
                                          //$('#car_id').append('<option value=\"'+data[i].id+'\">'+data[i].car_no+'</option>');                                          
                                          $('<option value=\"'+data[i].id+'\">'+data[i].car_no+'</option>').attr('cartype',data[i].car_type).appendTo('#car_id');
                                       }
                                   }
                               });
                            }
                        });
                    });
                ");
                if(isset($errorMessages)){
                    $error = "";
                    foreach($errorMessages as $item => $msg){
                       $this->view->errorMessages .= "<div class='valid_error'>{$item}: ".implode(', ',$msg) ."</div>";
                    }
                }
                break;
            case "account":
                $this->view->pageLocation()->add("收支表");
                break;
            default:
                throw new Exception("指定新增的表單名稱(".$this->_getParam('target').")不存在");    
        }
        if($this->view->getScriptPath("form/".$sub_script_name)){
            $this->view->sub_script = $this->view->getScriptPath("form/".$sub_script_name);
        }else{
            throw new Exception($sub_script_name." do not exists!");
        }      
    }
    public function getAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->layout->disableLayout();
        switch($this->_getParam('target')){
            case "customer":
               $mapper = new Application_Model_Mapper_Customer();
               $select = $mapper->getDbTable()->select();
               $select->where("name like '%".trim($this->_request->getParam('term'))."%'");
               $arr = $mapper->fetchArray($select);               
               foreach($arr as $k=>$e){
                  $data[$k]=$e->toArray();
                  $data[$k]['label']=$e->name;
               }
               echo json_encode($data); 
               break;
            case "cars":
               $customer_id = $this->_getParam('customer');
               $mapper = new Application_Model_Mapper_Cars();
               $select = $mapper->getDbTable()->select();
               $select->where('customer_id=?',$customer_id);
               $arr = $mapper->fetchAll($select);
               foreach($arr as $k=>$e){
                  $data[$k]=$e->toArray();                  
               }
               echo json_encode($data);
               break;   
        } 
    }
    
    public function saveorderitemAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->layout->disableLayout();
        $this->_request->setPost('order_id',$this->_getParam('order'));
        if($this->_request->isPost()){
            $form = new Application_Form_OrderItem();
            if($form->isValid($this->_request->getPost())){                
                  //print_r($form->getValues());
                $mapper = new Application_Model_Mapper_Order();
                $order = new Application_Model_Order();
                $item = new Application_Model_OrderItem();
                
                $item->setOptions($form->getValues());                
                $mapper->find($this->_getParam('order'),$order);
                $order->addItem($item);                
                $data['success']=1;
                $data['order']=$order->toArray(); 
                if($lastId = $mapper->getDbTable()->getAdapter()->lastInsertId()){
                   $data['lastInsertId'] = $lastId;
                }
                $mapper->save($order);
                echo json_encode($data);
            }            
        }
    }
    
    public function delorderitemAction()
    {
        $this->_helper->viewRenderer->setNoRender();
        $this->layout->disableLayout();
        if($this->_request->isPost()){
            $mapper = new Application_Model_Mapper_Order();            
            $order = new Application_Model_Order();
            $mapper->find($this->_request->getPost('order_id'),$order);
            $order->delItem($this->_request->getPost('id'));
            $mapper->save($order);
        }
    }
}


?>
