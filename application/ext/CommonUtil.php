<?php
class CommonUtil{
	
	const USER_COOKIE_KEY = 'x$Xnjsk9&ik3jx241632ox[klexgfmd';

	//js openssl 私钥，用于与前端解密
	private static $_js_private_key = '-----BEGIN RSA PRIVATE KEY-----
MIICXQIBAAKBgQDKwzrDzssZmKuw4bj/DDI0sQ6l9FJSlazZS+/FkmxHdyURMnUp
eQfLurMt7k7Ji97ntPEfisBXFCl/5fjXVBAdObIVHMqO2/mO6GINNNQpymLPbXi0
QbaRUJUeaBM7/mIrfHvrfL7SCm3tAByGDsLoQty8CcuR3T/7Vmomu1SkIQIDAQAB
AoGBALaGkLv7yDE+kjAkwlg2Lmvoh3s1OXZGbtcd2LZOXVLoGmIkvfubjD0F0I6k
XCBq3TpQVE9EpGi/KayM5TlR3z4VENch53S2tJSpZyQZhvpBLV2auiwxwn4dUtOo
qF2OIOoGQHFQMALyL9py/7euiT/V38AHBd1/8tVh+2ikyNbRAkEA8EMtt9+9Zzmz
WOYRLJi5WGiSZP1tUs/R4ctTnrtlCFbs3aqIWq/aP9aQ3F9K60drO02yb5L8k6aD
dgFgMi6r3wJBANgLPQ6W0NbUL4IaU67ConwvZCNnRN41oup0HPmCTY0pW8iwnnCG
MprmLQCvjzB2IdJcanpPeZPi72TkhTPmr/8CQEUib4NbZDrRxaOtAAAfKiYgYQ+i
RNTxa+lXAobIUXgm2x2ltnqx41E/QovG993fvSFhaBjm6LrrzSQlnTKTb20CQQCF
6hlzpJpYv3efTpBWgEDVd067/zQaBOcyKr0XD+TgPiWtdYUQPK67gzeb+DZqlJ8M
VMqPfBnhSEaZpGjIrhKjAkAHAQoyPNNx/8R077TQvRSPXVjXFnhQndZJwSfxpzVL
2VyJuolHEAIANI9bVBGvXR4WqiFZGqScef9a+Ixfrz2L
-----END RSA PRIVATE KEY-----';

	//js openssl 公钥
	private static $_js_public_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDKwzrDzssZmKuw4bj/DDI0sQ6l
9FJSlazZS+/FkmxHdyURMnUpeQfLurMt7k7Ji97ntPEfisBXFCl/5fjXVBAdObIV
HMqO2/mO6GINNNQpymLPbXi0QbaRUJUeaBM7/mIrfHvrfL7SCm3tAByGDsLoQty8
CcuR3T/7Vmomu1SkIQIDAQAB
-----END PUBLIC KEY-----';

	//js openssl 模数，用于rsa.js生成公钥 (生成模数命令：rsa -in private_key.pem -noout -modulus)
	private static $_js_openssl_modulus = 'CAC33AC3CECB1998ABB0E1B8FF0C3234B10EA5F4525295ACD94BEFC5926C477725113275297907CBBAB32DEE4EC98BDEE7B4F11F8AC05714297FE5F8D754101D39B2151CCA8EDBF98EE8620D34D429CA62CF6D78B441B69150951E68133BFE622B7C7BEB7CBED20A6DED001C860EC2E842DCBC09CB91DD3FFB566A26BB54A421';
	
	public static $domainList = array('sifuba.net','sifuba.com.cn');
	
	public static function encodePassword($password){
		$p = md5($password);
		return md5(substr($p, 16, 8).substr($p, 0, 8).substr($p, 24, 8).substr($p, 8, 8));
	}
	
	/**
	 * 将分转元
	 * @param int $money 单位分
	 * @return float
	 */
	public static function moneyToYuan($money){
		$money = intval($money);
		return $money / 100;
	}
	
