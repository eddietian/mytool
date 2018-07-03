<?php
//简单路由配置,必要的转义在RouteAction->index中处理
$RouteConfig = array(
	array("reg" => "/art/list/([0-9]+)/([0-9]+)","action"=> "ArticleAction","method" => "classlist"),
	array("reg" => "/art/list/([0-9]+)","action"=> "ArticleAction","method" => "classlist"),
	array("reg" => "/art/([0-9]+)","action"=> "ArticleAction","method" => "index"),
	array("reg" => "/base64","action"=> "IndexAction","method" => "base64"),
	array("reg" => "/md5","action"=> "IndexAction","method" => "md5"),
	array("reg" => "/qrcode","action"=> "IndexAction","method" => "qrcode"),
	array("reg" => "/urlencode","action"=> "IndexAction","method" => "urlencode"),
	array("reg" => "/time","action"=> "IndexAction","method" => "time"),
	array("reg" => "/","action"=> "IndexAction","method" => "index"),

);

global $RouteConfig;
