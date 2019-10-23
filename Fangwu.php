<?php


namespace app\admin\controller;
use think\Db;

class Fangwu extends Comm
{
    public function index(){
        $rs=Db::table('room')
            ->alias('r')
            ->join('house h','h.hid=r.hid')
            ->join('huxing hu','r.huid=hu.huid')
            ->join('pay p','p.payid=r.payid')
            ->join('user u','u.uid=r.uid')
            ->field('r.*,h.*,hu.*,p.*,u.*')
            ->group('r.rid')
            ->paginate(3);
        //echo \db('room')->getLastSql();die();
        $page=$rs->render();
        $words='';
        $realwords='';
        return view('index',['rs'=>$rs,'page'=>$page,'words'=>$words,'realwords'=>$realwords]);
    }
    public function add(){
        if (input('sub')){
            $hid=input('hid');
            $uid=input('uid');
            $huid=input('huid');
            $payid=input('payid');
            $address=input('address');
            $mianji=input('mianji');
            $zujin=input('zujin');
            $yajin=input('yajin');
            $jiaju=input('jiaju');
            $jiadian=input('jiadian');
            $fangcall=input('fangcall');
            $rs=db('room')->insert(['hid'=>$hid,'huid'=>$huid,'address'=>$address,
                'mianji'=>$mianji,'payid'=>$payid,'zujin'=>$zujin,'yajin'=>$yajin,
                'jiaju'=>$jiaju,'jiadian'=>$jiadian,'fangcall'=>$fangcall,'uid'=>$uid,'rstatus'=>'1']);
            if ($rs>0)
                $this->success('添加成功','index');
            else
                $this->error('添加失败','add');
        }else{
            $rl=db('house')->select();
            $rh=db('huxing')->select();
            $rz=db('pay')->select();
            return view('add',['rl'=>$rl,'rh'=>$rh,'rz'=>$rz]);
        }
    }
    public function del(){
        if (input('rid')){
            $rid=input('rid');
            $rs=db('room')->where(['rid'=>$rid])->delete();
            if ($rs>0)
                $this->success('删除成功','index');
            else
                $this->error('删除失败','index');
        }
    }
    public function mod(){
        if (input('sub')){
            $rid=input('hiid');
            $hid=input('hid');
            $uid=input('uid');
            $huid=input('huid');
            $address=input('address');
            $payid=input('payid');
            $zujin=input('zujin');
            $mianji=input('mianji');
            $yajin=input('yajin');
            $jiaju=input('jiaju');
            $jiadian=input('jiadian');
            $fangcall=input('fangcall');
            $rs=db('room')->where(['rid'=>$rid])
                ->update(['hid'=>$hid,'huid'=>$huid,'address'=>$address,'mianji'=>$mianji,
                    'payid'=>$payid,'zujin'=>$zujin,'yajin'=>$yajin,'jiaju'=>$jiaju,'jiadian'=>$jiadian,
                    'fangcall'=>$fangcall,'rstatus'=>'1','uid'=>$uid]);
            if ($rs>0)
                $this->success('修改成功','index');
            else
                $this->error('修改失败','index');
        }else{
            $rid=intval(input('rid'));
            $rs=db('room')->where(['rid'=>$rid])->find();
            $rl=db('house')->select();
            $rh=db('huxing')->select();
            $rp=db('pay')->select();
            return view('mod',['rs'=>$rs,'rl'=>$rl,'rh'=>$rh,'rp'=>$rp]);
        }
    }
}