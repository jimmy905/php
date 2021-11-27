<?php

namespace yuanhou\composer;

use think\facade\Db;
use think\Request;


class Wxsenpay
{

    //公众账号ID
    private $appid;
    //商户号
    private $mch_id;

    private $secrect_key;
    //随机字符串
    private $nonce_str;
    //签名
    private $sign;
    //商品描述
    private $body;
    //商户订单号
    private $out_trade_no;
    //支付总金额
    private $total_fee;
    //终端IP
    private $spbill_create_ip;
    //支付结果回调通知地址
    private $notify_url;
    //交易类型
    private $trade_type;
    //支付密钥
    //证书路径
    private $SSLCERT_PATH;
    private $SSLKEY_PATH;
    //所有参数
    private $params = array();
    public function __construct($appid, $mch_id, $secrect_key)
    {
        $this->appid = $appid;
        $this->mch_id = $mch_id;
        // $this->notify_url = $notify_url;
        $this->secrect_key = $secrect_key;
    }



    // $appid = 'wxbe0b595a6379dea2';
    // $mch_id = '1608871258';

    // $notify_url = 'http://shouhuoji.vue100.com/yibu.php';

    // $key = 'jklajdau90ui90jojoiu87u897u89ioj';
    /**
     * [xmltoarray xml格式转换为数组]
     * @param [type] $xml [xml]
     * @return [type]  [xml 转化为array]
     */
    public function xmltoarray($xml)
    {
        //禁止引用外部xml实体
        libxml_disable_entity_loader(true);
        $xmlstring = simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA);
        $val = json_decode(json_encode($xmlstring), true);
        return $val;
    }

    /**
     * [arraytoxml 将数组转换成xml格式（简单方法）:]
     * @param [type] $data [数组]
     * @return [type]  [array 转 xml]
     */
    public function arraytoxml($data)
    {
        $str = '<xml>';
        foreach ($data as $k => $v) {
            $str .= '<' . $k . '>' . $v . '</' . $k . '>';
        }
        $str .= '</xml>';
        return $str;
    }

    /**
     * [createNoncestr 生成随机字符串]
     * @param integer $length [长度]
     * @return [type]   [字母大小写加数字]
     */
    public function createNoncestr($length = 32)
    {
        $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYabcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";

        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /**
     * [curl_post_ssl 发送curl_post数据]
     * @param [type] $url  [发送地址]
     * @param [type] $xmldata [发送文件格式]
     * @param [type] $second [设置执行最长秒数]
     * @param [type] $aHeader [设置头部]
     * @return [type]   [description]
     */



    public function curl_post_ssl($url, $xmldata, $second = 30, $aHeader = array())
    {
        $isdir = $_SERVER['DOCUMENT_ROOT'] . "/cert/"; //证书位置;绝对路径


        $ch = curl_init(); //初始化curl

        curl_setopt($ch, CURLOPT_TIMEOUT, $second); //设置执行最长秒数
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //要求结果为字符串且输出到屏幕上
        curl_setopt($ch, CURLOPT_URL, $url); //抓取指定网页
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // 终止从服务端进行验证
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); //
        curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'PEM'); //证书类型
        curl_setopt($ch, CURLOPT_SSLCERT, $isdir . 'apiclient_cert.pem'); //证书位置
        curl_setopt($ch, CURLOPT_SSLKEYTYPE, 'PEM'); //CURLOPT_SSLKEY中规定的私钥的加密类型
        curl_setopt($ch, CURLOPT_SSLKEY, $isdir . 'apiclient_key.pem'); //证书位置
        curl_setopt($ch, CURLOPT_CAINFO, 'PEM');
        curl_setopt($ch, CURLOPT_CAINFO, $isdir . 'rootca.pem');
        if (count($aHeader) >= 1) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $aHeader); //设置头部
        }
        curl_setopt($ch, CURLOPT_POST, 1); //post提交方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $xmldata); //全部数据使用HTTP协议中的"POST"操作来发送

        $data = curl_exec($ch); //执行回话


        // var_dump($data);

        if ($data) {
            curl_close($ch);
            return $data;
        } else {
            $error = curl_errno($ch);

            var_dump($error);


            echo "call faild, errorCode:$error\n";
            curl_close($ch);
            return false;
        }
    }

    /**
     * [sendMoney 企业付款到零钱]
     * @param [type] $amount  [发送的金额（分）目前发送金额不能少于1元]
     * @param [type] $re_openid [发送人的 openid]
     * @param string $desc  [企业付款描述信息 (必填)]
     * @param string $check_name [收款用户姓名 (选填)]
     * @return [type]    [description]
     */
    public  function sendMoney($amount, $re_openid, $desc = '测试', $check_name = '')
    {

        $total_amount = (100) * 1;
        // $total_amount = (100) * $amount;

        $data = array(
            'mch_appid' => $this->appid, //商户账号appid
            'mchid' => $this->mch_id, //商户号
            'nonce_str' => $this->createNoncestr(), //随机字符串
            'partner_trade_no' => date('YmdHis') . rand(1000, 9999), //商户订单号
            'openid' => $re_openid, //用户openid
            'check_name' => 'NO_CHECK', //校验用户姓名选项,
            're_user_name' => $check_name, //收款用户姓名
            'amount' => $total_amount, //金额
            'desc' => $desc, //企业付款描述信息
            // 'spbill_create_ip' => IP, //Ip地址
        );

        //生成签名算法
        $secrect_key = $this->secrect_key; ///这个就是个API密码。MD5 32位。
        $data = array_filter($data);
        ksort($data);
        $str = '';
        foreach ($data as $k => $v) {
            $str .= $k . '=' . $v . '&';
        }
        $str .= 'key=' . $secrect_key;
        $data['sign'] = md5($str);
        //生成签名算法

        $xml = $this->arraytoxml($data);

        $url = 'https://api.mch.weixin.qq.com/mmpaymkttransfers/promotion/transfers'; //调用接口
        $res = $this->curl_post_ssl($url, $xml);
        $return = $this->xmltoarray($res);


        //返回来的结果是xml，最后转换成数组
        /*
    array(9) {
    ["return_code"]=>
    string(7) "SUCCESS"
    ["return_msg"]=>
    array(0) {
    }
    ["mch_appid"]=>
    string(18) "wx57676786465544b2a5"
    ["mchid"]=>
    string(10) "143345612"
    ["nonce_str"]=>
    string(32) "iw6TtHdOySMAfS81qcnqXojwUMn8l8mY"
    ["result_code"]=>
    string(7) "SUCCESS"
    ["partner_trade_no"]=>
    string(18) "201807011410504098"
    ["payment_no"]=>
    string(28) "1000018301201807019357038738"
    ["payment_time"]=>
    string(19) "2018-07-01 14:56:35"
    }
     */

        $responseObj = simplexml_load_string($res, 'SimpleXMLElement', LIBXML_NOCDATA);
        // echo "<pre>";
        // var_dump($responseObj);


        // $shuzu = json_decode($responseObj, true);


        // $mch_appid = $responseObj->mch_appid;
        // $return_code = $responseObj->return_code;
        // $mchid = $responseObj->mchid;
        // $nonce_str = $responseObj->nonce_str;
        // $result_code = $responseObj->result_code;
        // $partner_trade_no = $responseObj->partner_trade_no;
        // $payment_no = $responseObj->payment_no;
        // $payment_time = $responseObj->payment_time;
        // var_dump($return);

        // $res = $responseObj->return_code; //SUCCESS 如果返回来SUCCESS,则发生成功，处理自己的逻辑

        return $return;
    }
}
