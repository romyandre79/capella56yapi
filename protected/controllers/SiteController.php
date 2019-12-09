<?php 
class SiteController extends ApiController {
  public function actionIndex(){
    $this->render('index');
  }
}