var SE = {};

SE.inviteId = [];
window._overlay_pass = null;


//订单列表
SE.OrderList = function (page) {
	var OrderNum=$("#OrderNum").val();
	var ClientName=$("#ClientName").val();
	var starttime=$("#starttime").val();
	var stoptime=$("#stoptime").val();
    
    var page = page || 1;
    $.ajax({
        type: 'GET',
        url: "../../admin/index/OrderList",
        dataType: 'json',
        async: false,
        data: {
            page: page,
			OrderNum: OrderNum,
			ClientName: ClientName,
			starttime: starttime,
			stoptime: stoptime
        },
        success: function (e) {
            if (e.code == 1) {
                var stringHtml = "";
                $.each(e.data, function (i, v) {
                    stringHtml += "<tr><td>" +   ((i + 1) + (page - 1) * 10)  + "</td>";
                    stringHtml += "<td>" + v['OrderNum'] + "</td>";
                    stringHtml += "<td>" + v['ClientName'] + "</td>";
                    stringHtml += "<td>" + v['OrderTime'] + "</td>";
					stringHtml += "<td>" + v['MaterialsNum'] + "</td>";
					stringHtml += "<td>" + v['NormName'] + "</td>";
					stringHtml += "<td>" + v['NumData'] + "</td>";
					stringHtml += "<td>" + v['UnitPrice'] + "</td>";
					stringHtml += "<td>" + v['TotalAmount'] + "</td>";
					stringHtml += "<td><a href='../../admin/index/orderinfodata?id="+v['Id']+"'>查看</a> | <a href='../../admin/index/addorderinfo?id="+v['Id']+"'>修改</a> | <a href='javascript:;' onclick='SE.delorder("+v['Id']+")'>删除</a></td>";
					stringHtml += "</tr>";
                })
                $(".orderlist").html(stringHtml);
				$(".num").html("共 "+e.message[0]['num']+" 条记录");
                $(".tcdPageCode").createPage({
                    pageCount: e.message[1]['sum'],//总页数
                    current: page, //当前页
                    backFn: function (p) { //p点击的页面
                        SE.OrderList(p);
                    }
                });
			
            }
            else {
                //alert(e.message);
            }
        }
    });
}

//订单列表删除
SE.delorder = function (id){
      if (confirm("您确定要删除吗？")) {  
            location.href ='../../admin/index/delorderinfo/id/'+id; 
        }  
        else {  
           return
        }  
}

