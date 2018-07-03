<?php
class ArticleAction extends Basic{

	public function index() {
		$data = array();
		$coteriemodule = new CoterieModule();

		$params = $this->getParams();
		$coterieid = $params[1];

		$coterieinfo = $coteriemodule->getCoterieInfo($coterieid);
		$data['coterieinfo'] = $coterieinfo;
		$data['coterieinfo']['classname'] = CoterieModule::$COTERIE_CLASS[$coterieinfo['classid']];

		if (empty($data['coterieinfo'])) {
			header('HTTP/1.1 404 Not Found');
			exit;
		}


		$this->smarty->assign("data", $data);
		$this->smarty->display('article/index.html');
	}

	public function classlist() {
		$data = array();

		$params = $this->getParams();
		$page = intval($params[2]) ? intval($params[2]):1;
		$pagesize = 20;

		$searchParams = array();
		$searchParams['classid'] = intval($params[1]);

		$coteriemodule = new CoterieModule();
		$list = $coteriemodule->getCoterieList($searchParams, $page, $pagesize);

		$data['rows'] = $list['rows'];

		//分页
		$pagination = new Pagination($list['total'], $page, $pagesize);
		$pagination->pageurl = "/art/list/".$params[1]."/";
		$pager = $pagination->generate();

		$this->smarty->assign('pager', $pager);
		$this->smarty->assign("data", $data);
		$this->smarty->display('article/list.html');
	}

}
