<?php
namespace app\admin\controller;
use think\Controller;
class Comm extends Controller
{
    public function _initialize(){
        doLogin();
    }
}
?>