//订单统计
SE.OrderStatistics = function (page) {
	var OrderNum=$("#OrderNum").val();
	var ClientName=$("#ClientName").val();
	var starttime=$("#starttime").val();
	var stoptime=$("#stoptime").val();
    
    var page = page || 1;
    $.ajax({
        type: 'GET',
        url: "../../admin/index/OrderStatistics",
        dataType: 'json',
        async: false,
        data: {
            page: page,
			OrderNum: OrderNum,
			ClientName: ClientName,
			starttime: starttime,
			stoptime: stoptime
        },
        success: function (e) {
            if (e.code == 1) {
                var stringHtml = "";
				var Status = [];
                $.each(e.data, function (i, v) {

					if(v['ShipNumData']==null){ var ShipNumData=0; }else{ var ShipNumData= parseInt(v['ShipNumData']); }
					if(v['ReturnNumData']==null){ var ReturnNumData=0; }else{ var ReturnNumData= parseInt(v['ReturnNumData']);  }
					if(v['NumData']==null){ var NumData=0; }else{ var NumData= parseInt(v['NumData']);  }
					
					if(v['Status']==0){ Status[0] = "selected = 'selected'"; }
					if(v['Status']==1){ Status[1] = "selected = 'selected'"; }
					if(v['Status']==2){ Status[2] = "selected = 'selected'"; }
					if(v['Status']==3){ Status[3] = "selected = 'selected'"; }
					if(v['Status']==4){ Status[4] = "selected = 'selected'"; }
					if(v['Status']==5){ Status[5] = "selected = 'selected'"; }
                    stringHtml += "<tr><td>" +   ((i + 1) + (page - 1) * 10)  + "</td>";
                    stringHtml += "<td title='" + v['OrderNum'] + "'>" + v['OrderNum'] + "</td>";
                    stringHtml += "<td title='" + v['ClientName'] + "'>" + v['ClientName'] + "</td>";
                    stringHtml += "<td title='" + v['OrderTime'] + "'>" + v['OrderTime'] + "</td>";
					stringHtml += "<td title='" + v['UnitPrice'] + "'>" + v['UnitPrice'] + "</td>";
					stringHtml += "<td title='" + v['TotalAmount'] + "'>" + v['TotalAmount'] + "</td>";
					stringHtml += "<td title='" + v['NumData'] + "'>" + v['NumData'] + "</td>";
					stringHtml += "<td title='" + ShipNumData + "'><a href='../../admin/index/shiplist?id="+v['Id']+"'>" + ShipNumData + "</a></td>";
					stringHtml += "<td title='" + ReturnNumData + "'><a href='../../admin/index/returnlist?id="+v['Id']+"'>" + ReturnNumData + "</a></td>";
					stringHtml += "<td>" + ((NumData+ReturnNumData)-ShipNumData) + "</td>";
					stringHtml += "<td><select id='Status' data_id='"+v['Id']+"' onchange='SE.StatusEdit(this)'><option value='0' "+ Status[0] +">无</option><option value='1' "+ Status[1] +" >待车</option><option value='2' "+ Status[2] +">待镀</option><option value='3' "+ Status[3] +">待装</option><option value='4' "+ Status[4] +">待包</option><option value='5' "+ Status[5] +">待交</option></select></td>";
					stringHtml += "<td><a href='../../admin/index/addorder?cid="+v['Id']+"'>增加</a> | <a href='../../admin/index/chartshow?cid="+v['Id']+"'>图表</a></td>";
					stringHtml += "</tr>";
                })
                $(".orderlist").html(stringHtml);
				$(".num").html("共 "+e.message[0]['num']+" 条记录");
                $(".tcdPageCode").createPage({
                    pageCount: e.message[1]['sum'],//总页数
                    current: page, //当前页
                    backFn: function (p) { //p点击的页面
                        SE.OrderStatistics(p);
                    }
                });
			
            }
            else {
                //alert(e.message);
            }
        }
    });
}

//订单统计状态修改
SE.StatusEdit = function (e){
	var id=$(e).attr("data_id");
    var status=$(e).val();
    if (confirm("您确定要修改订单状态吗？")) { 
	 $.ajax({
        type: 'GET',
        url: "../../admin/index/statusedit",
        dataType: 'json',
        async: false,
        data: {
            id: id,
			status: status
        },
        success: function (e) {
            if (e.code == 1) {
			 location.href ='../../admin/index/hy_list';
            }else {
			  alert(e.hint);
             location.href ='../../admin/index/hy_list';
            }
        }
     });
         
    }else{ 
      SE.OrderStatistics();
      return
    }  
}

//已发货列表
SE.ShipDataList = function (page) {
	var id=$("#editid").val();
	var starttime=$("#starttime").val();
	var stoptime=$("#stoptime").val();
    
    var page = page || 1;
    $.ajax({
        type: 'GET',
        url: "../../admin/index/ShipDataList",
        dataType: 'json',
        async: false,
        data: {
            page: page,
			id: id,
			starttime: starttime,
			stoptime: stoptime
        },
        success: function (e) {
            if (e.code == 1) {
                var stringHtml = "";
                $.each(e.data, function (i, v) {
                    stringHtml += "<tr><td>" +   ((i + 1) + (page - 1) * 10)  + "</td>";
                    stringHtml += "<td>" + v['OrderNum'] + "</td>";
                    stringHtml += "<td>" + v['ClientName'] + "</td>";
                    stringHtml += "<td>" + v['ExpressNum'] + "</td>";
					stringHtml += "<td>" + v['NumData'] + "</td>";
					stringHtml += "<td>" + v['NotPaidNum'] + "</td>";
					stringHtml += "<td>" + v['DataTime'] + "</td>";
					stringHtml += "<td><a href='../../admin/index/addorder?cid="+v['cid']+"&id="+v['Id']+"'>修改</a> | <a href='javascript:;' onclick='SE.DelShipData("+v['Id']+")'>删除</a></td>";
					stringHtml += "</tr>";
                })
                $(".orderlist").html(stringHtml);
				$(".num").html("共 "+e.message[0]['num']+" 条记录");
                $(".tcdPageCode").createPage({
                    pageCount: e.message[1]['sum'],//总页数
                    current: page, //当前页
                    backFn: function (p) { //p点击的页面
                        SE.ShipDataList(p);
                    }
                });
			
            }
            else {
                //alert(e.message);
            }
        }
    });
}

