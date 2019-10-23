<?php
namespace app\admin\controller;

use think\Controller;
use think\Session;
class Index extends Controller
{
    public function index()
    {
        //登录判定
        doLogin();
        return view('index');
    }
    public function login()
    {

        if(input('sub')){
            $code=input('code');
            $captcha=new \think\captcha\Captcha();
            if (!$captcha->check($code)){
                $this->error('验证码错误');
            }else{
                $uname=input('uname');
                $upwd=sha1(input('upwd'));
                $rs=db('user')->where(['uname'=>$uname,'upwd'=>$upwd])->find();
                if($rs['uid']){
                    //	创建session
                    Session::set('uid',$rs['uid']);
                    Session::set('uname',$rs['uname']);
                    $this->success('登录成功','index');
                }else
                    $this->success('登录失败,请重新登录','login');
            }
        }else
            return view();
    }
    public function logOut(){
        Session::clear();
        return view('login');
    }
}
