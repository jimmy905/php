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
}