//已发货列表删除
SE.DelShipData = function (id){
      if (confirm("您确定要删除吗？")) {  
			 $.ajax({
				type: 'GET',
				url: "../../admin/index/DelShipData",
				dataType: 'json',
				async: false,
				data: {
					id: id
				},
				success: function (e) {
					if (e.code == 1) {
					  SE.ShipDataList();
					}else {
					  alert(e.hint);
					}
				}
			 });
			
        }else {  
           return
        }  
}

//退货列表
SE.ReturnDataList = function (page) {
	var id=$("#editid").val();
	var starttime=$("#starttime").val();
	var stoptime=$("#stoptime").val();
    
    var page = page || 1;
    $.ajax({
        type: 'GET',
        url: "../../admin/index/ReturnDataList",
        dataType: 'json',
        async: false,
        data: {
            page: page,
			id: id,
			starttime: starttime,
			stoptime: stoptime
        },
        success: function (e) {
            if (e.code == 1) {
                var stringHtml = "";
                $.each(e.data, function (i, v) {
                    stringHtml += "<tr><td>" +   ((i + 1) + (page - 1) * 10)  + "</td>";
                    stringHtml += "<td>" + v['OrderNum'] + "</td>";
                    stringHtml += "<td>" + v['ClientName'] + "</td>";
                    stringHtml += "<td>" + v['ExpressNum'] + "</td>";
					stringHtml += "<td>" + v['NumData'] + "</td>";
					stringHtml += "<td>" + v['NotPaidNum'] + "</td>";
					stringHtml += "<td>" + v['DataTime'] + "</td>";
					stringHtml += "<td><a href='../../admin/index/addorder?cid="+v['cid']+"&id="+v['Id']+"'>修改</a> | <a href='javascript:;' onclick='SE.DelShipData("+v['Id']+")'>删除</a></td>";
					stringHtml += "</tr>";
                })
                $(".orderlist").html(stringHtml);
				$(".num").html("共 "+e.message[0]['num']+" 条记录");
                $(".tcdPageCode").createPage({
                    pageCount: e.message[1]['sum'],//总页数
                    current: page, //当前页
                    backFn: function (p) { //p点击的页面
                        SE.ReturnList(p);
                    }
                });
			
            }
            else {
                //alert(e.message);
            }
        }
    });
}

//未交货图表
SE.ChartDataShow = function () {
   var cid=$("#cid").val();
   var ydata=[];
   var xdata=[];  
    $.ajax({
        type: 'GET',
        url: "../../admin/index/ChartDataShow",
        dataType: 'json',
        async: false,
        data: {
			cid: cid
        },
        success: function (e) {
            if (e.code == 1) {
                $.each(e.data, function (i, v) {
                  ydata.push(v['NotPaidNum']);//括号里面存放数据 
				  xdata.push(v['DataTime'].substring(0,10));//括号里面存放数据 
                })
			    // 基于准备好的dom，初始化echarts实例
				var myChart = echarts.init(document.getElementById('main'));

				// 指定图表的配置项和数据
				 var  option = {
					title: {
						text: '未交货数量趋势图',
						left: 'center'
					},
					tooltip: {
						trigger: 'item',
						formatter: '未交数量：{c}<br/>时间：{b} '
					},
					legend: {
						left: 'left',
						data: ['显示/隐藏']
					},
					xAxis: {
						type: 'category',
						name: 'x',
						splitLine: {show: false},
						data: xdata
					},
					grid: {
						left: '3%',
						right: '4%',
						bottom: '3%',
						containLabel: true
					},
					yAxis: {
						type: 'log',
						name: 'y'
					},
					series: [
						{
							name: '显示/隐藏',
							type: 'line',
							data: ydata
						}
					]
				};

				 // 使用刚指定的配置项和数据显示图表。
				 myChart.setOption(option);
            }else {
                alert(e.hint);
            }
        }
    });
}