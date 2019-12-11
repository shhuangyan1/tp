<?php
/**
 * @Author shhuangyan1@gmail.com
 * @Date 2019/12/9 16:51
 * @Version 1.0
 */

namespace app\admin\controller;


use org\Verify;
use think\Request;
use think\Session;

class Login
{
    // 验证码
    public function checkVerify()
    {
        $verify = new Verify();
        $verify->imageH = 32;
        $verify->imageW = 100;
        $verify->length = 4;
        $verify->useNoise = false;
        $verify->fontSize = 14;
        return $verify->entry();
    }

    //检查用户登录态
    public function checkLogin () {
        if(Request::instance()->isPost()){
            $username = input('username');
            $password = input('password');
            $code = input('code');

            $result = $this->validate(compact('username', 'password', "code"), 'UserValidate');
            if(true !== $result){
                return json(msg(1002, '', $result));
            }

            $verify = new Verify();
             if (!$verify->check($code)) {
                 return json(msg(1003, '', '验证码错误'));
             }

             $user  = new UserModel();
             $hasUser = $user->hasUser();
             if(empty($hasUser)){
                 return json(msg(1004,'','账号或密码错误'));
             }
             if($hasUser['status']==2){
                 return json(msg(1005,'','用户登录态失效'));
             }

             //记录session信息并传给前端
             session('username', $hasUser['user_name']);
             session('id', $hasUser['id']);

             return json(msg(200,'','用户登录成功'));

        }else{
            return json(msg(1001,'','该接口支持post请求'));
        }
    }

    //用户退出登录
    public function loginOut () {
        Session::clear();
        return json(msg(200,'','退出登录成功'));
    }

}
