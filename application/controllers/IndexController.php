<?php
class IndexController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */             
        $auth = Zend_Auth::getInstance();
        if($this->_getParam('action')!='login' && !$auth->hasIdentity()){
            $this->_redirect("/index/login");
        }  
        $this->view->identity = $auth->getIdentity();  
        $this->boot = $this->getInvokeArg('bootstrap');
        $this->db = $this->boot->getResource('db');
        
        $this->layout=$this->_helper->layout();
        require "../application/configs/menu.php";
        $this->layout->menu = $mainMenu;
        $this->layout->pageTitle = "【陽陞汽車】首頁";
        $this->layout->setLayout('main');        
    }

    public function indexAction()
    {
       
        // action body        
    }

    public function loginAction()
    {
        // action body
        $this->layout->setLayout('login');
        if($this->_request->isPost()){
           $auth = Zend_Auth::getInstance();
           $authAdapter = new Zend_Auth_Adapter_DbTable($this->db,"auth","account","password","md5(?)");
           $authAdapter->setIdentity($this->_request->getPost('account'))
                       ->setCredential($this->_request->getPost('password'));
           $result = $auth->authenticate($authAdapter);
           if($result->isValid()){
              $auth->getStorage()->write($authAdapter->getResultRowObject());
              $this->_redirect("/");
           }
           switch($result->getCode()){
              case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                  $this->view->loginMessage = "帳號不存在";
                  break;
              case Zend_Auth_Result::FAILURE_IDENTITY_AMBIGUOUS:
                  $this->view->loginMessage = "帳號衝突!(代表有多筆相同帳號)";
                  break;
              case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                  $this->view->loginMessage = "密碼錯誤!";
                  break;              
              case Zend_Auth_Result::FAILURE_UNCATEGORIZED:
                  $this->view->loginMessage = "無效分類!";
                  break;
              default:
                  $this->view->loginMessage = "不明錯誤!";
                  break;
           }          
        }
        $this->layout->pageTitle = "【陽陞汽車】登入";                
        
    }

    public function logoutAction()
    {
        $auth = Zend_Auth::getInstance();
        $auth->clearIdentity();
        $this->_redirect("/index/logout");
    }

    public function dbUpdateAction()
    {
        // action body
        $sqlPath = APPLICATION_PATH . DIRECTORY_SEPARATOR . "sql" . DIRECTORY_SEPARATOR;
        $filesInDir = scandir($sqlPath);
        foreach($filesInDir as $sqlfile){
            if(preg_match("/\.sql$/",$sqlfile) && is_file($sqlPath . $sqlfile)){
                $sqlStr = file_get_contents($sqlPath . $sqlfile);
                $error = array();
                $this->db->getConnection()->multi_query($sqlStr);
                if($this->db->getConnection()->error!=''){
                    $error[] = $this->db->getConnection()->error;
                }
                while($this->db->getConnection()->more_results()){
                    $this->db->getConnection()->next_result();
                    if($this->db->getConnection()->error!=''){
                        $error[] = $this->db->getConnection()->error;
                    }
                }
                if(!empty($error)){
                    $this->view->message .= "<h5>".$sqlfile."檔案有錯誤發生</h5>";
                    foreach($error as $e){
                       $this->view->message .= "<div class='multi_query_error'>".$e ."</div>";                       
                    }
                }else{
                    $this->view->message .= "<div class='multi_query_success'>".$sqlfile."執行成功!</div>";
                    unlink($sqlPath . $sqlfile);
                }                                                
            }
        }        
    }


}

?>