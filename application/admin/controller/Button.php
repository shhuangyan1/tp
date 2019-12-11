<?php
/**
 * @Author shhuangyan1@gmail.com
 * @Date 2019/12/10 10:18
 * @Version 1.0
 */

namespace app\admin\controller;


use think\Db;
use think\Request;

class Button
{
//增加按钮
    public function addButton () {
        if(Request::instance()->isPost()){
            $node_id = input('node_id');
            $name = input('name');

            $res = Db::table('button')->insert(['name'=>$name,'node_id'=>$node_id]);
            if($res){
                return json(msg(200,'','数据提交成功'));
            }else{
                return json(msg(1002,'','数据提交失败'));
            }
        }else{
            return json(msg(1001,'','数据采用post方式提交'));
        }
    }

//修改按钮
    public function modButton () {
        if(Request::instance()->isPost()){
            $id = input('id');
            $data['node_id'] = input('node_id');
            $data['name'] = input('name');

            $res = Db::table('button')->where(['id'=>$id])->update($data);
            if($res){
                return json(msg(200,'','数据提交成功'));
            }else{
                return json(msg(1002,'','数据提交失败'));
            }
        }else{
            return json(msg(1001,'','数据采用post方式提交'));
        }
    }

//删除按钮
    public function delButton () {
        if(Request::instance()->isGet()){
            $id = input('id');

            $res = Db::table('button')->delete($id);
            if($res){
                return json(msg(200,'','数据删除成功'));
            }else{
                return json(msg(1002,'','数据删除失败'));
            }
        }else{
            return json(msg(1001,'','数据采用get提交方式'));
        }
    }
}
