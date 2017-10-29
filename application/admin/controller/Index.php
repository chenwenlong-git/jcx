<?php
namespace app\admin\controller;
use think\Controller;
use think\Db; //引用数据库
use think\Paginator; //引用分页
use app\admin\model\Order as OrderModel; //引用这个模型
use app\admin\model\Orderinfo as OrderinfoModel; //引用这个模型
use think\Session;

class Index extends Controller
{
    public function index()
    {   
		if(!Session::has('uname')){ $this->error('请您重新登录！','/index'); }
        return $this->fetch();
    }
	public function top()
    {   
        return $this->fetch();
    }
	public function left()
    {   
        return $this->fetch();
    }
	public function tcxt()
    {   
        return $this->fetch();
    }
	public function imgshow()
    { 
		$id=$_GET["id"];
		$orderdata = Db::name('orderdata') ->where(['Id'=> $id])->find();
		$dataarr=json_decode($orderdata["UploadImg"]);
		$this->assign('data', $dataarr);
        return $this->fetch();
    }
	public function chartshow()
    {   
        if(!Session::has('uname')){ $this->error('请您重新登录！','/index'); }
	    $cid=$_GET["cid"];
		$orderdata = Db::name('orderdata') ->where(['Id'=> $cid])->find();
		$this->assign('cid', $cid);
		$this->assign('OrderNum', $orderdata['OrderNum']);
        return $this->fetch();
    }
	public function shiplist()
    {   
	  if(!Session::has('uname')){ $this->error('请您重新登录！','/index'); }
	    $id=$_GET["id"];
		$this->assign('id', $id);
        return $this->fetch();
    }
	public function returnlist()
    {   
	  if(!Session::has('uname')){ $this->error('请您重新登录！','/index'); }
	    $id=$_GET["id"];
		$this->assign('id', $id);
        return $this->fetch();
    }
	public function ShipDataList()
    {   
	  	header('Content-Type:application/json; charset=utf-8');
		 $page=$_GET['page'];
		 $id=$_GET['id'];
		 $starttime=$_GET['starttime'];
		 $stoptime=$_GET['stoptime'];
		 if(!empty($starttime) || !empty($stoptime)){
		  $DataTime=" and DataTime >='".$starttime."' and DataTime <='".$stoptime."'";
		 }else{
		  $DataTime='';
		 }
		 if(!empty($id)){
		  $OrderId=" and OrderId  =".$id." ";
		 }else{
		  $OrderId='';
		 }
         $result = Db::query('select * from think_orderlist where Type=0 '.$DataTime.$OrderId.' order by DataTime desc');
         $pagepar = 10;
		 $rel[0]['num']=count($result);
         $rel[1]['sum'] = ceil($rel[0]['num'] / $pagepar);
         $curren = isset($_GET['page']) ? $_GET['page'] : 1;
         $lim = (($curren - 1) * $pagepar) . "," . $pagepar;
         if ($curren == $rel[1]['sum']) {
            $end = $rel[0]['num'] - (($curren - 1) * $pagepar);
            $lim = (($curren - 1) * $pagepar) . "," . $end;
         }

		 $result = Db::query('select * from think_orderlist where Type=0 '.$DataTime.$OrderId.' order by DataTime desc limit ' . $lim);
		 $info = Db::name('orderdata')->where('Id',$id)->select();
		 foreach ($result as $k => $v) {
		    $result[$k]['OrderNum']=$info[0]['OrderNum'];
			$result[$k]['ClientName']=$info[0]['ClientName'];
			$result[$k]['cid']=$id;
		  }
		 if($result){
		  $arr=array('code'=>1,'message'=>$rel,'hint'=>'查询成功！','data'=>$result);
		 }else{
		  $arr=array('code'=>1,'message'=>$rel,'hint'=>'查询失败！','data'=>$result);
		 }
		 echo json_encode($arr);
		 
    }
	public function ReturnDataList()
    {   
	  	header('Content-Type:application/json; charset=utf-8');
		 $page=$_GET['page'];
		 $id=$_GET['id'];
		 $starttime=$_GET['starttime'];
		 $stoptime=$_GET['stoptime'];
		 if(!empty($starttime) || !empty($stoptime)){
		  $DataTime=" and DataTime >='".$starttime."' and DataTime <='".$stoptime."'";
		 }else{
		  $DataTime='';
		 }
		 if(!empty($id)){
		  $OrderId=" and OrderId  =".$id." ";
		 }else{
		  $OrderId='';
		 }
         $result = Db::query('select * from think_orderlist where Type=1 '.$DataTime.$OrderId.' order by DataTime desc');
         $pagepar = 10;
		 $rel[0]['num']=count($result);
         $rel[1]['sum'] = ceil($rel[0]['num'] / $pagepar);
         $curren = isset($_GET['page']) ? $_GET['page'] : 1;
         $lim = (($curren - 1) * $pagepar) . "," . $pagepar;
         if ($curren == $rel[1]['sum']) {
            $end = $rel[0]['num'] - (($curren - 1) * $pagepar);
            $lim = (($curren - 1) * $pagepar) . "," . $end;
         }

		 $result = Db::query('select * from think_orderlist where Type=1 '.$DataTime.$OrderId.' order by DataTime desc limit ' . $lim);
		 $info = Db::name('orderdata')->where('Id',$id)->select();
		 foreach ($result as $k => $v) {
		    $result[$k]['OrderNum']=$info[0]['OrderNum'];
			$result[$k]['ClientName']=$info[0]['ClientName'];
			$result[$k]['cid']=$id;
		  }
		 if($result){
		  $arr=array('code'=>1,'message'=>$rel,'hint'=>'查询成功！','data'=>$result);
		 }else{
		  $arr=array('code'=>1,'message'=>$rel,'hint'=>'查询失败！','data'=>$result);
		 }
		 echo json_encode($arr);
		 
    }
	public function orderinfodata()
    {   
	  if(!Session::has('uname')){ $this->error('请您重新登录！','/index'); }
		if(isset($_GET["id"])){ 
	      $id=$_GET["id"];
		  $result = Db::name('orderdata')->where('Id',$id)->select();
		}else{
		  $result=array();
		}
		$this->assign('orderdata', $result);
        return $this->fetch();
    }
	public function ChartDataShow()
    {  
	  header('Content-Type:application/json; charset=utf-8');
	  if(!Session::has('uname')){ $this->error('请您重新登录！','/index'); }
		if(isset($_GET["cid"])){ 
	      $cid=$_GET["cid"];
		  $result = Db::name('orderlist')->where('OrderId',$cid)->order('DataTime asc')->select();
		}else{
		  $result=array();
		}
		if($result){
		  $arr=array('code'=>1,'hint'=>'查询成功！','data'=>$result);
		 }else{
		  $arr=array('code'=>1,'hint'=>'查询失败！','data'=>$result);
		 }
		 echo json_encode($arr);
    }
	public function hy_list()
    {    
		if(!Session::has('uname')){ $this->error('请您重新登录！','/index'); }
		if(isset($_GET["search"])){ 
			$search=$_GET["search"];
		    $resultnum = Db::name('orderlist')->where('OrderNum','like','%'.$search.'%')->select();
		    $result = Db::name('orderlist')->where('OrderNum','like','%'.$search.'%')->order('Id desc')->paginate(10);
			$this->assign('orderdata', $search);
		}else{
		  $search="";
		  $resultnum = Db::name('orderlist')->select();
		  $result = Db::name('orderlist')->order('Id desc')->paginate(10);
		  $this->assign('orderdata', $search);
		}
		 
		 $this->assign('orderlist', $result);
		 $this->assign('ordernum', count($resultnum));
        return $this->fetch();
    }
    public function OrderList()
	{
		 header('Content-Type:application/json; charset=utf-8');
		 $page=$_GET['page'];
		 $OrderNum=trim($_GET['OrderNum']);
		 $ClientName=trim($_GET['ClientName']);
		 $starttime=trim($_GET['starttime']);
		 $stoptime=trim($_GET['stoptime']);
		 if(!empty($OrderNum)){
		  $OrderNum=" and OrderNum like '%".$OrderNum."%' ";
		 }else{
		  $OrderNum='';
		 }
		 if(!empty($ClientName)){
		  $ClientName=" and ClientName like '%".$ClientName."%' ";
		 }else{
		  $ClientName='';
		 }
		 if(!empty($starttime) || !empty($stoptime)){
		  $OrderTime=" and OrderTime >='".$starttime."' and OrderTime <='".$stoptime."'";
		 }else{
		  $OrderTime='';
		 }
         $result = Db::query('select * from think_orderdata where 1=1 '.$OrderNum.$ClientName.$OrderTime.' order by OrderTime desc');

         $pagepar = 10;
		 $rel[0]['num']=count($result);
         $rel[1]['sum'] = ceil($rel[0]['num'] / $pagepar);
         $curren = isset($_GET['page']) ? $_GET['page'] : 1;
         $lim = (($curren - 1) * $pagepar) . "," . $pagepar;
         if ($curren == $rel[1]['sum']) {
            $end = $rel[0]['num'] - (($curren - 1) * $pagepar);
            $lim = (($curren - 1) * $pagepar) . "," . $end;
         }

		 $result = Db::query('select * from think_orderdata where 1=1 '.$OrderNum.$ClientName.$OrderTime.' order by OrderTime desc limit ' . $lim);
		
		 if($result){
		  $arr=array('code'=>1,'message'=>$rel,'hint'=>'查询成功！','data'=>$result);
		 }else{
		  $arr=array('code'=>1,'message'=>$rel,'hint'=>'查询失败！','data'=>$result);
		 }
		 echo json_encode($arr);
	}
	public function OrderStatistics()
	{
		 header('Content-Type:application/json; charset=utf-8');
		 $page=$_GET['page'];
		 $OrderNum= trim($_GET['OrderNum']);
		 $ClientName=trim($_GET['ClientName']);
		 $starttime=trim($_GET['starttime']);
		 $stoptime=trim($_GET['stoptime']);
		 $startnot=trim($_GET['startnot']);
		 $stopnot=trim($_GET['stopnot']);
		 if(!empty($OrderNum)){
		  $OrderNum=" and OrderNum like '%".$OrderNum."%' ";
		 }else{
		  $OrderNum='';
		 }
		 if(!empty($ClientName)){
		  $ClientName=" and ClientName like '%".$ClientName."%' ";
		 }else{
		  $ClientName='';
		 }
		 if(!empty($starttime) || $stoptime){
		  $OrderTime=" and OrderTime >='".$starttime."' and OrderTime <='".$stoptime."'";
		 }else{
		  $OrderTime='';
		 }
         $result = Db::query('select * from think_orderdata where 1=1 '.$OrderNum.$ClientName.$OrderTime.' order by OrderTime desc');

         $pagepar = 10;
		 $rel[0]['num']=count($result);
         $rel[1]['sum'] = ceil($rel[0]['num'] / $pagepar);
         $curren = isset($_GET['page']) ? $_GET['page'] : 1;
         $lim = (($curren - 1) * $pagepar) . "," . $pagepar;
         if ($curren == $rel[1]['sum']) {
            $end = $rel[0]['num'] - (($curren - 1) * $pagepar);
            $lim = (($curren - 1) * $pagepar) . "," . $end;
         }

		 $result = Db::query('select * from think_orderdata where 1=1 '.$OrderNum.$ClientName.$OrderTime.' order by OrderTime desc limit ' . $lim);

		 foreach ($result as $k => $v) {
            $data = Db::query('select sum(NumData) as num from think_orderlist where OrderId='.$result[$k]['Id'].' and Type=0 '); //交货统计
			$data1 = Db::query('select sum(NumData) as num from think_orderlist where OrderId='.$result[$k]['Id'].' and Type=1 '); //退货统计
            $result[$k]['ShipNumData']=$data[0]['num'];
            $result[$k]['ReturnNumData']=$data1[0]['num'];
			$notnum=($result[$k]['NumData']+$result[$k]['ReturnNumData'])-$result[$k]['ShipNumData'];
			if($startnot!="" && $stopnot!=""){
				if($notnum>=$startnot && $notnum<=$stopnot){
				}else{
				 unset($result[$k]);
				}
			}
		 }
		
		 if($result){
		  $arr=array('code'=>1,'message'=>$rel,'hint'=>'查询成功！','data'=>$result);
		 }else{
		  $arr=array('code'=>1,'message'=>$rel,'hint'=>'查询失败！','data'=>$result);
		 }
		 echo json_encode($arr);
	}
	public function orderinfo()
    {    
		if(!Session::has('uname')){ $this->error('请您重新登录！','/index'); }
		if(isset($_GET["search"]) || isset($_GET["Status"])){ 
			if(isset($_GET["search"])){
				$search=$_GET["search"];
				$resultnum = Db::name('orderdata')->where('OrderNum','like','%'.$search.'%')->select();
				$result = Db::name('orderdata')->where('OrderNum','like','%'.$search.'%')->order('Id desc')->paginate(10);
				$this->assign('Status',3);
				$this->assign('orderdata', $search);
			}
			if(isset($_GET["Status"])){
				$search=$_GET["Status"];
				if($search!=0){
                    $search=$search-1;
					$resultnum = Db::name('orderdata')->where('Status',$search)->select();
					$result = Db::name('orderdata')->where('Status',$search)->order('Id desc')->paginate(10);
					$this->assign('Status',$search);
				}
			}
			if(isset($_GET["search"]) && isset($_GET["Status"])){
				if($_GET["Status"]==0){
					$search=$_GET["search"];
					$Status=$_GET["Status"];
					$Status=$Status-1;
					$resultnum = Db::name('orderdata')->where('OrderNum','like','%'.$search.'%')->select();
					$result = Db::name('orderdata')->where('OrderNum','like','%'.$search.'%')->order('Id desc')->paginate(10);
					$this->assign('Status',3);
					$this->assign('orderdata', $search);
				}else{
				   	$search=$_GET["search"];
					$Status=$_GET["Status"];
					$Status=$Status-1;
					$resultnum = Db::name('orderdata')->where('Status',$Status)->where('OrderNum','like','%'.$search.'%')->select();
					$result = Db::name('orderdata')->where('Status',$Status)->where('OrderNum','like','%'.$search.'%')->order('Id desc')->paginate(10);
					$this->assign('Status',$Status);
					$this->assign('orderdata', $search);
				}
			}
		}else{
		  $search="";
		  $resultnum = Db::name('orderdata')->select();
		  $result = Db::name('orderdata')->order('Id desc')->paginate(10);
		  $this->assign('orderdata', $search);
		  $this->assign('Status',3);
		}
		 
		 $this->assign('orderlist', $result);
		 $this->assign('ordernum', count($resultnum));
        return $this->fetch();
    }