	/**
	 * 计算token将手机、用户一起绑定，下一步操作在验证token的时候必须提供相关信息
	 * @param int $time
	 * @param string $phone
	 * @param int $userid
	 * @return string
	 */
	public static function smsToken($time, $phone, $userid = 0){
		$userid = intval($userid);
		return md5(AppConstant::MD5_USER_SMS_KEY.$time.$phone.$userid);
	}
	
	public static function standardUrl($url, $isHttps = false){
		$url = trim($url);
		return (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) ? $url : ($isHttps ? "https://{$url}" : "http://{$url}");
	}
	
	public static function setCookie($name, $value, $timeout = null){
		$expire = time() + intval($timeout);
		foreach(self::$domainList as $domain){
			setcookie($name, $value, $expire, '/', $domain);
		}
	}
	
	public static function clearCookie($name){
		$expire = strtotime('1970-01-01');
		foreach(self::$domainList as $domain){
			setcookie($name, '', $expire, '/', $domain);
		}
	}
	
	/**
	 * 设置用户web cookie
	 * @param array $user
	 */
	public static function setWebUserCookie($user){
		$time = time();
		$expire = $time + 365 * 86400;
		
		$u = DesUtil::encrypt(serialize($user));
		$sign = md5($time.$u.CommonUtil::USER_COOKIE_KEY);
		$data = array('logintime' => $time, 'sign' => $sign, 'u' => $u);
		
		foreach(self::$domainList as $domain){
			setcookie('userid', $user['userid'], $expire, '/', $domain);
			setcookie('username', $user['username'], $expire, '/', $domain);
			setcookie('nickname', $user['nickname'], $expire, '/', $domain);
			setcookie('cyauth', base64_encode(json_encode($data)), $expire, '/', $domain);
		}
	}
	
	public static function clearWebUserCookie(){
		$time = strtotime('1970-01-01');
		foreach(self::$domainList as $domain){
			setcookie('userid', '', $time, '/', $domain);
			setcookie('username', '', $time, '/', $domain);
			setcookie('nickname', '', $time, '/', $domain);
			setcookie('cyauth', '', $time, '/', $domain);
		}
	}
	
	/**
	 * 解析cookie并返回用户信息数组
	 * @return boolean
	 */
	public static function decodeWebUserCookie(){
		static $user = null;
		if($user !== null){
			return $user;
		}
		
		$cookie = $_COOKIE;
		if(!isset($cookie['cyauth']) || empty($cookie['cyauth'])){
			return false;
		}
		
		$data = @json_decode(base64_decode($cookie['cyauth']), true);
		
		if(!isset($data['logintime']) || !isset($data['sign']) || !isset($data['u'])){
			return false;
		}
		
		if($data['sign'] != md5($data['logintime'].$data['u'].CommonUtil::USER_COOKIE_KEY)){
			return false;
		}

		$user = @unserialize(DesUtil::decrypt($data['u']));
		return empty($user) ? false : $user;
	}

	public static function calculateSign($params, $key){
		if(!is_array($params) || empty($key)){
			return false;
		}
		if(isset($params['sign'])){
			unset($params['sign']);
		}

		ksort($params);//对参数字母排序
		reset($params);

		return md5(urldecode(http_build_query($params))."&{$key}");
	}

    /**
     * 得到用户IP
     *
     */
    public static function getUserIP()
    {
        $realIP = FALSE;
        if(!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $realIP = $_SERVER['HTTP_CLIENT_IP'];
        }elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(',',$_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach($ips as $ip) {
                $ip = trim($ip);
                if(!self::isLAN($ip)) {//非局域网
                    $realIP = $ip;
                    break;
                }
            }
        }else {
            $realIP = $_SERVER['REMOTE_ADDR'];
        }
        return $realIP ? $realIP : 'unknow IP';
    }

        /**
         *@todo: 判断是否为post
         */
        public static  function is_post()
        {
            return isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD'])=='POST';
        }

	//js rsa 解密(16进制)
	public static function jsDeCrypt($hex_encrypt_data) {
		$private_key = self::$_js_private_key;
		$encrypt_data = pack("H*", $hex_encrypt_data);//对十六进制数据进行转换
		openssl_private_decrypt($encrypt_data, $decrypt_data, $private_key);
		return $decrypt_data;
	}


}