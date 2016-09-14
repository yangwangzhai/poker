var map = null;  // 全局地图对象
var g_color = [ "#0C0", "#F90", "#FF0000" ];  // 颜色
var g_polyline = [];  // 全部线段的数组
var marker = null;    // 查询 地址对象

// 网页初始化
function initialize() 
{	
    var latlng = new google.maps.LatLng(22.8219, 108.3157); // 中心点
    var myOptions = {
      zoom: 13,
      center: latlng,     
      mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    map = new google.maps.Map(document.getElementById("map_canvas"), myOptions);
	
	// 显示路况按钮
	var lukuangDiv = document.getElementById('lukuang');
    lukuangDiv.index = 1;
    map.controls[google.maps.ControlPosition.TOP_RIGHT].push(lukuangDiv);
	
	new polyline();	  // 线路对象
	
}

// 线路对象
function polyline()
{
	this.ishow = 0;	
	var line = this;
	
	$("#lukuang").click(function(){		
		if(line.ishow == 0) {			
			line.showline();	
			line.ishow = 1;
			$(this).css("font-weight","bold");
		} else {			
			line.hideline();
			line.ishow = 0;
			$(this).css("font-weight","");
		}
	});	
		
	// 显示线路
	this.showline = function(){		
		$.get("get.php?type=all", function(data){	
			data = eval('('+ data +')');
			for( var i=0; i<data.length; i++) {	
				var id = data[i].id;
				var path_array = data[i].path.split(' ');
				var Coordinates = [];
				var color = g_color[ data[i].status ];			
				
				for(var n=0; n<path_array.length; n++) {				
					var latlng = path_array[n].split(',');				
					Coordinates.push( new google.maps.LatLng(latlng[0],latlng[1]) );
				}
				
				 g_polyline.push( new google.maps.Polyline({
						path: Coordinates,
						strokeColor: color,
						strokeOpacity: 1.0,
						strokeWeight: 3,
						map : map,
						zIndex : data[i].z_index
						}) );
			}		
		});		
	}
	
	// 隐藏线路
	this.hideline = function(){
		for(var i in g_polyline) {
			g_polyline[i].setMap(null);
		}
	}
	
}

// 查找 地址
function codeAddress() {
	if(marker) marker.setMap(null);	// 清楚之前的标记
	
    var address = document.getElementById("keywords").value;
	var geocoder = new google.maps.Geocoder();
    if (geocoder) {
      geocoder.geocode( { 'address': address}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
          map.setCenter(results[0].geometry.location);
          marker = new google.maps.Marker({
              map: map, 
              position: results[0].geometry.location
          });
        } else {
          alert("Geocode was not successful for the following reason: " + status);
        }
      });
    }
}
  
  
