<?php
namespace app\admin\controller;
use think\Controller;
use think\Db; //引用数据库

class Index extends Controller
{
    public function index()
    {   
        return $this->fetch();
    }
	public function top()
    {   
		//return 'Hello,World！';
        return $this->fetch();
    }
	public function left()
    {   
        return $this->fetch();
    }
	public function hy_list()
    {   
        return $this->fetch();
    }
}
