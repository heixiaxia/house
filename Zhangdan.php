<?php


namespace app\admin\controller;


class Zhangdan extends Comm
{
    public function index(){
        $where=[];
        $words='';
        $date='';
        if(input('sou')){
            $words=input('search_name');
            $date=input('search_date');
            $where['kname']=array('like','%'.$words.'%');
            $where['billdate']=array('like','%'.$date.'%');
        }
        $rs=db('bill')
            ->alias('b')
            ->join('kehu k','k.kid=b.kid','left')
            ->field('b.*,k.kname')
            ->where($where)
            ->group('bid ')
            ->paginate(5);
        $page=$rs->render();
        return view('index',['rs'=>$rs,'page'=>$page,'words'=>$words,'date'=>$date]);
    }
    public function add(){
        if (input('sub')){
            $kid=input('zid');
            $rid=input('rid');
            $water=input('shui');
            $dian=input('dian');
            $net=input('wang');
            $zujin=input('zujin');
            $billdate=input('billdate');
            $moneysum=input('sum');
            $rs=db('bill')->insert(['water'=>$water,'dian'=>$dian,'net'=>$net,'zujin'=>$zujin,'moneysum'=>$moneysum,
                'kid'=>$kid,'billdate'=>$billdate,'rid'=>$rid]);
            if ($rs>0)
                $this->success('添加成功','index');
            else
                $this->error('添加失败','add');
        }else{
            $rs=db('zufang')
                ->alias('z')
                ->join('room r','z.rid=r.rid','left')
                ->join('kehu k','k.kid=z.kid','left')
                ->field('z.*,r.*,k.*')
                ->group('z.zid')
                ->select();
            //print_r($rs);die();
            $rw=db('room')->select();
            return view('add',['rs'=>$rs,'rw'=>$rw]);
        }
    }
    public function del(){
        if(input('bid')){
            $bid=input('bid');
            $rs=db('bill')->where(['bid'=>$bid])->delete();
            if ($rs>0)
                $this->success('删除成功','index');
            else
                $this->error('删除失败','index');
        }
    }
    public function liandong()
    {
        $rid=input('rid');
        $data=db('zufang')
            ->alias('z')
            ->join('kehu k','k.kid=z.kid','right')
            ->where('rid',$rid)
            ->select();
        $str="<option value=''>请选择租户</option>";
        foreach ($data as $hu){
            $str.="<option value=".$hu['kid'].">".$hu['kname']."</option>";
        }
        echo $str;
    }
    public function mod(){
        if (input('sub')){
            $bid=input('bid');
            $kid=input('zid');
            $rid=input('rid');
            $water=input('shui');
            $dian=input('dian');
            $net=input('wang');
            $zujin=input('zujin');
            $billdate=input('billdate');
            $moneysum=input('sum');
            $rs=db('bill')->where(['bid'=>$bid])->update(['water'=>$water,'dian'=>$dian,'net'=>$net,'zujin'=>$zujin,'moneysum'=>$moneysum,
                'kid'=>$kid,'billdate'=>$billdate,'rid'=>$rid]);
            if ($rs>0)
                $this->success('修改成功','index');
            else
                $this->error('修改失败','index');
        }
        if (input('bid')){
            $bid=input('bid');
            $rb=db('bill')->where(['bid'=>$bid])->find();
            $rw=db('room')->select();
            $rs=db('zufang')
                ->alias('z')
                ->join('room r','z.rid=r.rid','left')
                ->join('kehu k','k.kid=z.kid','left')
                ->field('z.*,r.*,k.*')
                ->group('z.zid')
                ->select();
            //print_r($rs);die();
            return view('mod',['rs'=>$rs,'rw'=>$rw,'rb'=>$rb]);
        }
    }
}