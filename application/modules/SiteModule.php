<?php
class SiteModule extends BaseModel {
	private $_table_sitehost = "sitehost";
	private $_table_sitelist = "sitelist";
	private $_table_sitetpl = "sitetpl";

	public function __construct($db_choose = 'seo'){
		parent::__construct($db_choose);
	}

	public function getSiteInfo($hostname) {
		$where = array();
		$where["hostname"] = $hostname;
		$rs = $this->table($this->_table_sitelist)->where($where)->find();
		return $rs;
	}

	public function getSiteExt($host) {
		$sql = "select tbhost.totalcode, tbtpl.context from {$this->_table_sitehost} as tbhost, {$this->_table_sitetpl} as tbtpl where tbhost.host = tbtpl.host and tbhost.host = '{$host}'";

		$rs = $this->querysql($sql);

		return $rs[0];
	}
}
