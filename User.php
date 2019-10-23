<?php


namespace app\admin\controller;


class User extends Comm
{
    public function index(){
        //显示
        $where=[];
        $words='';
        if(input('sou')){
            $words=input('search_name');
            $where['uname']=array('like','%'.$words.'%');
        }
        $rs=db('user')->where($where)->order('uid ')->paginate(5);
        $page=$rs->render();
        return view('index',['rs'=>$rs,'page'=>$page,'words'=>$words]);
    }
    public function del()
    {
        if(input('uid')){
            $uid=intval(input('uid'));
            if ($uid!=1){
                $rs=db('user')->where(['uid'=>$uid])->delete();
                if ($rs>0)
                    $this->success('删除成功','index');
                else
                    $this->error('删除失败','index');
            }else
                $this->error('此管理员禁止删除!','index');
        }
    }
    //增加
    public function add()
    {
        if (input('uname')){
            $uname=input('uname');
            $pwd=sha1(input('pwd'));
            $upwd=sha1(input('upwd'));
            $power=input('power');
            $ucontact=input('ucontact');
            if ($pwd==$upwd){
                //添加数据
                $rs=db('user')->insert(['uname'=>$uname,'upwd'=>$pwd,'ucontact'=>$ucontact]);
                if ($rs>0)
                    $this->success('添加成功','index');
                else
                    $this->error('添加失败','add');

            }else
                $this->error('密码不一致，请重新输入','add');
        }else
            return view();
    }
    //修改
    public function mod()
    {
        if (input('uid')){
            $uid=intval(input('uid'));
            //查询单条数据
            $rs=db('user')->where(['uid'=>$uid])->find();
            return view('mod',['rs'=>$rs]);
        }
        //修改
        if (input('sub')){
            $uname=input('uname');
            $hid=input('hid');
            $ypwd=sha1(input('ypwd'));
            $num=db('user')->where(['uid'=>$hid,'upwd'=>$ypwd])->count();
            if ($num<1)
                $this->error('原密码不正确！请重新输入！','index');
            else{
                $pwd=sha1(input('pwd'));
                $upwd=sha1(input('upwd'));
                $ucontact=input('ucontact');
                $power=input('power');
                if ($pwd==$upwd){
                    $rs=db('user')->where(['uid'=>$hid])->update(['uname'=>$uname,'upwd'=>$upwd,'upower'=>$power,'ucontact'=>$ucontact]);
                    if ($rs>0)
                        $this->success('修改成功','index');
                    else
                        $this->error('修改失败','index');
                }else
                    $this->error('两次密码输入不一致，请重新输入！','index');
            }
            return view('mod');

        }
    }
}