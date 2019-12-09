<?php
function SendData($total,$rows){
	echo CJSON::encode(array(
		'isError' => 0,
		'total'=>$total,
		'msg' => getCatalog('Success'),
		'rows'=>$rows,
	));
	Yii::app()->end(); 
}
function SendMsg($iserror,$values) {
	echo CJSON::encode(array(
		'isError' => $iserror,
		'msg' => $values,
	));
	Yii::app()->end(); 
}
function GetSearchText($paramtype=[],$param,$default='',$datatype='string') {
	$s = $default;
	for ($i = 0;$i<count($paramtype);$i++) {
		if (strtoupper($paramtype[$i]) == 'POST') {
			$s = isset ($_POST[$param]) ? filter_input(INPUT_POST,$param) : $s;
		}
		if (strtoupper($paramtype[$i]) == 'GET') {
			$s = isset ($_GET[$param]) ? filter_input(INPUT_GET,$param) : $s;
		}
		if (strtoupper($paramtype[$i]) == 'Q') {
			$s = isset ($_GET['q']) ? filter_input(INPUT_GET,'q') : $s;
		}
	}
	if ($datatype=='string') {
		$s = '%'.str_replace(' ','%',trim($s)).'%';
	}
	return $s;
}
function GetCatalog($menuname) {
	$dependency = new CDbCacheDependency("select max(updatedate) from catalogsys");
	$connection = Yii::app()->db;
	if (Yii::app()->user->id !== null) {
		$sql = "select catalogval as katalog 
			from catalogsys a 
			inner join useraccess b on b.languageid = a.languageid 
			where catalogname = :catalogname 
			and b.username = '" . Yii::app()->user->id . "'";
	} else {
		$sql = "select catalogval as katalog 
			from catalogsys a 
			where languageid = 1 
			and catalogname = :catalogname";
	}
	$comm = $connection->cache(1000, $dependency)->createCommand($sql);
	$comm->bindvalue(':catalogname',$menuname,PDO::PARAM_STR);
	$menu = $comm->queryScalar();
	if (($menu != null) || ($menu != '')) {
		return $menu;
	} else {
		return $menuname;
	}
}
function GetMessage($isError = false, $catalogname = '', $typeerror = 0) {
	header("Content-Type: application/json");	
	if ($isError == true) {
		$isError = 1;
	} else {
		$isError = 0;
	}
	echo CJSON::encode(array(
		'isError' => $isError,
		'msg' => $catalogname
	));
	Yii::app()->end();
}
function CheckAccess($token,$menuname, $menuaction) {
	$baccess    = false;
	$dependency = new CDbCacheDependency("select max(c.updatedate) 
	from useraccess a 
	inner join usergroup b on b.useraccessid = a.useraccessid 
	inner join groupmenu c on c.groupaccessid = b.groupaccessid 
	inner join menuaccess d on d.menuaccessid = c.menuaccessid 
	where a.authkey = '".$token."' and lower(menuname) = lower('" . $menuname . "')");
	$sql        = "select " . $menuaction . " as akses " . " from useraccess a 
	inner join usergroup b on b.useraccessid = a.useraccessid 
	inner join groupmenu c on c.groupaccessid = b.groupaccessid 
	inner join menuaccess d on d.menuaccessid = c.menuaccessid 
	where a.authkey = '".$token."' and lower(username) = lower('" . Yii::app()->user->id . "') and lower(menuname) = lower('" . $menuname . "')";
	$results		  = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryAll();
	foreach ($results as $result) {
		if ($result['akses'] == 1) {
			$baccess = true;
		}
	}
	return $baccess;
}