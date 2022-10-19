<?php

namespace yuanhou\composer;

use think\facade\Db;
use think\Request;
use think\facade\Cache;

class Tool
{
    public function index()
    {
        echo 'this is tool';
    }

    static function test()
    {
        echo 'this is tool test 1  111';
    }

    // 小数点后保留两位
    static function xiaoshu($num, $wei = 2)
    {
        return  number_format($num, $wei, ".", "");
    }




    // redis存
    static function rediscun($key, $data)
    {
        $biaoshi = BIAOSHI . '_' . $key;

        Cache::store('redis')->set($biaoshi, $data);

        return true;
    }

    // redis取
    static function redisqu($key)
    {
        $biaoshi = BIAOSHI . '_' . $key;

        $test = Cache::store('redis')->get($biaoshi);

        return $test;
    }


    // 提取变量
    static function tiqu($shuzu, $ziduan)
    {
        // 提取111

        if (isset($shuzu[$ziduan])) {
            return $shuzu[$ziduan];
        } else {
            return '';
        }
    }


    // 获取会员
    static function gethuiyuan(Request $request)
    {

        $openid = $request->param('token');

        // 查询会员
        $huiyuan = Db::name("yuanhou_user")->where('token', $openid)->where('isdel', 0)->find();

        return $huiyuan;
    }
    // 获取会员
    static function getUser(Request $request)
    {

        $openid = $request->param('token');

        // 查询会员
        $huiyuan = Db::name("yuanhou_user")->where('token', $openid)->find();

        return $huiyuan;
    }

    // 获取会员
    static function getAdmin(Request $request)
    {

        $openid = $request->param('admintoken');

        // 查询会员
        $huiyuan = Db::name("yuanhou_admin")->where('token', $openid)->find();

        return $huiyuan;
    }


    // 获取今天日期
    static function jintian()
    {
        $today = date("Y-m-d");
        return $today;
    }


    // 成功的返回
    static function fan_ok($arr)
    {

        $arr['code'] = 20000;
        $arr['status'] = 0;

        return $arr;
    }
    // 失败的返回
    static function fan_fail($arr)
    {

        $arr['code'] = 20000;
        $arr['status'] = -1;
        return $arr;
    }

    // 获取订单好
    static function getOrderId()
    {

        return date('YmdHis') . rand(100000, 999999);
    }

    // 获取现在的时间datetime
    static function xianzai($chuo = null)
    {



        if ($chuo) {
            return date('Y-m-d H:i:s',  time() + $chuo);
        }


        return date("Y-m-d H:i:s", time());
    }


    // 获取位置
    static function getweizhi($longitude, $latitude, $ak = 't1GTuZRbjUzLHlcrwGIkKaKy7bfihKiN')
    {
        //调取百度接口,其中ak为百度帐号key,注意location纬度在前，经度在后

        $api = "http://api.map.baidu.com/reverse_geocoding/v3/?ak=" . $ak . "&output=json&coordtype=wgs84ll&location=" . $latitude . "," . $longitude;
        $content = file_get_contents($api);
        $arr = json_decode($content, true);
        return $arr;
    }


    // 腾讯获取距离
    static  function tengxunJuli($lat1 = 0, $lng1 = 0, $lat2 = 0, $lng2 = 0, $ak = '')
    {
        $result = array();
        $result['distance'] = 0.00; //距离 公里
        $result['duration'] = 0.00; //时间 分钟
        // $ak = '32YBZ-EIHCD-6MH44-P663Z-KOD46-YYBYP';
        $url = 'https://apis.map.qq.com/ws/distance/v1/matrix/?mode=driving&from=' . $lat1 . ',' . $lng1 . '&to=' . $lat2 . ',' . $lng2 . '&key=' . $ak;

        $data = file_get_contents($url);
        $data = json_decode($data, true);

        return $data;

        // $result = $data['result'];
        // $shuzu =  $result['rows'][0]['elements'][0];
        //     // $duration  = $result['rows'][0]['elements'][0]['duration'];

        // return $shuzu;
    }


    //  密码
    static function mima($data)
    {

        return md5('yuanhou' . $data);
    }
    //  获取token
    static function gettoken($data)
    {

        return md5('yuanhou' . $data);
    }


    // 删除
    static function shanchu($biao, $id)
    {

        $up = Db::table($biao)->where('id', $id)->update(['isdel' => 1]);

        if ($up) {
            return true;
        } else {
            return false;
        }
    }
    // 删除
    static function del($biao, $id)
    {

        $up = Db::table($biao)->where('id', $id)->update(['isdel' => 1]);

        if ($up) {
            return true;
        } else {
            return false;
        }
    }

    // 查询多条数据
    static function cha($biao, $tiaojian = [])
    {


        $ls = Db::table($biao)->where([
            ['isdel', '=', 0],
        ])->where($tiaojian)->select()->toArray();

        return $ls;
    }




    // 查询单条数据
    static function find($biao, $tiaojian = [])
    {


        $r = Db::table($biao)->where([
            ['isdel', '=', 0],
        ])->where($tiaojian)->find();

        return $r;
    }





    // 更新
    static function update($biao, $id, $arr = [])
    {

        $up = Db::table($biao)->where('id', $id)->where('isdel', 0)->update($arr);

        if ($up) {
            return true;
        } else {
            return false;
        }
    }
    // 添加
    static function add($biao, $arr = [])
    {

        $in = Db::name($biao)->insert($arr);
        if ($in) {
            return true;
        } else {
            return false;
        }
    }
    // 添加
    static function addId($biao, $arr = [])
    {

        $in = Db::name($biao)->insertGetId($arr);
        if ($in) {
            return $in;
        } else {
            return false;
        }
    }

    //获取月初
    static function yuechu($date = null)
    {

        if ($date == null) {
            $yuechu = date('Y-m-01', time());
        } else {
            $yuechu =  date('Y-m-01', strtotime($date));
        }


        return $yuechu;
    }

    // 获取上月下月的今天日期
    static function someyue($fukuanqi, $i)
    {

        return  $yue = date("Y-m-d", strtotime($i . " months", strtotime($fukuanqi)));
    }



    // 获取昨天

    static  function zuotian($date = null)
    {
        if ($date) {

            $zuotian = date('Y-m-d', strtotime('-1 day', strtotime($date)));
        } else {
            $zuotian = date('Y-m-d', strtotime('-1 day'));
        }

        return     $zuotian;
    }

    // 获取日期
    static  function riqi($date = null, $tian)
    {
        if ($date) {

            $zuotian = date('Y-m-d', strtotime($tian . ' day', strtotime($date)));
        } else {
            $zuotian = date('Y-m-d', strtotime($tian . ' day'));
        }

        return     $zuotian;
    }



    // 获取明天的日期
    static  function mingtian()
    {

        $mingtian = date('Y-m-d', strtotime('+1 day'));

        return     $mingtian;
    }


    //获取上月日期
    static  function shangyue()
    {

        $shangyue = date("Y-m-d", strtotime("last month"));

        return     $shangyue;
    }


