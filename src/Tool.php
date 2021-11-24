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
        echo 'this is tool test';
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
}
