<?php $this->load->view('admin/header');?>
	<script src="static/js/highcharts/highcharts.js"></script>

<script type="text/javascript">
	$(function () {
    $('#container').highcharts({
        title: {
            text: '喝奶大作战数据分析',
            x: 0 //center
        },
        subtitle: {
            text: '2016-03-01至2016-03-04',
            x: 0
        },
        xAxis: {
            categories: ['2016-03-01', '2016-03-02', '2016-03-03', '2016-03-04']
        },
        yAxis: {
            title: {
                text: '玩家与游戏记录'
            },
            plotLines: [{
                value: 0,
                width: 1,
                color: '#808080'
            }]
        },
        tooltip: {
            valueSuffix: '个'
        },
        legend: {
            layout: 'vertical',
            align: 'right',
            verticalAlign: 'middle',
            borderWidth: 0
        },
        series: [{
            name: '玩家',
            data: [<?=$count_user_1?>, <?=$count_user_2?>, <?=$count_user_3?>, <?=$count_user_4?>]
        }, {
            name: '游戏记录',
            data: [<?=$count_log_1?>, <?=$count_log_2?>, <?=$count_log_3?>, <?=$count_log_4?>]
        }]
    });
	
	 $('#container2').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: '喝奶大作战数据分析(玩家)'
        },
        tooltip: {
    	    pointFormat: '{series.name}: <b>{point.y:.0f}个</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    format: '<b>{point.name}</b>: {point.percentage:.1f} %'
                }
            }
        },
        series: [{
            type: 'pie',
            name: '玩家个数',
            data: [
                ['2016-03-01',  <?=$count_user_1?>],
                ['2016-03-02',  <?=$count_user_2?>],
                
                ['2016-03-03',  <?=$count_user_3?>],
                ['2016-03-04',   <?=$count_user_4?>],
               
            ]
        }]
    });
	
	$('#container3').highcharts({
        chart: {
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: false
        },
        title: {
            text: '喝奶大作战数据分析(游戏记录)'
        },
        tooltip: {
    	    pointFormat: '{series.name}: <b>{point.y:.0f}条</b>'
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    color: '#000000',
                    connectorColor: '#000000',
                    format: '<b>{point.name}</b>: {point.percentage:.1f}%'
                }
            }
        },
        series: [{
            type: 'pie',
            name: '游戏记录条数',
            data: [
                ['2016-03-01',  <?=$count_log_1?>],
                ['2016-03-02',  <?=$count_log_2?>],
                
                ['2016-03-03',  <?=$count_log_3?>],
                ['2016-03-04',   <?=$count_log_4?>],
               
            ]
        }]
    });
});
				
</script>
<style>
.nomargin table {
  border: 1px solid #ccc;
  border-collapse: separate;
  width: 100%;
}
.nomargin table td.sum_data {
  font-size: 18px;
  font-weight: 700;
  height: 20px;
  line-height: 20px;
}
.nomargin table td.sum_title {
  height: 20px;
  line-height: 20px;
}
</style>
<div class="mainbox nomargin" style="margin:10px 0px 0px 10px;">
<table>
		<tbody><tr>
			<td class="sum_title">游戏总次数<a tip="pv" class="sum_help">&nbsp;</a></td>
			<td class="sum_title">玩家总个数<a tip="uv" class="sum_help">&nbsp;</a></td>
			<td class="sum_title">人均玩次数<a tip="ip" class="sum_help">&nbsp;</a></td>
			
		</tr>
		<tr>
			<td class="sum_data"><?=$log_sum = $count_log_1+$count_log_2+$count_log_3+$count_log_4?></td>
			<td class="sum_data"><?=$user_sum =$count_user_1+$count_user_2+$count_user_3+$count_user_4?></td>
			<td class="sum_data"><?=ceil($log_sum/$user_sum)?></td>
			
			
		</tr>
	</tbody></table>
	<div id="container" style="min-width: 600px; height: 400px; margin: 0 auto; border:#ddd 1px solid; display:"></div>
    <div id="container2" style="width: 520px; height: 400px; margin: 0 auto; border:#ddd 1px solid; float:left;"></div>
     <div id="container3" style="width: 500px; height: 400px; margin: 0 auto; border:#ddd 1px solid;  float:right"></div>



</div>

<?php $this->load->view('admin/footer');?>