<?php
namespace app\index\controller;
use think\Controller;
use think\Db; //引用数据库
use think\Session;
class Index extends Controller
{
    public function index()
    {   
        return $this->fetch();
    }
	 public function login()
    {   
		 $username=$_POST["username"];
		 $password=MD5($_POST["password"]);
		 $result = Db::name('users')
            ->where([
           'username'   => $username,
		   'password'   => $password
            ])
            ->find();
         if($result){
			Session::set('uname',$result["username"]);
			Session::set('userid',$result["id"]);
		    $this->success('登陆成功，正在为您跳转...', '../admin/');
		 }else{
		    $this->error('账号或密码错误！');
		 }
    }
	public function abort()
    { 
	   Session::clear();
       $this->success('退出成功，正在为您跳转...', '/');
    }
}
