<?php
class CoterieModule extends BaseModel {
	private $_table_coterielist = "cyou_coterie_list";
	private $_table_coteriescreenshot = "cyou_coterie_screenshot";
	private $_table_comment_list = "cyou_comment_list";

	//发布状态
	const COTERIE_STATUS_0 = 0;//未发布
	const COTERIE_STATUS_1 = 1;//已发布
	public $COTERIE_STATUS = array(
		self::COTERIE_STATUS_0 => '未发布',
		self::COTERIE_STATUS_1 => '已发布',
	);

	//分类
	const COTERIE_CLASS_1 = 1;//搞笑圈
	const COTERIE_CLASS_2 = 2;//美色圈
	const COTERIE_CLASS_3 = 3;//游戏圈
	const COTERIE_CLASS_4 = 4;//八卦
	const COTERIE_CLASS_5 = 5;//小说
	const COTERIE_CLASS_6 = 6;//少男少女
	const COTERIE_CLASS_7 = 7;//小编测评
	const COTERIE_CLASS_8 = 8;//优惠活动
	const COTERIE_CLASS_9 = 9;//php类文章

	public static $COTERIE_CLASS = array(
		self::COTERIE_CLASS_1 => '搞笑圈',
		self::COTERIE_CLASS_2 => '美色圈',
		self::COTERIE_CLASS_3 => '游戏圈',
		self::COTERIE_CLASS_4 => '八卦',
		self::COTERIE_CLASS_5 => '小说',
		self::COTERIE_CLASS_6 => '少男少女',
		self::COTERIE_CLASS_7 => '小编测评',
		self::COTERIE_CLASS_8 => '优惠活动',
		self::COTERIE_CLASS_9 => 'PHP类文章',
	);

	const COMMENT_DEL_0 = 0;//未删除(显示)
	const COMMENT_DEL_1 = 1;//已删除(隐藏)
	public $COMMENT_DEL = array(
		self::COMMENT_DEL_0 => "未删除",
		self::COMMENT_DEL_1 => "已删除",
	);

	public function __construct($db_choose = 'cms'){
		parent::__construct($db_choose);
	}

	public function getCoterieInfo($id) {
		$id = intval($id);
		$where = array();
		$where['coterieid'] = $id;
		return $this->table($this->_table_coterielist)->where($where)->find();
	}

	public function getCoterieTop($where = array(), $limit = 10) {
		$where['status'] = 1;
		return $this->table($this->_table_coterielist)->field("coterieid, title, introduce")->where($where)->order("coterieid desc")->limit($limit)->select();
	}

	public function getCoterieList($where, $page, $pagesize) {
		$page = intval($page) ? intval($page):1;
		$pagesize = intval($pagesize) ? intval($pagesize):20;
		$offset = ($page - 1) * $pagesize;

		$result = array(
			"total" => 0,
			"rows" => array()
		);

		$total = $this->table($this->_table_coterielist)->where($where)->count();
		$rows = $this->table($this->_table_coterielist)->field("coterieid, title, introduce, classid")->where($where)->order("coterieid desc")->limit($offset,$pagesize)->select();

		$result['total'] = $total;
		$result['rows'] = $rows;

		return $result;
	}

}
