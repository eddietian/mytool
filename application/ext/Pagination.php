<?php

class Pagination {

	private $totalcount	=	0;		// 总条数
	private $pagesize	=	1;		// 每页条数
	private $pagenum	=	1;		// 当前页
	private $pagetotal	=	0;		// 总页数
	private $hasinput	=	true;	// 是否要输入框
	
	public $labelcount	=	10;		// 标签数，只包含数字和省略号
	public $pageurl;				// url

	public $paramname		=	'page';			// url参数名
	public $pagelabel		=	'a';			// 数字标签
	public $ellipsislabel	=	'span';			// 省略号标签
	public $currentclass	=	'select';		// 当前页标签的class
	public $nextclass		=	'nextPage';		// 下一页标签的class
	public $previousclass	=	'previousPage';	// 上一页标签的class

	public function __construct( $total, $page, $size, $input = true ) {
		$this->totalcount	=	intval( $total ) >= 0 ? intval( $total ) : 0;
		$this->pagesize		=	intval( $size ) > 0 ? intval( $size ) : 1;
		$this->pagenum		=	intval( $page ) > 0 ? intval( $page ) : 1;
		$this->hasinput		=	$input;
		$this->pageurl		=	"?r={$_REQUEST['r']}&{$this->paramname}=";
	}

	private function getpagerdata() {
		$pager	=	array();
		$this->pagetotal	=	ceil( $this->totalcount / $this->pagesize );
		if ( $this->labelcount < $this->pagetotal ) {	// 要有...
			$pager[$this->labelcount-1]	=	strval( $this->pagetotal );
			$pager[$this->labelcount-2]	=	strval( $this->pagetotal - 1 );
			if ( ( $this->pagetotal - $this->pagenum ) > 6 ) {
				$pager[$this->labelcount-3]	=	".....";
				$middle	=	ceil( ( $this->labelcount - 3 ) / 2 );
				if ( $this->pagenum < $middle ) {	// 中间值不是当前页
					$count	=	count( $pager );
					for ($i = 0; $i < ($this->labelcount - $count); $i++) { 
						$pager[$i]	=	strval( $i + 1 );
					}
				} else {	// 中间值不是当前页
					$pager[$middle-1]	=	strval( $this->pagenum );
					$i	= 0;
					while ( !isset( $pager[$middle+$i] ) ) {
						$pager[$middle+$i]	=	strval( $this->pagenum + $i + 1 );
						$i++;
					}
					$i	=	2;
					while ( count( $pager ) < $this->labelcount ) {
						$pager[$middle-$i]	=	strval( $this->pagenum - $i + 1 );
						$i++;
					}
				}
			} else {
				for ($i = 3; $i <= $this->labelcount; $i++) { 
					$pager[$this->labelcount-$i]	=	strval( $this->pagetotal - $i + 1 );
				}
			}
		} else if ( $this->pagetotal > 0 ) {	// 不需要...
			for ($i = 0; $i < $this->pagetotal; $i++) { 
				$pager[$i]	=	strval( $i + 1 );
			}
		} else {
			$pager[0]	=	"1";
		}
		ksort($pager);
		return $pager;
	}

	public function generate($params = array()) {

		$_purl = "";
		if ($params && is_array($params)) {
			$_purl = http_build_query($params);
			$_purl = "&".$_purl;
		}

		$pager	=	$this->getpagerdata();
		$html	=	'';
		$input	=	'';
		$next	=	'';
		$previous	=	'';
		foreach ($pager as $k => $v) {
			if ( $v == '.....' ) {
				$html	.=	'<span>'.$v.'</span> ';
			} else if ( intval( $v ) == $this->pagenum ) {
				$html	.=	'<'.$this->pagelabel.' href="'.$this->pageurl.$v.$_purl.'" class="'.$this->currentclass.'">'.$v.'</'.$this->pagelabel.'> ';
			} else {
				$html	.=	'<'.$this->pagelabel.' href="'.$this->pageurl.$v.$_purl.'">'.$v.'</'.$this->pagelabel.'> ';
			}
		}
		// if ( $this->pagenum > 1 ) {
		// 	$previous	=	'<'.$this->pagelabel.' href="'.$this->pageurl.( $this->pagenum - 1 ).'" class="'.$this->previousclass.'">&nbsp;</'.$this->pagelabel.'> ';
		// }
		if ( $this->pagenum < $this->pagetotal ) {
			$next	=	'<'.$this->pagelabel.' href="'.$this->pageurl.( $this->pagenum + 1 ).$_purl.'" class="'.$this->nextclass.'">&nbsp;</'.$this->pagelabel.'> ';
		}
		if ( $this->hasinput ) {
			$input	=	'<span>跳转到</span> <input type="text" id="pageNum"> <input type="button" value="GO" class="goPage">';
		}
		$html	=	"共：{$this->totalcount} 条数据&nbsp;".$previous.$html.$next.$input;
		return $html.$this->genscript($_purl);
	}
	
	private function genscript($_purl = "",$has_jquery = true) {
		$scripthead	=	"\r\n".'<script type="text/javascript">';
		$scriptfoot	=	"\r\n".'</script>';
		$script		=	"\r\n
						var gotopage = function() {
							var page = $('#pageNum').val();
							if (page != '') {
								window.location.href = '$this->pageurl'+page+'$_purl';
							}
						};
						$('.goPage').click(function(){gotopage();});
						$('#pageNum').keypress(function(event){gotopage();});
						\r\n";
		return $scripthead.$script.$scriptfoot;
	}
	
}