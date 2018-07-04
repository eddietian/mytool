<?php
class IndexAction extends Basic{

	public function __construct(){

		parent::__construct();

		if ($_SERVER['HTTP_HOST'] != "www.dejson.com") {
			$this->seo();
			exit;
		}

	}

	public function index() {
		$data = array();

		$coteriemodule = new CoterieModule();

		//拿到10篇文章
		$searchparams = array(
			"classid" => CoterieModule::COTERIE_CLASS_9
		);
		$data['coterieinfo'] = $coteriemodule->getCoterieTop($searchparams, 10);

		$this->smarty->assign("data", $data);
		$this->smarty->display('web/index.html');
	}

	public function urlencode() {
		$this->smarty->display('web/urlencode.html');
	}

	public function base64() {
		$this->smarty->display('web/base64.html');
	}

	public function md5() {
		$this->smarty->display('web/md5.html');
	}

	public function qrcode() {
		$this->smarty->display('web/qrcode.html');
	}

	public function time() {
		$this->smarty->display('web/time.html');
	}

	public function seo() {
		$hostname = $_SERVER['HTTP_HOST'];

		$sitemodule = new SiteModule();
		$siteinfo = $sitemodule->getSiteInfo($hostname);

		$siteext = $sitemodule->getSiteExt($siteinfo['host']);
		$siteinfo['totalcode'] = $siteext['totalcode'];
		$siteinfo['tpl'] = $siteext['context'];

		if ($siteinfo['state'] == "0") {
			$siteinfo['title'] = 404;
			$siteinfo['keywords'] = "";
			$siteinfo['description'] = "";
			$siteinfo['tpl'] = "404 Not Found";
			header('HTTP/1.1 404 Not Found');
		}

		$data = array();
		$data['siteinfo'] = $siteinfo;

		$this->smarty->assign("data", $data);
		$this->smarty->display('seo/index.html');
	}



}