    // 获取上月昨天
    static  function shangyuezuotian()
    {

        $shangyuezuotian = date("Y-m-d", strtotime("last month -1 day"));
        return  $shangyuezuotian;
    }



    //获取上月初
    static   function shangyuechu()
    {

        $shangyuechu = date("Y-m-01", strtotime("last month"));

        return     $shangyuechu;
    }


    // 获取一年多少天
    static function year_days()
    {

        $days = date('t');
        return $days;
    }

    // 获取去年今天
    static function qunianjintian()
    {

        $qunian = date("Y-m-d H:i:s", strtotime("-1 year"));


        return $qunian;
        // $qunianzuotian = zuotian($qunian);
    }

    // 获取去年昨天
    static function qunianzuotian()
    {

        $qunian = date("Y-m-d H:i:s", strtotime("-1 year"));
        $qunianzuotian = self::zuotian($qunian);
        return $qunianzuotian;
    }

    // 获取去年月初
    static function qunianbenyuechu()
    {
        $qunian = date("Y-m-d H:i:s", strtotime("-1 year"));
        $qunianyuechu = self::yuechu($qunian);
        return $qunianyuechu;
    }

    // 获取今年开始日期
    static function jinniankaishiriqi()
    {



        $yearFirstDay  = date('Y-m-d', mktime(0, 0, 0, 1, 1, date("Y")));

        return $yearFirstDay;
    }




    // 多位数组排序
    static function duowei_sort($arr, $key, $type = 'asc')
    {

        if ($type == 'asc') {
            $sort = SORT_ASC;
        } else {
            $sort = SORT_DESC;
        }

        $last_names = array_column($arr, $key);
        array_multisort($last_names, $sort, $arr);

        return $arr;
    }


    // 获取微信的access_token   

    static function getWxAccessToken($appid, $secret)
    {
        //接收用户性别
        //下面url是请求微信端地址获取用户唯一标识的，对应的appid和secret改成自己的
        // $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $appid . '&secret=' . $secret . '&js_code=' . $code . '&grant_type=authorization_code';


        $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret;

        $neirong = self::curlGet($url);


        return $neirong;
    }


    // 小程序模版消息发送
    static function wxSmallMubanSend($appid, $secret, $openid, $mubanid, $page, $data)
    {

        $shuzu = self::getWxAccessToken($appid, $secret);
        $shuzu = json_decode($shuzu, true);
        $access_token = $shuzu['access_token'];

        $wangzhi = 'https://api.weixin.qq.com/cgi-bin/message/subscribe/send?access_token=' . $access_token;

        $post_data = array(
            'access_token'  => $access_token,
            'touser'        => $openid, // 用户openid
            'template_id'   => $mubanid, // 消息模板ID
            'page'          => $page, // 小程序跳转页面 ,需要上线
            'data'          => $data, // 模板内容（注意格式问题）
        );
        $angzhi = Tool::curlPost($wangzhi, json_encode($post_data));




        return $angzhi;
    }



