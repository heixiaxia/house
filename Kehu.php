<?php


namespace app\admin\controller;


class Kehu extends Comm
{
    public function index(){
        $where=[];
        $words='';
        $realwords='';
        if(input('sou')){
            $words=input('search_name');
            $realwords=input('search_realname');
            $where['kname']=array('like','%'.$words.'%');
            $where['realname']=array('like','%'.$realwords.'%');
        }
        $rs=db('kehu')->where($where)->order('kid ')->paginate(5);
        $page=$rs->render();
        return view('index',['rs'=>$rs,'page'=>$page,'words'=>$words,'realwords'=>$realwords]);

    }
    public function add(){
        if(input('sub')){
            $kname=input('kname');
            $realname=input('realname');
            $kcontact=input('kcontact');
            $wechat=input('wechat');
            $status=input('status');
            $rs=db('kehu')->insert(['kname'=>$kname,'realname'=>$realname,'kcontact'=>$kcontact,'wechat'=>$wechat,'status'=>$status]);
            if ($rs>0)
                $this->success('添加成功','index');
            else
                $this->error('添加失败','index');
        }else
            return view();
    }
    public function del(){
        if (input('kid')){
            $kid=input('kid');
            $rs=db('kehu')->where(['kid'=>$kid])->delete();
            if ($rs>0)
                $this->success('删除成功','index');
            else
                $this->error('删除失败','index');
        }
    }
    public function mod(){
        if (input('kid')){
            $kid=intval(input('kid'));
            //查询单条数据
            $rs=db('kehu')->where(['kid'=>$kid])->find();
            return view('mod',['rs'=>$rs]);
        }
        if (input('sub')){
            $kid=input('hid');
            $kname=input('kname');
            $realname=input('realname');
            $kcontact=input('kcontact');
            $wechat=input('wechat');
            $status=input('status');
            $rs=db('kehu')->where(['kid'=>$kid])->update(['kname'=>$kname,'realname'=>$realname,'kcontact'=>$kcontact,'wechat'=>$wechat,'status'=>$status]);
            if ($rs>0)
                $this->success('修改成功','index');
            else
                $this->error('修改失败','index');
        }
    }
}