<?php class SysadmController extends ApiController {
  public function actionGetCatalog(){
    $messages = GetSearchText(array('POST'),'messages','','');
    SendMsg(0,GetCatalog($messages));
  }
  public function actionGetMessage(){
    $messages = GetSearchText(array('POST'),'messages','','');
    $iserror = GetSearchText(array('POST'),'iserror',0,'int');
    SendMsg($iserror,GetCatalog($messages));
  }
  public function actionCheckAccess(){
    $token = GetSearchText(array('POST'),'token','','');
    $menuname = GetSearchText(array('POST'),'menuname','','');
    $menuaction = GetSearchText(array('POST'),'menuaction','','');
    $breturn = CheckAccess($token,$menuname,$menuaction);
    if ($breturn == false) {
      SendMsg(1,GetCatalog('youarenotauthorized'));
    } else {
      SendMsg(0,$breturn);
    }
  }
}