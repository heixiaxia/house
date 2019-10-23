<?php


namespace app\admin\controller;
use think\db;

class Yuyue extends Comm
{
    public function index(){
        $where=[];
        $words='';
        $hname='';
        $ystatus='';
        if(input('sou')){
            $words=input('search_name');
            $hname=input('hname');
            $ystatus=input('ystatus');
            $where['hname']=array('like','%'.$hname.'%');
            $where['ystatus']=array('like','%'.$ystatus.'%');
            $where['kname']=array('like','%'.$words.'%');
        }
        $rh=\db('house')->select();
        $rs=Db::table('yuyue')
            ->alias('y')
            ->join('kehu k','k.kid=y.kid','left')
            ->join('room r','r.rid=y.rid','left')
            ->join('house h','h.hid=r.hid','left')
            ->join('user u','u.uid=y.uid','left')
            ->field('y.*,h.*,u.*,k.*,r.address')
            ->where($where)
            ->group('y.yid')
            ->paginate(3);
        //echo db('yuyue')->getLastSql();die();
        $page=$rs->render();
        return view('index',['rs'=>$rs,'rh'=>$rh,'page'=>$page,'words'=>$words,'hname'=>$hname,'ystatus'=>$ystatus]);
    }
    public function add(){
        if (input('sub')){
            $kid=input('kid');
            $rid=input('rid');
            $yutime=input('date');
            $type=input('type');
            $ystatus=input('ystatus');
            $uid=input('uid');
            $rs=db('yuyue')->insert(['kid'=>$kid,'rid'=>$rid,'uid'=>$uid,'type'=>$type,'ystatus'=>$ystatus,'yutime'=>$yutime]);
            if ($rs>0)
                $this->success('添加成功','index');
            else
                $this->error('添加失败','add');
        }else{
            $rk=db('kehu')->select();
            $rr=db('room')->select();
            return view('add',['rk'=>$rk,'rr'=>$rr]);
        }
    }
    public function mod(){
        if (input('sub')){
            $yid=input('yid');
            $kid=input('kid');
            $rid=input('rid');
            $yutime=input('date');
            $type=input('type');
            $ystatus=input('ystatus');
            $uid=input('uid');
            $rs=db('yuyue')->where(['yid'=>$yid])->update(['kid'=>$kid,'rid'=>$rid,'uid'=>$uid,'type'=>$type,'ystatus'=>$ystatus,'yutime'=>$yutime]);
            if ($rs>0)
                $this->success('修改成功','index');
            else
                $this->error('修改失败','index');
        }
        if (input('yid')){
            $yid=input('yid');
            $ry=db('yuyue')->where(['yid'=>$yid])->find();
            $rk=db('kehu')->select();
            $rr=db('room')->select();
            return view('mod',['ry'=>$ry,'rk'=>$rk,'rr'=>$rr]);
        }
    }
}