	public function addorder()
    {   
        if(!Session::has('uname')){ $this->error('请您重新登录！','/index'); }
        
		if(isset($_GET["id"])){
		  $id=$_GET["id"];
		  $result = Db::name('orderlist')
            ->where([
           'Id'   => $id
            ])
            ->find();
          $this->assign('orderlist', $result);
		  $this->assign('id', $id);
		  $this->assign('orderea', 1);
		}else{
		  $this->assign('orderea', 0);
		}
        $cid=$_GET["cid"];
		$orderdata = Db::name('orderdata') ->where(['Id'=> $cid])->find();

        $data = Db::query('select sum(NumData) as num from think_orderlist where OrderId='.$cid.' and Type=0 '); //交货统计
		$data1 = Db::query('select sum(NumData) as num from think_orderlist where OrderId='.$cid.' and Type=1 '); //退货统计
        $NotPaidNuminfo=($data1[0]['num']+$orderdata['NumData'])-$data[0]['num'];

		$this->assign('cid', $cid);
	    $this->assign('NotPaidNuminfo', $NotPaidNuminfo);
        $this->assign('OrderNum', $orderdata['OrderNum']);
        return $this->fetch();
    }
	public function addorderinfo()
    {   
        if(!Session::has('uname')){ $this->error('请您重新登录！','/index'); }
        $rderdata = Db::name('orderlist')->select();
		$rderinfo = Db::name('orderdata')->select();
		if(isset($_GET["id"])){
		  $id=$_GET["id"];
		  $result = Db::name('orderdata')
            ->where([
           'Id'   => $id
            ])
            ->find();
          $this->assign('orderdata', $result);
		  $this->assign('id', $id);
		  $this->assign('orderea', 1);
		}else{
		  $this->assign('orderea', 0);
		}
		$this->assign('rderdata', $rderdata);
        $OrderNumData='[';
		$ClientNameData='[';
		$MaterialsNumData='[';
		$NormNameData='[';
		$NeedlesData='[';
		$NeedleData='[';
		$SpringData='[';
        foreach($rderinfo as $k=>$v){
		 if($k==0){
		  $OrderNumData .='{ id: '.$k.', name: "'.$rderinfo[$k]["OrderNum"].'" }';
		  $ClientNameData .='{ id: '.$k.', name: "'.$rderinfo[$k]["ClientName"].'" }';
		  $MaterialsNumData .='{ id: '.$k.', name: "'.$rderinfo[$k]["MaterialsNum"].'" }';
		  $NormNameData .='{ id: '.$k.', name: "'.$rderinfo[$k]["NormName"].'" }';
          $NeedlesData .='{ id: '.$k.', name: "'.$rderinfo[$k]["Needles"].'" }';
		  $NeedleData .='{ id: '.$k.', name: "'.$rderinfo[$k]["Needle"].'" }';
		  $SpringData .='{ id: '.$k.', name: "'.$rderinfo[$k]["Spring"].'" }';
		 }else{
		  $OrderNumData .=',{ id: '.$k.', name: "'.$rderinfo[$k]["OrderNum"].'" }';
		  $ClientNameData .=',{ id: '.$k.', name: "'.$rderinfo[$k]["ClientName"].'" }';
		  $MaterialsNumData .=',{ id: '.$k.', name: "'.$rderinfo[$k]["MaterialsNum"].'" }';
		  $NormNameData .=',{ id: '.$k.', name: "'.$rderinfo[$k]["NormName"].'" }';
          $NeedlesData .=',{ id: '.$k.', name: "'.$rderinfo[$k]["Needles"].'" }';
		  $NeedleData .=',{ id: '.$k.', name: "'.$rderinfo[$k]["Needle"].'" }';
		  $SpringData .=',{ id: '.$k.', name: "'.$rderinfo[$k]["Spring"].'" }';
		 }
		}
		$OrderNumData.=']';
		$ClientNameData.=']';
		$MaterialsNumData.=']';
		$NormNameData.=']';
		$NeedlesData.=']';
		$NeedleData.=']';
		$SpringData.=']';

		$this->assign('OrderNumData', $OrderNumData);
		$this->assign('ClientNameData', $ClientNameData);
		$this->assign('MaterialsNumData', $MaterialsNumData);
		$this->assign('NormNameData', $NormNameData);
		$this->assign('NeedlesData', $NeedlesData);
		$this->assign('NeedleData', $NeedleData);
		$this->assign('SpringData', $SpringData);

        return $this->fetch();
    }
	public function adddata()
    {   
		if(!Session::has('uname')){ $this->error('请您重新登录！','/index'); }

        $OrderId=$_POST["cid"];
        $ExpressNum=trim($_POST["ExpressNum"]);
	    $NumData=trim($_POST["NumData"]);
		$DataTime=trim($_POST["DataTime"]);
		$Type=trim($_POST["Type"]);
		$Remarks=trim($_POST["Remarks"]);
		$result = Db::name('orderlist')
            ->where([
           'ExpressNum'   => $ExpressNum
            ])
            ->find();
		//if($result){ return $this->error('输入的快递单号已存在，请重新输入！','../../admin/index/hy_list'); }

        $orderdata = Db::name('orderdata') ->where(['Id'=> $OrderId])->find();
        $data = Db::query('select sum(NumData) as num from think_orderlist where OrderId='.$OrderId.' and Type=0 '); //交货统计
		$data1 = Db::query('select sum(NumData) as num from think_orderlist where OrderId='.$OrderId.' and Type=1 '); //退货统计
        $NotPaidNum=($data1[0]['num']+$orderdata['NumData'])-$data[0]['num'];
        if($Type=='0'){
		 $NotPaidNum = $NotPaidNum-$NumData;
		}else{
		 $NotPaidNum = $NotPaidNum+$NumData;
		}

	    $Orde           = new OrderModel;
        $Orde->OrderId = $OrderId;
		$Orde->UserName = Session::get('uname');;
        $Orde->ExpressNum    = $ExpressNum;
		$Orde->NumData    = $NumData;
		$Orde->DataTime    = $DataTime;
		$Orde->Type    = $Type;
		$Orde->Remarks    = $Remarks;
		$Orde->NotPaidNum    = $NotPaidNum;
        $Orde->WriteTime = date("Y-m-d H:i:s");
        if ($Orde->save()) {
            return $this->success('恭喜您增加成功！','../../admin/index/hy_list');
        } else {
            return $Orde->getError();
        }
    }
	public function adddatalist()
    {   
		if(!Session::has('uname')){ $this->error('请您重新登录！','/index'); }

        $OrderNum=trim($_POST["OrderNum"]);
		$OrderTime=trim($_POST["OrderTime"]);
		$DeliverName=trim($_POST["DeliverName"]);
		$ClientName=trim($_POST["ClientName"]);
		$MaterialsNum=trim($_POST["MaterialsNum"]);
		$NormName=trim($_POST["NormName"]);
		$NumData=trim($_POST["NumData"]);
		$UnitPrice=trim($_POST["UnitPrice"]);
		$TotalAmount=trim($_POST["TotalAmount"]);
		$Needles=trim($_POST["Needles"]);
		$Needle=trim($_POST["Needle"]);
		$Spring=trim($_POST["Spring"]);
		$Remarks=trim($_POST["Remarks"]);

		$UploadImg=trim($_POST["UploadImg"]);
        $UploadImgarr=explode(",",$UploadImg);
        $UploadImg=json_encode($UploadImgarr);
		//$result = Db::name('orderdata')->where([ 'OrderNum'   => $OrderNum ]) ->find();
		//if($result){  return $this->error('输入的订单号已存在，请重新输入！'); }

	    $Orde           = new OrderinfoModel;
		$Orde->UserName = Session::get('uname');
		$Orde->OrderNum = $OrderNum;
		$Orde->OrderNumId    = $OrderNum;
		$Orde->OrderTime    = $OrderTime;
		$Orde->DeliverName    = $DeliverName;
		$Orde->ClientName    = $ClientName;
		$Orde->MaterialsNum    = $MaterialsNum;
		$Orde->NormName    = $NormName;
		$Orde->NumData    = $NumData;
		$Orde->UnitPrice    = $UnitPrice;
		$Orde->TotalAmount    = $TotalAmount;
		$Orde->Needles    = $Needles;
		$Orde->Needle    = $Needle;
		$Orde->Spring    = $Spring;
		$Orde->Remarks    = $Remarks;
		$Orde->UploadImg    = $UploadImg;
        $Orde->WriteTime = date("Y-m-d H:i:s");
        if ($Orde->save()) {
            return $this->success('恭喜您增加成功！','../../admin/index/addorderinfo');
        } else {
            return $Orde->getError();
        }
    }
	public function editdatalist()
    {   
		if(!Session::has('uname')){ $this->error('请您重新登录！','/index'); }
        $id=$_POST["id"];
        $OrderNum= trim($_POST["OrderNum"]);
		$OrderTime=trim($_POST["OrderTime"]);
		$DeliverName= trim($_POST["DeliverName"]);
		$ClientName= trim($_POST["ClientName"]);
		$MaterialsNum= trim($_POST["MaterialsNum"]);
		$NormName= trim($_POST["NormName"]);
		$NumData= trim($_POST["NumData"]);
		$UnitPrice= trim($_POST["UnitPrice"]);
		$TotalAmount=trim($_POST["TotalAmount"]);
		$Needles= trim($_POST["Needles"]);
		$Needle= trim($_POST["Needle"]);
		$Spring= trim($_POST["Spring"]);
		$Remarks= trim($_POST["Remarks"]);
        
		$UploadImg=trim($_POST["UploadImg"]);
        $UploadImgarr=explode(",",$UploadImg);
        $UploadImg=json_encode($UploadImgarr);

		//$result = Db::name('orderdata')->where('Id', '<>', $id)->where([ 'OrderNum'   => $OrderNum ]) ->find();
		//if($result){  return $this->error('输入的订单号已存在，请重新输入！'); }
		
		$Orde           = OrderinfoModel::get($id);
		$Orde->UserName = Session::get('uname');
		$Orde->OrderNum = $OrderNum;
		$Orde->OrderNumId    = $OrderNum;
		$Orde->OrderTime    = $OrderTime;
		$Orde->DeliverName    = $DeliverName;
		$Orde->ClientName    = $ClientName;
		$Orde->MaterialsNum    = $MaterialsNum;
		$Orde->NormName    = $NormName;
		$Orde->NumData    = $NumData;
		$Orde->UnitPrice    = $UnitPrice;
		$Orde->TotalAmount    = $TotalAmount;
		$Orde->Needles    = $Needles;
		$Orde->Needle    = $Needle;
		$Orde->Spring    = $Spring;
		$Orde->Remarks    = $Remarks;
		if($_POST["UploadImg"]!="0"){ $Orde->UploadImg    = $UploadImg;   }
        if ($Orde->save()) {
            return $this->success('恭喜您修改成功！','../../admin/index/orderinfo');
        } else {
            return $Orde->getError();
        }
    }
	public function editdata()
    {   
		if(!Session::has('uname')){ $this->error('请您重新登录！','/index'); }

        $ExpressNum=$_POST["ExpressNum"];
	    $NumData=$_POST["NumData"];
		$DataTime=$_POST["DataTime"];
		$Type=$_POST["Type"];
		$Remarks=$_POST["Remarks"];
		$id=$_POST["id"];
	    $Orde           = OrderModel::get($id);
        $Orde->ExpressNum = $ExpressNum;
		$Orde->UserName = Session::get('uname');;
        $Orde->NumData    = $NumData;
		$Orde->DataTime    = $DataTime;
		$Orde->Type    = $Type;
		$Orde->Remarks    = $Remarks;
        if ($Orde->save()) {
            return $this->success('恭喜您修改成功！','../../admin/index/hy_list');
        } else {
			return  $this->error('修改失败！');
        }
    }
	public function delorder($id="")
    {   
		if(!Session::has('uname')){ $this->error('请您重新登录！','/index'); }
        $result =  Db::name('orderlist')
               ->where('id', $id)
               ->delete();
		if ($result) {
            return $this->success('删除成功！');
        } else {
            return $this->error('删除失败！');
        }
    }
	public function delorderinfo($id="")
    {   
		if(!Session::has('uname')){ $this->error('请您重新登录！','/index'); }
        $result =  Db::name('orderdata')
               ->where('id', $id)
               ->delete();
        $result1 = Db::name('orderlist')->where('OrderId', $id) ->find();
		if($result1){
		  $result =  Db::name('orderlist')
               ->where('OrderId', $id)
               ->delete();
		}
		if ($result) {
            return $this->success('删除成功！');
        } else {
            return $this->error('删除失败！');
        }
    }
	public function DelShipData($id="")
    {   
		if(!Session::has('uname')){ $this->error('请您重新登录！','/index'); }
		header('Content-Type:application/json; charset=utf-8');
        $result =  Db::name('orderlist')
               ->where('id', $id)
               ->delete();
		if ($result) {
			$arr=array('code'=>1,'hint'=>'删除成功！');
        }else{
			$arr=array('code'=>1,'hint'=>'删除失败！');
        }
		echo json_encode($arr);
    }
	public function statusedit()
    {   
		header('Content-Type:application/json; charset=utf-8');
		if(!Session::has('uname')){ $this->error('请您重新登录！','/index'); }
		$id=$_GET['id'];
		$status=$_GET['status'];
        $Orde           = OrderinfoModel::get($id);
        $Orde->Status = $status;
		if ($Orde->save()) {
          $arr=array('code'=>1,'hint'=>'修改成功！');    
        } else {
		  $arr=array('code'=>0,'hint'=>'修改失败！');	
        }
		echo json_encode($arr);
    }
    public function UploadImg()
    {   
		header('Content-Type:application/json; charset=utf-8');
		if(!Session::has('uname')){ $this->error('请您重新登录！','/index'); }
		foreach($_FILES as $k=>$v){
          $name=strstr($v["name"],".");
          $fileName = time().rand(1,9).rand(1,9).rand(1,9).rand(1,9).rand(1,9).$name;
          move_uploaded_file($v["tmp_name"],"uploads/image/" .$fileName);
          $imgarr[]="uploads/image/" .$fileName;
        }
       if(isset($imgarr)){
       $jsjson_imgarr = json_encode($imgarr);
        echo '{"code": "1", "message": "上传成功！", "data": '.$jsjson_imgarr.'}';
         }else{
         echo '{"code": "2", "message": "上传失败！", "data": ""}';
        }
    }

}

