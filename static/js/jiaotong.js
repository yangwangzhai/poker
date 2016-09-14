/*
	交通事件 地图后台程序
	tangjian 最后修改 20120521

*/

var map = null;
var g_color = [ "#0C0", "#F90", "#FF0000" ];  // 颜色
var g_marker = null;     // 当前激活的交通事故 标注

// 网页初始化
function initialize()
{
    map = new BMap.Map("map_canvas");
	map.centerAndZoom(new BMap.Point(108.3157, 22.8219), 13);
	map.addControl(new BMap.NavigationControl());
	map.addControl(new BMap.ScaleControl());
	map.addControl(new BMap.OverviewMapControl());
	map.addControl(new BMap.MapTypeControl());
	map.enableScrollWheelZoom();
	map.setDefaultCursor("auto");
	
	// 显示交通	
	showpoint();
	
	// 点击 添加交通
	$("#addjiaotong").click(function(){	
		resetform();	
		map.addEventListener("click", addjiaotong);		
	}); 
	// 点击提交交通
	$("#submit_jiaotong").click(post_jiatong); 
	
	// 点击删除按钮
	$("#deletejiaotong").click(function(){	
		if(confirm("确定该线段吗？")){			
			var id = $("#jtid").val();			
			if( id > 0 ) {
				$.get("cpi.php?d=admin&c=jiaotong&m=delete&id="+id,function(data){					
					map.removeOverlay(g_marker);					
				});
				
			} else {
				map.removeOverlay(g_marker);											
			}
			resetform();
		}
	});

}


// 表单初始化
function resetform()
{
	$("#jtid").val("");
	$("#info").val("");
	$("#types").val("0");
	$("#point").val("");
	$("#updatetime2").val(getTimes());
	
	map.removeEventListener("click", addjiaotong);
}


// 添加交通事件坐标
function addjiaotong(e)
{	
	var marker = new BMap.Marker(e.point);        // 创建标注  	
	map.addOverlay(marker);                     // 将标注添加到地图中 
	$("#point").val( e.point.lng + "," + e.point.lat);
	
	marker.enableDragging();
	marker.addEventListener("dragend", function(e){  
	 	$("#point").val( e.point.lng + "," + e.point.lat);  
	});
	
	map.removeEventListener("click", addjiaotong);	
}

// 提交表单  交通事件
function post_jiatong()
{
	var id = $("#jtid").val();
	var types = $("#types").val();
	var info = $("#info").val();
	var picture = $("#picture").val();	
	var point = $("#point").val();
	var updatetime = $("#updatetime2").val();
	
	if (point == "") {
		$("#point").focus();
		return ;
	}
	
	$.post("cpi.php?d=admin&c=jiaotong&m=save", { id:id, types:types, info:info,picture:picture, point:point, updatetime:updatetime }, function(dataid){		
		if (dataid) {		
			resetform();
			alert("成功！");		
		} 
	});		
}


// 显示交通
function showpoint()
{	
	$.get("cpi.php?d=admin&c=jiaotong&m=list_lite", function(data) {	
		data = eval('('+ data +')');
		for( var i=0; i<data.length; i++) {	
			var id = data[i].id;			
			var latlng = data[i].point.split(',');
			var point = new BMap.Point(latlng[0], latlng[1]);		
			var marker = new BMap.Marker(point);        // 创建标注  	
			map.addOverlay(marker);                     // 将标注添加到地图中			
			
			clickpoint(marker,id);  // 侦听改线段，点击线段的时候
		}
	});	
}


// 点击标注的时候 激活 用于修改
function clickpoint(marker,id)
{	
	marker.addEventListener('click', function(event) {
		g_marker = marker;		
		$.get("cpi.php?d=admin&c=jiaotong&m=get_one&id="+id, function(data){			
			data = eval('('+ data +')');
			
			$("#jtid").val( data.id );
			$("#types").val( data.types );
			$("#info").val( data.info );
			$("#point").val( data.point );
			$("#updatetime2").val( data.updatetime  );
		});
	});	
}

$(document).ready(function() { 

        // 提交交通事件
		//$('#myForm').ajaxForm(function() { 
//			alert("成功！"); 
//			resetform();
//		}); 
});