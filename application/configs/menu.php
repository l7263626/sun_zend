<?php
$key=0;
$skey=0;
$mainMenu[$key]['subject']="設定";
$mainMenu[$key]['sub'][$skey]['op']="/admin/document";
$mainMenu[$key]['sub'][$skey++]['name']="公司基本資料";
$mainMenu[$key]['sub'][$skey]['op']="/admin/customer";
$mainMenu[$key]['sub'][$skey++]['name']="客戶資料";
$mainMenu[$key]['sub'][$skey]['op']="/admin/supplier";
$mainMenu[$key]['sub'][$skey++]['name']="廠商資料";
$mainMenu[$key]['sub'][$skey]['op']="/admin/checkitem";
$mainMenu[$key]['sub'][$skey++]['name']="檢查項目";
$key++;
$mainMenu[$key]['subject']="表單";
$mainMenu[$key]['sub'][$skey]['op']="/form/checklist";
$mainMenu[$key]['sub'][$skey++]['name']="檢查單";
$mainMenu[$key]['sub'][$skey]['op']="/form/application";
$mainMenu[$key]['sub'][$skey++]['name']="委修單";
$mainMenu[$key]['sub'][$skey]['op']="/form/account";
$mainMenu[$key]['sub'][$skey++]['name']="收支表";
$mainMenu[$key]['sub'][$skey]['op']="/form/payed";
$mainMenu[$key]['sub'][$skey++]['name']="支出表";
$mainMenu[$key]['sub'][$skey]['op']="/form/todo-list";
$mainMenu[$key]['sub'][$skey++]['name']="開發備忘錄";
?>
