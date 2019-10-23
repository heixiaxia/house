<?php


namespace app\admin\controller;
use think\Controller;
use think\Db;

class House extends Comm
{
    public function index(){
        $where=[];
        $words='';
        if(input('sou')){
            $words=input('search_name');
            $where['hname']=array('like','%'.$words.'%');
        }
        $rs=db('house')->where($where)->order('hid ')->paginate(5);
        $page=$rs->render();
        return view('index',['rs'=>$rs,'page'=>$page,'words'=>$words]);

    }
    public function add(){
        if (input('hname')){
            $hname=input('hname');
            $rs=db('house')->insert(['hname'=>$hname]);
            if($rs>0)
                $this->success('添加成功','index');
            else
                $this->error('添加失败','add');
        }else
            return view();
    }
    public function del()
    {
        if(input('hid')){
            $hid=intval(input('hid'));
            if($hid>2){
                $rs=db('house')->where(['hid'=>$hid])->delete();
                if ($rs>0)
                    $this->success('删除成功','index');
                else
                    $this->error('删除失败','index');
            }else
                $this->error('禁止删除此栋楼！','index');
        }
    }
    public function mod()
    {
        if (input('hid')){
            $hid=intval(input('hid'));
            //查询单条数据
            $rs=db('house')->where(['hid'=>$hid])->find();
            //9/echo db('house')->getLastSql();die();
            return view('mod',['rs'=>$rs]);
        }
        //修改
        if (input('hiid')){
            $hname=input('hname');
            $hid=input('hiid');
            $rs=db('house')->where(['hid'=>$hid])->update(['hname'=>$hname]);
            if ($rs>0)
                $this->success('修改成功','index');
            else
                $this->error('修改失败','index');
            return view('mod');
        }
    }
}