    // httpsget请求
    static function curlGet($url, $header = null)
    {

        $headers = array(

            "Content-Type: application/json",
            "Accept: application/json",

        );

        //初始化    
        $ch = curl_init();

        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);

        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, '0');
        #禁止 cURL 验证对等证书（peer's certificate）。要验证的交换证书可以在 CURLOPT_CAINFO 选项中设置
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, '0');


        //设置header头  

        if (!empty($header)) {

            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }



        //执行并获取HTML文档内容
        $output = curl_exec($ch);

        //释放curl句柄
        curl_close($ch);




        return $output;    //返回json对象
    }


    // post请求
    static function curlPost($url, $data)
    {

        //初使化init方法
        $ch = curl_init();

        //指定URL
        curl_setopt($ch, CURLOPT_URL, $url);

        //设定请求后返回结果
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        //声明使用POST方式来进行发送
        curl_setopt($ch, CURLOPT_POST, 1);

        //发送什么数据呢
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);


        //忽略证书
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        //忽略header头信息
        curl_setopt($ch, CURLOPT_HEADER, 0);

        //设置超时时间
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);

        //发送请求
        $output = curl_exec($ch);

        //关闭curl
        curl_close($ch);

        //返回数据
        return $output;
    }


    // 获取openid
    static function getopenid($appid, $secret, $code)
    {




        //接收用户性别
        //下面url是请求微信端地址获取用户唯一标识的，对应的appid和secret改成自己的
        $url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $appid . '&secret=' . $secret . '&js_code=' . $code . '&grant_type=authorization_code';

        //初始化
        $ch = curl_init();



        //设置选项，包括URL
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

        //执行并获取HTML文档内容
        $output = curl_exec($ch);

        //释放curl句柄
        curl_close($ch);

        //打印获得的数据
        // print_r( $output );

        $shuzu = json_decode($output, true);

        // var_dump($shuzu);

        return $shuzu;
    }







    // 过滤表情
    static function guolv_biaoqing($str)
    {

        $str = preg_replace_callback(
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $str
        );
        return $str;
    }


    // 获取今天是今年的第几天
    static  function year_dijitian()
    {
        $tian = 0;
        //输入一个日期，判断此日期是本年的哪一天。
        $year = date('Y', time());
        $month = date('m', time());
        $day = date('d', time());
        $sum = -1;
        switch ($month) {
            case 1:
                $sum = 0;
                break;
            case 2:
                $sum = 31;
                break;
            case 3:
                $sum = 59;
                break;
            case 4:
                $sum = 90;
                break;
            case 5:
                $sum = 120;
                break;
            case 6:
                $sum = 151;
                break;
            case 7:
                $sum = 181;
                break;
            case 8:
                $sum = 212;
                break;
            case 9:
                $sum = 243;
                break;
            case 10:
                $sum = 273;
                break;
            case 11:
                $sum = 304;
                break;
            case 12:
                $sum = 334;
                break;
            default:
                echo '输入错误，请输入1-12之间的数';
                break;
        }
        if ($sum >= 0) {
            $sum = $sum + $day;
            if ($year % 400 == 0 || ($year % 4 == 0 && $year % 100 != 0)) {
                $leap = 1;
            } else {
                $leap = 0;
            }
            if ($leap == 1 && $month == 2) {
                $sum++;
            }
            $tian = $sum;
        }
        return $tian;
    }




    // 创建模板
    static function createTpl($biaohou)
    {
        $classname = $biaohou;
        $str = '<?php
        namespace app\controller;
        use app\BaseController;
        use think\facade\Db;
        use think\Request;
        use \yuanhou\composer\Tool;
        class ' . $classname . ' extends BaseController
        {
        
            // index
            public function index(Request $request)
            {
                $biao = "yuanhou_";
        
                $biao .= "' . $classname . '";
        
                $biao = strtolower($biao);


                $jsonshuzu = ["图片", "列表值", "附件", "约束选择框"];

        
                $lx = $request->param("lx");
                if ($lx == "getlist") {
                    $tiaojian = [];
                    $keyword = $request->param("keyword");

                    $pid = $request->param("pid");
                    if ($pid) {
                        $tiaojian[] = ["pId", "=", $pid];
                    }
                    if ($keyword) {
        
                        $tiaojian[] = ["name|id", "like", "%" . $keyword . "%"];
                    }
                    // 获取列表
                    $page = $request->param("page") ?: 1;
        
                    $pagenum = $request->param("pagenum") ?: 20;
                    // 排序
                    $paixu1 = "id desc";
                    $shuju = Db::table($biao)->order($paixu1)->where("isdel", "0")->where($tiaojian)->paginate($pagenum, false, [
                        "var_page" => $page,
                    ]);
                    $list = $shuju->items();
                    $fenset = [
                        "page" => $shuju->currentPage(),
                        "pagenum" => $shuju->listRows(),
                        "total" => $shuju->total(),
                    ];
                    return json(fan_ok(["msg" => "查询成功", "list" => $list, "fenset" => $fenset]));
                } else if ($lx == "get") {
                    $id = $request->param("id");
                    // 查看详情
                    $r = Tool::find($biao, ["id" => $id]);
                    if ($r) {
                        $ziduanbiao = str_replace("yuanhou_", "", $biao);
                        // 查询当前标的多选框字段
                        $ziduans = Tool::cha("yuanhou_ziduan", [
                            ["isdel", "=", 0],
                            ["biao", "=", $ziduanbiao],
                            ["lx", "in", $jsonshuzu],
                        ]);
        
                        foreach ($ziduans as $k => $v) {
        
                            $ziduan = $v["ziduan"];
        
                            if ($r[$ziduan]) {
                                $r[$ziduan] = json_decode($r[$ziduan], true);
                            }
                        }
                        // 查询当前标的多选框字段
                        $ziduans  = Tool::cha("yuanhou_ziduan", [
                            ["isdel", "=", 0],
                            ["biao", "=", $ziduanbiao],
                            ["lx", "=", "多选框"],
        
                        ]);
        
                        foreach ($ziduans as $k => $v) {
        
                            $ziduan = $v["ziduan"];
        
                            if ($r[$ziduan]) {
        
                                $zizus = explode(",", $r[$ziduan]);
        
                                $shuzus = [];
        
                                foreach ($zizus as $k1 => $v1) {
                                    $shuzus[] = (int)$v1;
                                }
        
                                $r[$ziduan] = $shuzus;
                            }
                        }
        
        
                        // 查询当前标的多选框字段
                        $ziduans  = Tool::cha("yuanhou_ziduan", [
                            ["isdel", "=", 0],
                            ["biao", "=", $ziduanbiao],
                            ["lx", "=", "多选"],
        
                        ]);
        
                        foreach ($ziduans as $k => $v) {
        
                            $ziduan = $v["ziduan"];
        
                            if ($r[$ziduan]) {
        
                                $zizus = explode(",", $r[$ziduan]);
        
                                $shuzus = [];
        
                                foreach ($zizus as $k1 => $v1) {
                                    $shuzus[] = $v1;
                                }
        
                                $r[$ziduan] = $shuzus;
                            }else {
                                $r[$ziduan] = [];
                            }
                        }
        
                        return json(fan_ok(["msg" => "查询成功", "data" => $r]));
                    } else {
        
                        return json(fan_fail(["msg" => "查询失败"]));
                    }
                } else if ($lx == "del") {
                    $id = $request->param("id");
                    // 删除
                    $del = Tool::del($biao, $id);
        
                    if ($del) {
                        return json(fan_ok(["msg" => "删除成功"]));
                    } else {
                        return json(fan_fail(["msg" => "删除失败"]));
                    }
                } else if ($lx == "add") {
        
                    // 新增
                    $data = $request->param("data");
                    $ziduanbiao = str_replace("yuanhou_", "", $biao);
                    // 查询当前标的多选框字段
                    $ziduans  = Tool::cha("yuanhou_ziduan", [
                        ["isdel", "=", 0],
                        ["biao", "=", $ziduanbiao],
        
                        ["lx", "in", $jsonshuzu],
                    ]);
        
        
                    foreach ($ziduans as $k => $v) {
                        $ziduan = $v["ziduan"];
        
                        $ziduan = $v["ziduan"];
        
        
                        if (isset($data[$ziduan])) {
                            $data[$ziduan] = json_encode($data[$ziduan]);
                        }
                    }
                    // 查询当前标的多选框字段
                    $ziduans = Tool::cha("yuanhou_ziduan", [
                        ["isdel", "=", 0],
                        ["biao", "=", $ziduanbiao],
                        ["lx", "in", ["多选框", "多选"]],
        
        
                    ]);
        
                    foreach ($ziduans as $k => $v) {
        
                        $ziduan = $v["ziduan"];
        
        
                        if (isset($data[$ziduan])) {
                            $data[$ziduan] = implode(",", $data[$ziduan]);
                        }
                    }
        
                    $data["intime"] = xianzai();
                    $data["uptime"] = xianzai();
        
                    // 这里对图片进行处理
                    if (isset($data["pics"])) {
                        $pics = $data["pics"];
                        $data["pics"] = json_encode($pics);
                    }
        
                    $in = Db::name($biao)->insert($data);
        
                    if ($in) {
                        return json(fan_ok(["msg" => "添加成功"]));
                    } else {
                        return json(fan_fail(["msg" => "添加失败"]));
                    }
                } else if ($lx == "edit") {
                    // 编辑
                    $data = $request->param("data");
                    $data["uptime"] = xianzai();
                    $ziduanbiao = str_replace("yuanhou_", "", $biao);
                    // 查询当前标的多选框字段
        
                    $ziduans = Tool::cha("yuanhou_ziduan", [
                        ["isdel", "=", 0],
                        ["biao", "=", $ziduanbiao],
                        ["lx", "in", $jsonshuzu],
        
                    ]);
        
                    foreach ($ziduans as $k => $v) {
                        $ziduan = $v["ziduan"];
        
                        $ziduan = $v["ziduan"];
        
        
                        if (isset($data[$ziduan])) {
                            $data[$ziduan] = json_encode($data[$ziduan]);
                        }
                    }
                    // 查询当前标的多选框字段
                    
        
                    $ziduans = Tool::cha("yuanhou_ziduan", [
                        ["isdel", "=", 0],
                        ["biao", "=", $ziduanbiao],
                        ["lx", "in", ["多选框", "多选"]],
                    ]);
                    foreach ($ziduans as $k => $v) {
        
                        $ziduan = $v["ziduan"];
        
        
                        if (isset($data[$ziduan])) {
                            $data[$ziduan] = implode(",", $data[$ziduan]);
                        }
                    }
                    $id = $request->param("id");
                    // 这里对图片进行处理
                    if (isset($data["pics"])) {
                        $pics = $data["pics"];
                        $data["pics"] = json_encode($pics);
                    }
        
                    $up = Tool::update($biao, $id, $data);
        
        
                    if ($up) {
                        return json(fan_ok(["msg" => "更新成功"]));
                    } else {
                        return json(fan_fail(["msg" => "更新失败"]));
                    }
                } else if ($lx == "dels") {
        
                    $ids = $request->param("ids");
                    // var_dump($ids);
        
                    $up = Db::table($biao)->where([
                        ["isdel", "=", 0],
                        ["id", "in", $ids],
                    ])->update(
                        ["isdel" => 1]
                    );
                    if ($up) {
                        return json(fan_ok(["msg" => "删除成功"]));
                    } else {
                        return json(fan_fail(["msg" => "删除失败"]));
                    }
                } else if ($lx == "getlanmuall") {
        
                    $jieguo =  Tool::getMenuAll($biao);
        
                    return json(fan_ok(["msg" => "查询成功", "list" => $jieguo]));
                } else if ($lx == "copy") {
        
                    $id = $request->param("id");
                    $r = Db::table($biao)->where([
                        ["isdel", "=", 0],
                        ["id", "=", $id],
                    ])->find();
        
        
                    unset($r["id"]);
        
                    $r["intime"] = Tool::xianzai();
        
                    $r["uptime"] = Tool::xianzai();
        
                    $in = Db::name($biao)->insert($r);
        
                    if ($in) {
                        return json(fan_ok(["msg" => "复制成功"]));
                    } else {
                        return json(fan_fail(["msg" => "复制失败"]));
                    }
                }
            }
        }
        ';


        $lujing = CONTROLLER . '/' . $classname . '.php';

        try {

            $nei = file_get_contents($lujing);


            return false;
        } catch (\Exception $e) {

            file_put_contents($lujing, $str);
            return true;
        }
    }



    static function wuweinavhou($request)
    {



        $biao = "yuanhou_navhou";
        $lx = $request->param('lx');
        if ($lx == 'getlist') {


            $admintoken = $request->param('admintoken');


            // 获取admin
            $admin = Db::name('yuanhou_admin')->where('token', $admintoken)->find();


            $jueseid = $admin['jueseid'];
            $tiaojian = [];


            if ($jueseid) {

                // 查询角色
                $juese = Db::name('yuanhou_juese')->where('id', $jueseid)->find();
                // var_dump($juese);
                // 获取标识

                $biaoshi = $juese['biaoshi'];



                if ($biaoshi) {

                    $tiaojian[] = ['juesexuan', 'like', '%' . $biaoshi . '%'];
                }


                // var_dump($biaoshi);
            }












            $pId = $request->param('pId');





            $tiaojian[] = ['pId', '=', '0'];
            $tiaojian1 = [];

            if (isset($biaoshi) && $biaoshi) {
                $tiaojian1[] = ['juesexuan', 'like', '%' . $biaoshi . '%'];
            }

            $navs = Tool::getDigui('yuanhou_navhou', $tiaojian1, 'paixu desc');



            // 获取列表
            $page = $request->param('page') ?: 1;

            $pagenum = $request->param('pagenum') ?: 20;

            $shuju = Db::table($biao)->order('paixu desc')->where('isdel', '0')->where($tiaojian)->paginate($pagenum, false, [
                'var_page' => $page,
            ]);
            $list = $shuju->items();

            // 查询子栏目
            // foreach ($list as $k => $v) {

            //     $tiaojian1 = [];

            //     if (isset($biaoshi) && $biaoshi) {
            //         $tiaojian1[] = ['juesexuan', 'like', '%' . $biaoshi . '%'];
            //     }

            //     $list1 =  Db::table($biao)->order('paixu desc')->where('pId', $v['id'])->where($tiaojian1)->where('isdel', '0')->select()->toArray();



            //     foreach ($list1 as $k1 => $v1) {

            //         $tiaojian2 = [];

            //         if (isset($biaoshi) && $biaoshi) {
            //             $tiaojian2[] = ['juesexuan', 'like', '%' . $biaoshi . '%'];
            //         }

            //         $list2 =  Db::table($biao)->order('paixu desc')->where('pId', $v1['id'])->where($tiaojian2)->where('isdel', '0')->select()->toArray();




            //         $list1[$k1]['list'] = $list2;
            //     }



            //     $list[$k]['list'] = $list1;
            // }




            $fenset = [
                'page' => $shuju->currentPage(),
                'pagenum' => $shuju->listRows(),
                'total' => $shuju->total(),
            ];
            // $fanhui = $this->good(['msg' => '查询成功', 'list' => $list, 'fenset' => $fenset]);
            return json(fan_ok(['msg' => '查询成功', 'list' => $navs, 'fenset' => $fenset]));
        } else if ($lx == 'get') {
            $id = $request->param('id');
            // 查看详情
            $r = Db::name($biao)->where('id', $id)->find();
            if ($r) {

                return json(fan_ok(['msg' => '查询成功', 'data' => $r]));
            } else {

                return json(fan_fail(['msg' => '查询失败']));
            }
        } else if ($lx == 'del') {
            $id = $request->param('id');
            // 删除
            $data = ['isdel' => 1];
            $del = Db::name($biao)->where('id', $id)->update($data);

            if ($del) {
                return json(fan_ok(['msg' => '删除成功']));
            } else {
                return json(fan_fail(['msg' => '删除失败']));
            }
        } else if ($lx == 'add') {

            // 新增
            $data = $request->param('data');

            $data['intime'] = xianzai();
            $data['uptime'] = xianzai();


            $biao1 = 'yuanhou_' . $data['biao'];
            $biaohou = ucfirst($data['biao']);





            $sql = "SELECT table_name FROM information_schema.TABLES WHERE table_name ='" . $biao1 . "'";

            $r1 = Db::query($sql);


            $juesexuan = json_encode($data['juesexuan']);



            $data['juesexuan'] = $juesexuan;


            unset($data['shangji']);


            // 这里对图片进行处理
            if (isset($data['pics'])) {
                $pics = $data['pics'];
                $data['pics'] = json_encode($pics);
            }




            $in = Db::name($biao)->insert($data);

            if ($in) {



                if (count($r1) == 0) {
                    // 说明没有此表
                    // 创建表

                    $sql2 = "CREATE TABLE `" . $biao1 . "` (
                        `id` int unsigned NOT NULL AUTO_INCREMENT,
                        `name` varchar(255) DEFAULT NULL,
                        `leixing` varchar(255) DEFAULT NULL,
                        `icon` varchar(255) DEFAULT NULL,
                        `xiangxi` varchar(255) DEFAULT NULL,
                        `tpl` varchar(255) DEFAULT NULL,
                        `content` longtext,
                        `adminid` int DEFAULT '0',
                        `isdel` int DEFAULT '0',
                        `pId` int DEFAULT '0',
                        `intime` datetime DEFAULT NULL,
                        `pics` text,
                        `uptime` datetime DEFAULT NULL,
                        PRIMARY KEY (`id`) USING BTREE
                        ) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;";
                    Db::query($sql2);
                }

                if ($data['tpl'] == 'fenleilist1') {
                    $biao22 = $biao1 . 'list';

                    $sql = "SELECT table_name FROM information_schema.TABLES WHERE table_name ='" . $biao22 . "'";

                    $r2 = Db::query($sql);
                    if (count($r2) == 0) {
                        // 说明没有此表
                        // 创建表

                        $sql2 = "CREATE TABLE `" . $biao22 . "` (
                            `id` int unsigned NOT NULL AUTO_INCREMENT,
                            `name` varchar(255) DEFAULT NULL,
                            `leixing` varchar(255) DEFAULT NULL,
                            `icon` varchar(255) DEFAULT NULL,
                            `xiangxi` varchar(255) DEFAULT NULL,
                            `tpl` varchar(255) DEFAULT NULL,
                            `content` longtext,
                            `adminid` int DEFAULT '0',
                            `isdel` int DEFAULT '0',
                            `pId` int DEFAULT '0',
                            `intime` datetime DEFAULT NULL,
                            `pics` text,
                            `uptime` datetime DEFAULT NULL,
                            PRIMARY KEY (`id`) USING BTREE
                            ) ENGINE=InnoDB AUTO_INCREMENT=55 DEFAULT CHARSET=utf8mb3 ROW_FORMAT=COMPACT;";
                        Db::query($sql2);
                    }



                    Tool::createTpl($biaohou . 'list');
                }


                Tool::createTpl($biaohou);


                // +++


                return json(fan_ok(['msg' => '添加成功']));
            } else {
                return json(fan_fail(['msg' => '添加失败']));
            }
        } else if ($lx == 'edit') {


            // 编辑
            $data = $request->param('data');
            $data['uptime'] = xianzai();
            $id = $request->param('id');

            $juesexuan = json_encode($data['juesexuan']);


            $data['juesexuan'] = $juesexuan;



            // 这里对图片进行处理
            if (isset($data['pics'])) {
                $pics = $data['pics'];
                $data['pics'] = json_encode($pics);
            }

            $up = Db::name($biao)->where('id', $id)->update($data);

            if ($up) {
                return json(fan_ok(['msg' => '更新成功']));
            } else {
                return json(fan_fail(['msg' => '更新失败']));
            }
        } else if ($lx == 'getlanmuall') {

            // var_dump('121212');
            $jieguo =  self::getMenuAll1();
            return json(fan_ok(['msg' => '查询成功', 'list' => $jieguo]));
        } else if ($lx == 'getlie') {
            $biao = $request->param('biao');


            $fuid = $request->param('fuid');

            // 查询栏目


            $fu = Db::table('yuanhou_navhou')->where([
                ['isdel', '=', 0],
                ['id', '=', $fuid],

            ])->find();




            $pId = $fu['id'];

            $ls = Db::table('yuanhou_navhou')->where([
                ['isdel', '=', 0],
                ['pId', '=', $pId],
                ['xianshi', '=', 1],
            ])->order('paixu desc')->select()->toArray();








            // var_dump($ls);

            return json(fan_ok(['msg' => '查询成功', 'list' => $ls, 'fu' => $fu]));
        } else if ($lx == 'getzilanmubybiao') {

            $biao = $request->param('biao');



            // 查询当前的表
            $r = Db::table('yuanhou_navhou')->where([
                ['isdel', '=', 0],
                ['biao', '=', $biao],
            ])->find();


            $id = $r['id'];
            // var_dump($id);

            // 查询当前栏目的子栏目
            $ls = Db::table('yuanhou_navhou')->where([
                ['isdel', '=', 0],
                ['pId', '=', $id],
            ])->select()->toArray();




            return json(fan_ok(['msg' => '查询成功', 'list' => $ls, 'shangmian' => $r]));
        }
    }



    static function wuweiziduan($request)
    {



        $biao = "yuanhou_ziduan";
        $lx = $request->param('lx');
        if ($lx == 'getlist') {
            $tiaojian = [];

            $biao1 = $request->param('biao') ?: '';


            if ($biao1) {
                $tiaojian[] = ['biao', '=', $biao1];
                $tiaojian[] = ['isdel', '=', 0];
            }


            // var_dump($biao1);


            // 获取列表
            $page = $request->param('page') ?: 1;

            $pagenum = $request->param('pagenum') ?: 20;

            $shuju = Db::table($biao)->order('paixu desc')->where('isdel', '0')->where($tiaojian)->paginate($pagenum, false, [
                'var_page' => $page,
            ]);
            $list = $shuju->items();
            $fenset = [
                'page' => $shuju->currentPage(),
                'pagenum' => $shuju->listRows(),
                'total' => $shuju->total(),
            ];
            // $fanhui = $this->good(['msg' => '查询成功', 'list' => $list, 'fenset' => $fenset]);
            return json(fan_ok(['msg' => '查询成功', 'list' => $list, 'fenset' => $fenset]));
        } else if ($lx == 'get') {
            $id = $request->param('id');
            // 查看详情
            $r = Db::name($biao)->where('id', $id)->find();
            if ($r) {
                if ($r['xuanxiang']) {
                    $r['xuanxiang'] = json_decode($r['xuanxiang'], true);
                }



                return json(fan_ok(['msg' => '查询成功', 'data' => $r]));
            } else {

                return json(fan_fail(['msg' => '查询失败']));
            }
        } else if ($lx == 'del') {
            $id = $request->param('id');
            // 删除
            $data = ['isdel' => 1];
            $del = Db::name($biao)->where('id', $id)->update($data);

            if ($del) {
                return json(fan_ok(['msg' => '删除成功']));
            } else {
                return json(fan_fail(['msg' => '删除失败']));
            }
        } else if ($lx == 'add') {

            // 新增
            $data = $request->param('data');

            $data['intime'] = xianzai();
            $data['uptime'] = xianzai();

            // 这里对图片进行处理
            if (isset($data['pics'])) {
                $pics = $data['pics'];
                $data['pics'] = json_encode($pics);
            }




            $biao1 = $data['biao'];
            $ziduan = $data['ziduan'];

            $moren = '';

            if (isset($data['moren'])) {
                $moren = $data['moren'];
                if ($moren == '') {
                    $moren = '';
                }
            }


            $lx = $data['lx'];

            $shuzu = Db::query("show COLUMNS FROM " . 'yuanhou_' . $biao1);

            // var_dump($shuzu);


            $Fields = array_column($shuzu, 'Field');
            // var_dump($Fields);

            $jieguo = array_search($ziduan, $Fields);

            if ($jieguo) {
            } else {


                if ($lx == '文本') {
                    $zifu = '';

                    if ($moren) {
                        $zifu = "DEFAULT '" . $moren . "'";
                    }
                    // 如果没有字段的话,新增字段
                    $sql = "ALTER TABLE " . 'yuanhou_' . $biao1 . " ADD " . $ziduan . " varchar(255) " . $zifu;
                    Db::execute($sql);
                } else if ($lx == '价格') {
                    $zifu = '';

                    if ($moren) {
                        $zifu = "DEFAULT '" . $moren . "'";
                    }
                    $sql = "ALTER TABLE " . 'yuanhou_' . $biao1 . " ADD " . $ziduan . " decimal(10,2)  " . $zifu;
                    Db::execute($sql);
                } else if ($lx == '富文本') {
                    $zifu = '';

                    if ($moren) {
                        $zifu = "DEFAULT '" . $moren . "'";
                    }
                    $sql = "ALTER TABLE " . 'yuanhou_' . $biao1 . " ADD " . $ziduan . " text  " . $zifu;
                    Db::execute($sql);
                } else if ($lx == '数字') {
                    $zifu = '';

                    if ($moren) {
                        $zifu = "DEFAULT '" . $moren . "'";
                    }
                    $sql = "ALTER TABLE " . 'yuanhou_' . $biao1 . " ADD " . $ziduan . " int(11)  " . $zifu;
                    Db::execute($sql);
                } else if ($lx == '选择框') {
                    $zifu = '';

                    if ($moren) {
                        $zifu = "DEFAULT '" . $moren . "'";
                    }
                    $sql = "ALTER TABLE " . 'yuanhou_' . $biao1 . " ADD " . $ziduan . " int(11)  " . $zifu;
                    Db::execute($sql);
                } else if ($lx == '开关') {
                    $zifu = '';

                    if ($moren) {
                        $zifu = "DEFAULT '" . $moren . "'";
                    }
                    $sql = "ALTER TABLE " . 'yuanhou_' . $biao1 . " ADD " . $ziduan . " int(11)  " . $zifu;
                    Db::execute($sql);
                } else if ($lx == '多选框') {

                    $zifu = '';

                    if ($moren) {
                        $zifu = "DEFAULT '" . $moren . "'";
                    }





                    $sql = "ALTER TABLE " . 'yuanhou_' . $biao1 . " ADD " . $ziduan . " varchar(500)  " . $zifu;

                    Db::execute($sql);
                } else if ($lx == '图片') {
                    $zifu = '';

                    if ($moren) {
                        $zifu = "DEFAULT '" . $moren . "'";
                    }
                    $sql = "ALTER TABLE " . 'yuanhou_' . $biao1 . " ADD " . $ziduan . " text  " . $zifu;
                    Db::execute($sql);
                } else if ($lx == '附件') {


                    $zifu = '';

                    if ($moren) {
                        $zifu = "DEFAULT '" . $moren . "'";
                    }

                    $sql = "ALTER TABLE " . 'yuanhou_' . $biao1 . " ADD " . $ziduan . " text  " . $zifu;
                    Db::execute($sql);
                } else if ($lx == '编辑器') {
                    $zifu = '';

                    if ($moren) {
                        $zifu = "DEFAULT '" . $moren . "'";
                    }
                    $sql = "ALTER TABLE " . 'yuanhou_' . $biao1 . " ADD " . $ziduan . " longtext  " . $zifu;
                    Db::execute($sql);
                } else if ($lx == '约束选择框') {
                    $zifu = '';

                    if ($moren) {
                        $zifu = "DEFAULT '" . $moren . "'";
                    }
                    $sql = "ALTER TABLE " . 'yuanhou_' . $biao1 . " ADD " . $ziduan . " varchar(5000)  " . $zifu;
                    Db::execute($sql);
                } else if ($lx == '单选') {


                    $zifu = '';

                    if ($moren) {
                        $zifu = "DEFAULT '" . $moren . "'";
                    }
                    $sql = "ALTER TABLE " . 'yuanhou_' . $biao1 . " ADD " . $ziduan . " varchar(5000)  " . $zifu;


                    Db::execute($sql);
                } else if ($lx == '多选') {

                    $zifu = '';

                    if ($moren) {
                        $zifu = "DEFAULT '" . $moren . "'";
                    }
                    $sql = "ALTER TABLE " . 'yuanhou_' . $biao1 . " ADD " . $ziduan . " varchar(1000)  " . $zifu;

                    Db::execute($sql);
                } else if ($lx == '颜色') {
                    $sql = "ALTER TABLE " . 'yuanhou_' . $biao1 . " ADD " . $ziduan . " varchar(255) DEFAULT " . "'" . $moren . "'";
                    Db::execute($sql);
                } else if ($lx == '日期') {
                    $sql = "ALTER TABLE " . 'yuanhou_' . $biao1 . " ADD " . $ziduan . " date ";
                    Db::execute($sql);
                } else if ($lx == '日期时间') {
                    $sql = "ALTER TABLE " . 'yuanhou_' . $biao1 . " ADD " . $ziduan . " datetime ";
                    Db::execute($sql);
                } else if ($lx == '评分') {
                    $sql = "ALTER TABLE " . 'yuanhou_' . $biao1 . " ADD " . $ziduan . " varchar(255) DEFAULT " . "'" . $moren . "'";

                    Db::execute($sql);
                } else if ($lx == '滑块') {

                    $sql = "ALTER TABLE " . 'yuanhou_' . $biao1 . " ADD " . $ziduan . " int(11) DEFAULT " . $moren;



                    Db::execute($sql);
                } else if ($lx == '选择框带值') {


                    $sql = "ALTER TABLE " . 'yuanhou_' . $biao1 . " ADD " . $ziduan . " varchar(5000) DEFAULT " . "'" . $moren . "'";





                    Db::execute($sql);
                } else if ($lx == '列表值') {


                    // $sql = "ALTER TABLE " . 'yuanhou_' . $biao1 . " ADD " . $ziduan . " varchar(5000) DEFAULT " . "'" . $moren . "'";

                    $zifu = '';

                    if ($moren) {
                        $zifu = "DEFAULT '" . $moren . "'";
                    }





                    $sql = "ALTER TABLE " . 'yuanhou_' . $biao1 . " ADD " . $ziduan . " text  " . $zifu;






                    Db::execute($sql);
                }
            }


            if (isset($data['xuanxiang'])) {
                $data['xuanxiang'] = json_encode($data['xuanxiang']);
            }




            $in = Db::name($biao)->insert($data);

            if ($in) {
                return json(fan_ok(['msg' => '添加成功']));
            } else {
                return json(fan_fail(['msg' => '添加失败']));
            }
        } else if ($lx == 'edit') {
            // 编辑
            $data = $request->param('data');
            $data['uptime'] = xianzai();
            $id = $request->param('id');
            // 这里对图片进行处理
            if (isset($data['pics'])) {
                $pics = $data['pics'];
                $data['pics'] = json_encode($pics);
            }

            if (isset($data['xuanxiang'])) {
                $data['xuanxiang'] = json_encode($data['xuanxiang']);
            }

            $up = Db::name($biao)->where('id', $id)->update($data);

            if ($up) {
                return json(fan_ok(['msg' => '更新成功']));
            } else {
                return json(fan_fail(['msg' => '更新失败']));
            }
        } else if ($lx == 'liebiaoxianshi') {


            $id = $request->param('id');

            $up = Db::table('yuanhou_ziduan')->where([
                ['isdel', '=', 0],
                ['id', '=', $id],
            ])->update(
                [
                    'isliebiao' => 1
                ]
            );

            if ($up) {

                return json(fan_ok(['msg' => '更新成功']));
            } else {

                return json(fan_fail(['msg' => '更新失败']));
            }





            // var_dump($id);

        } else if ($lx == 'liebiaoyincang') {

            $id = $request->param('id');

            $up = Db::table('yuanhou_ziduan')->where([
                ['isdel', '=', 0],
                ['id', '=', $id],
            ])->update(
                [
                    'isliebiao' => 0
                ]
            );

            if ($up) {

                return json(fan_ok(['msg' => '更新成功']));
            } else {

                return json(fan_fail(['msg' => '更新失败']));
            }
        } else if ($lx == 'paixu') {
            $id = $request->param('id');
            $paixu = $request->param('paixu');
            // 更新排序
            $up = Db::table('yuanhou_ziduan')->where('id', $id)->update(['paixu' => $paixu]);

            if ($up) {
                return json(fan_ok(['msg' => '更新成功']));
            } else {
                return json(fan_fail(['msg' => '更新失败']));
            }
        } else if ($lx == 'getguanlian') {
            $biao = $request->param('biao');

            // 查询当前表
            $ls = Db::table('yuanhou_ziduan')->where([
                ['isdel', '=', 0],
                ['biao', '=', $biao],
                ['guanlianbiao', '<>', 'null'],
            ])->select()->toArray();



            $zuzu = [];


            foreach ($ls as $k => $v) {



                $lx = $v['lx'];
                $guanlianbiao = $v['guanlianbiao'];
                if (
                    $lx == '约束选择框'
                ) {

                    $jieguo = Tool::getMenuAll('yuanhou_' . $guanlianbiao);
                    $zuzu[$guanlianbiao] = $jieguo;
                } else {


                    $biaoming = 'yuanhou_' . $guanlianbiao;

                    // var_dump($biaoming);


                    // 查询当前关联表
                    $ls2 = Db::table($biaoming)->where([
                        ['isdel', '=', 0],

                    ])->select()->toArray();




                    $zuzu[$guanlianbiao] = $ls2;
                }





                // var_dump($guanlianbiao);
            }



            return json(fan_ok(['msg' => '查询成功', 'data' => $zuzu]));
        } else if ($lx == 'getguanlianliebiao') {
            $biao = $request->param('biao');

            // 查询当前表
            $ls = Db::table('yuanhou_ziduan')->where([
                ['isdel', '=', 0],
                ['biao', '=', $biao],
                ['isliebiao', '=', 1],
                ['guanlianbiao', '<>', 'null'],
            ])->select()->toArray();



            $zuzu = [];


            foreach ($ls as $k => $v) {

                $guanlianbiao = $v['guanlianbiao'];

                $biaoming = 'yuanhou_' . $guanlianbiao;

                // var_dump($biaoming);


                // 查询当前关联表
                $ls2 = Db::table($biaoming)->where([
                    ['isdel', '=', 0],

                ])->select()->toArray();




                $zuzu[$guanlianbiao] = $ls2;





                // var_dump($guanlianbiao);
            }



            return json(fan_ok(['msg' => '查询成功', 'data' => $zuzu]));
        } else if ($lx == 'getpeizhi') {

            // var_dump('222');

            $tiaojian = [];
            $admintoken = $request->param('admintoken');

            // var_dump($admintoken);

            // 查询admin
            $admin = Db::table('yuanhou_admin')->where([
                ['isdel', '=', 0],
                ['token', '=', $admintoken],
            ])->find();



            $jueseid = $admin['jueseid'];


            // var_dump($jueseid);


            if ($jueseid) {
                // 查询角色
                $juese = Db::table('yuanhou_juese')->where([
                    ['isdel', '=', 0],
                    ['id', '=', $jueseid],
                ])->find();


                $biaoshi = $juese['biaoshi'];

                if ($biaoshi) {

                    $tiaojian[] = ['juesexuan', 'like', '%' . $biaoshi . '%'];
                }

                // var_dump($biaoshi);
            }






            $ziduan = Db::table('yuanhou_ziduan')->where([
                ['isdel', '=', 0],
            ])->select()->toArray();

            // 查询配置


            $pId = $request->param('pId');



            $tiaojian[] = ['pId', '=', '0'];




            // 获取列表
            $page = $request->param('page') ?: 1;

            $pagenum = $request->param('pagenum') ?: 20;

            $shuju = Db::table('yuanhou_navhou')->order('id desc')->where('isdel', '0')->where($tiaojian)->paginate($pagenum, false, [
                'var_page' => $page,
            ]);
            $list = $shuju->items();

            // 查询子栏目
            // foreach ($list as $k => $v) {

            //     $tiaojian1 = [];


            //     if (isset($biaoshi) && $biaoshi) {

            //         $tiaojian1[] = ['juesexuan', 'like', '%' . $biaoshi . '%'];
            //     }


            //     $list[$k]['list'] = Db::table('yuanhou_navhou')->where('pId', $v['id'])->where($tiaojian1)->where('isdel', '0')->select();
            // }



            $tiaojian1 = [];

            if (isset($biaoshi) && $biaoshi) {
                $tiaojian1[] = ['juesexuan', 'like', '%' . $biaoshi . '%'];
            }


            $nav1  = Tool::getDigui('yuanhou_navhou', $tiaojian1, 'paixu desc');




            // foreach ($list as $k => $v) {

            //     $tiaojian1 = [];

            //     if (isset($biaoshi) && $biaoshi) {
            //         $tiaojian1[] = ['juesexuan', 'like', '%' . $biaoshi . '%'];
            //     }

            //     $list1 =  Db::table('yuanhou_navhou')->order('paixu desc')->where('pId', $v['id'])->where($tiaojian1)->where('isdel', '0')->select()->toArray();



            //     foreach ($list1 as $k1 => $v1) {

            //         $tiaojian2 = [];

            //         if (isset($biaoshi) && $biaoshi) {
            //             $tiaojian2[] = ['juesexuan', 'like', '%' . $biaoshi . '%'];
            //         }

            //         $list2 =  Db::table('yuanhou_navhou')->order('paixu desc')->where('pId', $v1['id'])->where($tiaojian2)->where('isdel', '0')->select()->toArray();




            //         $list1[$k1]['list'] = $list2;
            //     }



            //     $list[$k]['list'] = $list1;
            // }




            // var_dump($ziduan);


            // var_dump($list);

            // 查询搜索数据
            $sous = Db::table('yuanhou_sou')->where([
                ['isdel', '=', 0],
            ])->select()->toArray();





            return json(fan_ok(['msg' => '查询成功', 'list' => $ziduan, 'navhous' => $nav1, 'sous' => $sous]));
        }
    }



    /** 
     * 计算两个经纬度坐标 之间的距离,返回的是千米或米
     * params ：lat1 纬度1； lng1 经度1； lat2 纬度2； lng2 经度2；  
     * miles true时返回千米，false时返回米
     */
    static function getJuli($lat1, $lng1, $lat2, $lng2, $miles = true)
    {
        $pi80 = M_PI / 180;
        $lat1 *= $pi80;
        $lng1 *= $pi80;
        $lat2 *= $pi80;
        $lng2 *= $pi80;
        $r = 6372.797; // mean radius of Earth in km
        $dlat = $lat2 - $lat1;
        $dlng = $lng2 - $lng1;
        $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlng / 2) * sin($dlng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $km = $r * $c;
        return $miles ? ($km * 0.621371192 * 1.609344) : ($km * 0.621371192 * 1.609344 * 1000);
    }



    static  function baiduJuli($lat1 = 0, $lng1 = 0, $lat2 = 0, $lng2 = 0, $ak = "t1GTuZRbjUzLHlcrwGIkKaKy7bfihKiN")
    {
        $result = array();
        $result['distance'] = 0.00; //距离 公里
        $result['duration'] = 0.00; //时间 分钟
        $ak = $ak;
        $url = 'http://api.map.baidu.com/routematrix/v2/driving?output=json&origins=' . $lat1 . ',' . $lng1 . '&destinations=' . $lat2 . ',' . $lng2 . '&ak=' . $ak;
        $data = file_get_contents($url);

        // var_dump($data);

        $data = json_decode($data, true);






        if (!empty($data) && $data['status'] == 0) {
            $result['distance'] = $data['result'][0]['distance']['text'];
            $result['duration'] = $data['result'][0]['duration']['text'];
        }
        return $result;
    }




    //  查询字符串中是否包含某个字符串
    static function isBaohan($zifuchuan, $zifu)
    {
        if (strpos($zifuchuan, $zifu) !== false) {
            return true;
        } else {

            return false;
        }
    }



    static public function getMenuAll($biao = 'yuanhou_lanmu')
    {
        // $res = self::where('hid', 0)->field('id,pid,url,icon,title,sort,group')->order('pid', 'asc')->select()->toArray();

        $ls = Db::table($biao)->where([
            ['isdel', '=', 0],
        ])->select()->toArray();
        foreach ($ls as $k => $v) {

            // $ls[$k]['label'] = $v['name'] . ':' . $v['id'];
            $ls[$k]['label'] = $v['name'];
            $ls[$k]['value'] = $v['id'];
        }






        return self::makeArr($ls);
    }

    // 递归处理
    static public function getDigui($biao = 'yuanhou_lanmu', $tiaojian = [], $paixu = 'paixu desc')
    {
        // $res = self::where('hid', 0)->field('id,pid,url,icon,title,sort,group')->order('pid', 'asc')->select()->toArray();

        $ls = Db::table($biao)->where([
            ['isdel', '=', 0],
        ])->order($paixu)->where($tiaojian)->select()->toArray();




        foreach ($ls as $k => $v) {

            // $ls[$k]['label'] = $v['name'] . ':' . $v['id'];
            $ls[$k]['label'] = $v['name'];
        }


        return self::makeArrDigui($ls);
    }


    // 获取随机数字
    static public function getRandCode($num = 4)
    {
        // $res = self::where('hid', 0)->field('id,pid,url,icon,title,sort,group')->order('pid', 'asc')->select()->toArray();

        if ($num == 4) {
            return rand(1000, 9999);
        } else if ($num == 6) {
            return rand(100000, 999999);
        }
    }

    static public function getMenuAlllanmu()
    {
        // $res = self::where('hid', 0)->field('id,pid,url,icon,title,sort,group')->order('pid', 'asc')->select()->toArray();

        $ls = Db::table('yuanhou_lanmufujian')->where([
            ['isdel', '=', 0],
        ])->select()->toArray();
        foreach ($ls as $k => $v) {

            $ls[$k]['label'] = $v['name'] . ':' . $v['id'];
        }


        return self::makeArr($ls);
    }




    static public function getMenuAll1()
    {
        // $res = self::where('hid', 0)->field('id,pid,url,icon,title,sort,group')->order('pid', 'asc')->select()->toArray();

        $ls = Db::table('yuanhou_navhou')->where([
            ['isdel', '=', 0],
        ])->order('paixu desc')->select()->toArray();
        foreach ($ls as $k => $v) {



            $ls[$k]['label'] = $v['name'] . ':' . $v['id'];
        }

        return self::makeArr($ls);
    }



    /**
     * 递归循环
     * @param $res  总数组
     * @param int $pid 父级id
     * @return array
     */
    static  public function makeArr($res, $pid = 0)
    {
        $arr = [];
        $item['pId'] = $pid;
        $data = self::screen($res, $item);
        foreach ($data as $key => $val) {
            $ite['pId'] = $val['id'];
            $result = self::screen($res, $ite);
            if (!empty($result)) {
                $val['children'] = self::makeArr($res, $val['id']);
            }
            $arr[] = $val;
        }
        return  $arr;
    }
    /**
     * 递归循环
     * @param $res  总数组
     * @param int $pid 父级id
     * @return array
     */
    static  public function makeArrDigui($res, $pid = 0)
    {
        $arr = [];
        $item['pId'] = $pid;
        $data = self::screen($res, $item);
        foreach ($data as $key => $val) {
            $ite['pId'] = $val['id'];
            $result = self::screen($res, $ite);
            if (!empty($result)) {
                $val['list'] = self::makeArrDigui($res, $val['id']);
            }
            $arr[] = $val;
        }
        return  $arr;
    }

    /**
     * 数组键值对查询 返回查询数组
     * @param $res
     * @param $val
     * @return array
     */
    static public function screen($res, $val)
    {
        return array_filter($res, function ($var) use ($val) {
            if ($var['pId'] == $val['pId']) return true;
        });
    }



    static public function ClearHtml($str)

    {

        $str = trim($str); //清除字符串两边的空格

        $str = strip_tags($str, ""); //利用php自带的函数清除html格式

        $str = preg_replace("/\t/", "", $str); //使用正则表达式替换内容，如：空格，换行，并将替换为空。

        $str = preg_replace("/\r\n/", "", $str);

        $str = preg_replace("/\r/", "", $str);

        $str = preg_replace("/\n/", "", $str);

        $str = preg_replace("/ /", "", $str);

        $str = preg_replace("/ /", "", $str); //匹配html中的空格

        return trim($str); //返回字符串

    }

    // 描述
    static public function miaoshu($str, $num)
    {
        $neinei = self::ClearHtml($str);
        $neinei = htmlspecialchars($neinei);
        $miaoshu = mb_substr($neinei, 0, $num, 'UTF-8');

        return $miaoshu;
    }



    // 星期几
    static function getWeek($date)
    {

        //强制转换日期格式

        $date_str = date('Y-m-d', strtotime($date));



        //封装成数组

        $arr = explode("-", $date_str);



        //参数赋值

        //年

        $year = $arr[0];



        //月，输出2位整型，不够2位右对齐

        $month = sprintf('%02d', $arr[1]);



        //日，输出2位整型，不够2位右对齐

        $day = sprintf('%02d', $arr[2]);



        //时分秒默认赋值为0；

        $hour = $minute = $second = 0;



        //转换成时间戳

        $strap = mktime($hour, $minute, $second, $month, $day, $year);



        //获取数字型星期几

        $number_wk = date("w", $strap);



        //自定义星期数组

        $weekArr = array("星期日", "星期一", "星期二", "星期三", "星期四", "星期五", "星期六");



        //获取数字对应的星期

        return $weekArr[$number_wk];
    }
}
