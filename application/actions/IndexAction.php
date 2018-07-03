<?php
class IndexAction extends Basic{

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


}
