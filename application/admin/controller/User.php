<?php
/**
 * @Author shhuangyan1@gmail.com
 * @Date 2019/12/10 10:17
 * @Version 1.0
 */

namespace app\admin\controller;


use think\Db;
use think\Request;

class User
{
//添加后台用户
    public function addUser () {
        if(Request::instance()->isPost()){
            $username = input('username');
            $password = md5(input('password'));
            $phone = input('phone');
            $create_time = date('Y-m-d H:i:s',time());
            $res = Db::table('user')->insert(['username'=>$username,'password'=>$password,'phone'=>$phone,'create_time'=>$create_time]);

            if($res){
                return json(msg(200,'','数据提交成功'));
            }else{
                return json(msg(400,'','数据提交失败'));
            }
        }else{
            return json(msg(1001,'','数据采用post提交方式'));
        }
    }
}
