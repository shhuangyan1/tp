<?php
/**
 * @Author shhuangyan1@gmail.com
 * @Date 2019/12/9 17:37
 * @Version 1.0
 */

namespace app\admin\controller;


use think\Db;
use think\Request;

class Node
{
//添加节点
    public function addNode () {
        if(Request::instance()->isPost()){
            $data['name'] = input('name');
            $data['parent_id'] = input('parent_id')?input('parent_id'):0;
            $data['order_id'] = input('order_id');

            $res = Db::table('node')->insert($data);
            if($res){
                return json(msg(200,'','数据提交成功'));
            }else{
                return json(msg(1002,'','数据提交失败'));
            }
        }else{
            return json(msg(1001,'','数据采用post提交方式'));
        }
    }

//修改节点信息
    public function modNode () {
        if(Request::instance()->isPost()){
            $id = input('id');
            $data['name'] = input('name');
            $data['parent_id'] = input('parent_id')?input('parent_id'):0;
            $data['order_id'] = input('order_id');

            $res = Db::table('node')->where(['id'=>$id])->update($data);
            if($res){
                return json(msg(200,'','数据修改成功'));
            }else{
                return json(msg(1002,'','数据修改失败'));
            }
        }else{
            return json(msg(1001,'','数据采用post提交方式'));
        }
    }

 //删除节点信息
    public function delNode () {
        if(Request::instance()->isGet()){
            $id = input('id');

            $res = Db::table('node')->delete($id);
            if($res){
                return json(msg(200,'','数据删除成功'));
            }else{
                return json(msg(1002,'','数据删除失败'));
            }
        }else{
            return json(msg(1001,'','数据采用get提交方式'));
        }
    }

//节点列表
    public function nodeList () {
        session('role_id',2);
        if(session('role_id') == 1){//超级管理员
            $info = Db::table('node')->select();
        }else{//其他权限组
            $nodes = Db::table('role')->where(['id'=>session('role_id')])->value('nodes');
            $info = Db::table('node')->where(['id'=>['in',$nodes]])->select();
        }
        $res = getTree($info,false);
        return json(msg(200,$res,'success'));
    }
}
