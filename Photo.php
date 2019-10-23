<?php


namespace app\admin\controller;


class Photo extends Comm
{
    public function index(){
        $where=[];
        $words='';
        if(input('sou')){
            $words=input('search_name');
            $where['pname']=array('like','%'.$words.'%');
        }
        $rs=db('photo')->where($where)->order('pid ')->paginate(5);
        $page=$rs->render();
        return view('index',['rs'=>$rs,'page'=>$page,'words'=>$words]);
    }
    public function add(){
        if(input('sub')){
            $pname=input('pname');
            $jieshao=input('jieshao');
            $pstatus=input('pstatus');
            if(request()->file('img')){
                //获取表单上传文件
                $file=request()->file('img');
                //移动到框架应用根目录/public/static/images/ 目录下
                //dump($error = $_FILES['file']['error']);
                $info=$file->rule('time')->move(ROOT_PATH.'public'.DS.'static'.DS.'images');
                if ($info){
                    $newname=time().'.'.$info->getExtension();
                    $rs=db('photo')->insert(['pname'=>$pname,'pimg'=>$newname,'jieshao'=>$jieshao,'pstatus'=>$pstatus]);
                    //echo db('photo')->getLastSql();die();
                    if($rs>0)
                        $this->success('添加成功','index');
                    else
                        $this->error('添加失败','add');
                }else{
                    echo $info->getError();
                    $this->error('请重新添加','add');
                }
            }else{
                $rs=db('photo')->insert(['pname'=>$pname,'pimg'=>'1.jpg','jieshao'=>$jieshao,'pstatus'=>$pstatus]);
                //echo db('photo')->getLastSql();die();
                if($rs>0)
                    $this->success('添加成功','index');
                else
                    $this->error('添加失败','add');
            }
        }else
            return view();
    }
    public function del(){
        if(input('pid')){
            $pid=intval(input('pid'));
            $rs=db('photo')->where(['pid'=>$pid])->delete();
            if ($rs>0)
                $this->success('删除成功','index');
            else
                $this->error('删除失败','index');
        }
    }
    public function mod(){
        if (input('sub')){
            $pid=input('hid');
            $pname=input('pname');
            $jieshao=input('jieshao');
            $pstatus=input('pstatus');
            if(request()->file('img')){
                //获取表单上传文件
                $file=request()->file('img');
                //移动到框架应用根目录/public/static/images/ 目录下
                //dump($error = $_FILES['file']['error']);
                $info=$file->rule('time')->move(ROOT_PATH.'public'.DS.'static'.DS.'images');
                if ($info){
                    $newname=time().'.'.$info->getExtension();
                    $rs=db('photo')->where(['pid'=>$pid])->update(['pname'=>$pname,'pimg'=>$newname,'jieshao'=>$jieshao,'pstatus'=>$pstatus]);
                    //echo db('photo')->getLastSql();die();
                    if($rs>0)
                        $this->success('修改成功','index');
                    else
                        $this->error('修改失败','index');
                }else{
                    echo $info->getError();
                    $this->error('请重新修改','index');
                }
            }else{
                $rs=db('photo')->where(['pid'=>$pid])->update(['pname'=>$pname,'jieshao'=>$jieshao,'pstatus'=>$pstatus]);
                //echo db('photo')->getLastSql();die();
                if($rs>0)
                    $this->success('修改成功','index');
                else
                    $this->error('修改失败','index');
            }
        }else{
            $pid=input('pid');
            $rs=db('photo')->where(['pid'=>$pid])->find();
            return view('mod',['rs'=>$rs]);
        }
    }
}