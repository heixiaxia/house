<?php


namespace app\admin\controller;
use think\Db;

class Fangzuke extends Comm
{
    public function index(){
        $where=[];
        $words='';
        $realwords='';
        $hname='';
        $zstatus='';
        if(input('sou')){
            $words=input('search_name');
            $realwords=input('search_realname');
            $hname=input('hname');
            $zstatus=input('zstatus');
            $where['hname']=array('like','%'.$hname.'%');
            $where['zstatus']=array('like','%'.$zstatus.'%');
            $where['kname']=array('like','%'.$words.'%');
            $where['realname']=array('like','%'.$realwords.'%');
        }
        $rh=\db('house')->select();
        $rs=Db::table('zufang')
            ->alias('z')
            ->join('house h','h.hid=z.hid','left')
            ->join('room r','r.rid=z.rid','left')
            ->join('kehu k','k.kid=z.kid','left')
            ->join('pay p','p.payid=z.payid','left')
            ->join('user u','u.uid=z.uid','left')
            ->field('z.*,h.*,p.*,u.*,k.*,r.address')
            ->where($where)
            ->group('z.zid')
            ->paginate(3);
        //echo db('zufang')->getLastSql();die();
        $page=$rs->render();
        return view('index',['rs'=>$rs,'rh'=>$rh,'page'=>$page,'words'=>$words,'realwords'=>$realwords,'hname'=>$hname,'zstatus'=>$zstatus]);
    }
    public function add(){
        if (input('sub')){
            $hid=input('hid');
            $uid=input('uid');
            $rid=input('rid');
            $kid=input('kid');
            $payid=input('payid');
            $starttime=input('starttime');
            $endtime=input('endtime');
            $rs=db('zufang')->insert(['hid'=>$hid,'rid'=>$rid,'payid'=>$payid,'kid'=>$kid,
                'starttime'=>$starttime,'endtime'=>$endtime,'uid'=>$uid,'zstatus'=>1]);
            if ($rs>0)
                $this->success('添加成功','index');
            else
                $this->error('添加失败','add');
        }else{
            $rl=db('house')->select();
            $rr=db('room')->select();
            $rz=db('pay')->select();
            $rk=db('kehu')->select();
            return view('add',['rl'=>$rl,'rr'=>$rr,'rz'=>$rz,'rk'=>$rk]);
        }
    }
    public function mod(){
        if (input('sub')){
            $zid=input('zid');
            $hid=input('hid');
            $uid=input('uid');
            $rid=input('rid');
            $kid=input('kid');
            $payid=input('payid');
            $starttime=input('starttime');
            $endtime=input('endtime');
            $rs=db('zufang')->where(['zid'=>$zid])->update(['hid'=>$hid,'rid'=>$rid,'payid'=>$payid,'kid'=>$kid,
                'starttime'=>$starttime,'endtime'=>$endtime,'uid'=>$uid]);
            if ($rs>0)
                $this->success('修改成功','index');
            else
                $this->error('修改失败','index');
        }
        if (input('zid')){
            $zid=input('zid');
            $zu=db('zufang')->where(['zid'=>$zid])->find();
            $rl=db('house')->select();
            $rr=db('room')->select();
            $rz=db('pay')->select();
            $rk=db('kehu')->select();
            return view('mod',['zu'=>$zu,'rl'=>$rl,'rr'=>$rr,'rz'=>$rz,'rk'=>$rk]);
        }
    }
    public  function del(){
        if (input('zid')){
            $zid=input('zid');
            $rs=db('zufang')->where(['zid'=>$zid])->delete();
            if ($rs>0)
                $this->success('删除成功','index');
            else
                $this->error('删除失败','index');
        }
    }
}