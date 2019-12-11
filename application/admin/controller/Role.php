<?php
/**
 * @Author shhuangyan1@gmail.com
 * @Date 2019/12/9 17:37
 * @Version 1.0
 */

namespace app\admin\controller;


use think\Db;
use think\Request;

class Role
{
//增加角色
    public function addRole () {
        if(Request::instance()->isPost()){
            $name = input('name');
            $nodes = input('nodes');
            $status = input('status')?input('status'):1;

            $res = Db::table('role')->insert(['name'=>$name,'nodes'=>$nodes,'status'=>$status]);
            if($res){
                return json(msg(200,'','数据插入成功'));
            }else{
                return json(msg(1002,'','数据插入失败'));
            }
        }else{
            return json(msg(1001,'','数据采用post提交方式'));
        }
    }

//修改角色
    public function modRole () {
        if(Request::instance()->isPost()){
            $id = input('id');
            $data['name'] = input('name');
            $data['nodes'] = input('nodes');
            $data['status'] = input['status'];

            $res = Db::table('role')->where(['id'=>$id])->update($data);
            if($res){
                return json(msg(200,'','数据修改成功'));
            }else{
                return json(msg(1002,'','数据修改失败'));
            }
        }else{
            return json(msg(1001,'','数据采用post提交方式'));
        }
    }

//删除角色
    public function delRole () {
        if(Request::instance()->isGet()){
            $id = input('id');

            $res = Db::table('role')->delete($id);
            if($res){
                return json(msg(200,'','删除角色成功'));
            }else{
                return json(msg(1002,'','删除角色失败'));
            }
        }else{
            return json(msg(1001,'','数据采用get提交方式'));
        }
    }

//角色列表
    public function roleList () {
        if(Request::instance()->isGet()){
            $page = input('page')?input('page'):1;
            $pageSize = input('pageSize')?input('pageSize'):10;
            $res['list'] = Db::table('role')->page($page,$pageSize)->select();

            if($res['list']){
                $res['count'] = Db::table('role')->count();
                return json(msg(200,$res,'数据列表'));
            }else{
                return json(msg(1003,'','暂无数据'));
            }
        }else{
            return json(msg(1001,'','数据采用get提交方式'));
        }
    }
}
