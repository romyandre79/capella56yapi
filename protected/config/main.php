<?php
return array(
	'basePath'=>dirname(__FILE__).DIRECTORY_SEPARATOR.'..',
	'name'=>'Capella ERP Indonesia - API',
	//'preload'=>array('log'),
	'import'=>array(
		'application.models.*',
		'application.components.*',
		'application.modules.admin.models.*',
	),
	'components'=>array(
		'clientScript' => array('scriptMap' => array('jquery.js' => false,'jquery.min.js' => false)),
		'authManager' => array(
			'class' => 'CDbAuthManager',
			'connectionID' => 'db',
    ),
		'format'=>array(
			'class'=>'application.components.Formatter',
		),
		'user'=>array(
      'class'=>'application.components.WebUser',
			'allowAutoLogin'=>true,
		),
		'urlManager'=>array(
			'urlFormat'=>'path',
			'rules'=>array(
				'<controllers>/<action>'=>'<controllers>/<action>',
				array('<modules>/<controllers>/<action>','pattern'=>'<modules>/<controllers>/<action>','verb'=>'GET'),
			),
		),
		'db'=>array(
			'connectionString' => 'mysql:port=3306;dbname=capellafive;host=localhost',
			'emulatePrepare' => true,
			'username' => 'root',
			'password' => '',
			'charset' => 'utf8',
			'initSQLs'=>array('set names utf8'),
      'schemaCachingDuration' => 3600,
      //'enableProfiling'=>true,
      //'enableParamLogging' => true,
		),
		'cache'=>array(
			'class'=>'CRedisCache',
    ),
  ),
	'params'=>array(
    'SysInfoServer'=>'https://localhost/sysinfo/xml.php?plugin=complete&json'
	),
);
