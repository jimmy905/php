<?php

namespace yuanhou\composer;

use think\facade\Db;
use think\Request;


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


    // 获取会员
    static function gethuiyuan(Request $request)
    {

        $openid = $request->param('token');

        // 查询会员
        $huiyuan = Db::name("yuanhou_user")->where('token', $openid)->find();

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
    static function xianzai()
    {

        return date("Y-m-d H:i:s", time());
    }


    // 获取位置
    static function getweizhi($longitude, $latitude)
    {
        //调取百度接口,其中ak为百度帐号key,注意location纬度在前，经度在后

        $api = "http://api.map.baidu.com/reverse_geocoding/v3/?ak=t1GTuZRbjUzLHlcrwGIkKaKy7bfihKiN&output=json&coordtype=wgs84ll&location=" . $latitude . "," . $longitude;

        $content = file_get_contents($api);
        $arr = json_decode($content, true);

        // var_dump($arr);


        return $arr;
    }

    //  密码
    static function mima($data)
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


        $url = " https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=" . $appid . "&secret=" . $secret;

        $neirong = self::curlGet($url);


        var_dump($neirong);
    }





    // httpsget请求
    static function curlGet($url)
    {




        $curl = curl_init(); // 启动一个CURL会话
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);              // 对认证证书来源的检查
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);              // 从证书中检查SSL加密算法是否存在
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);



        $tmpInfo = curl_exec($curl);     //返回api的json对象
        //关闭URL请求
        curl_close($curl);
        return $tmpInfo;    //返回json对象
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

    //  查询字符串中是否包含某个字符串
    static function isBaohan($zifuchuan, $zifu)
    {
        if (strpos($zifuchuan, $zifu) !== false) {
            return true;
        } else {

            return false;
        }
    }
}
