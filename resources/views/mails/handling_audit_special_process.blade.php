<!DOCTYPE html>
<html>
<head>
	<style type="text/css">
		td{
			padding-right: 5px;
			padding-left: 5px;
			padding-top: 0px;
			padding-bottom: 0px;
		}
		th{
			padding-right: 5px;
			padding-left: 5px;			
		}
		.button {
		  background-color: #4CAF50; /* Green */
		  border: none;
		  color: white;
		  padding: 10px;
		  text-align: center;
		  text-decoration: none;
		  display: inline-block;
		  font-size: 16px;
		  margin: 4px 2px;
		  cursor: pointer;
		  border-radius: 4px;
		  cursor: pointer;
		}
		.button_reject {
		  background-color: #fa3939; /* Green */
		  border: none;
		  color: white;
		  padding: 10px;
		  text-align: center;
		  text-decoration: none;
		  display: inline-block;
		  font-size: 16px;
		  margin: 4px 2px;
		  cursor: pointer;
		  border-radius: 4px;
		  cursor: pointer;
		}
	</style>
</head>
<body>
	<div>
		<center>
			<img src="data:image/png;base64,{{base64_encode(file_get_contents(public_path('mirai.jpg')))}}" alt=""><br><br>
			@if($data[0]['title'] == 'week_before')
			<span style="font-weight: bold; color: purple; font-size: 17px;">REMINDER PENANGANAN AUDIT PROSES KHUSUS MENDEKATI DUE DATE<br>特殊工程の監査リマインダー</span><br>
			@else
			<span style="font-weight: bold; color: purple; font-size: 17px;">REMINDER PENANGANAN AUDIT PROSES KHUSUS MELEBIHI DUE DATE<br>特殊工程の監査リマインダー</span><br>
			@endif
			<p>This is an automatic notification. Please do not reply to this address. 返信不要の自動通知です。</p>
		</center>
	</div>			
	<div>
		<center>
			<div style="width: 70%">
				<table style="border:1px solid black; border-collapse: collapse;">
					<thead style="background-color: rgb(126,86,134);color: white">
						<tr>
							<th style="font-size: 15px">Document</th>
							<th style="font-size: 15px">Auditor</th>
							<th style="font-size: 15px">Auditee</th>
							<th style="font-size: 15px">Periode</th>
							<th style="font-size: 15px">Due Date</th>
							<th style="font-size: 15px">Action</th>
						</tr>
					</thead>
					<tbody align="center">
						<?php for ($i=0; $i < count($data[0]['datas']); $i++) { ?>
						<tr>
							<td style="border:1px solid black; font-size: 15px; width: 20%; height: 20;text-align: left;">{{$data[0]['datas'][$i]->document_number}} - {{$data[0]['datas'][$i]->document_name}}</td>
							<td style="border:1px solid black; font-size: 15px; width: 20%; height: 20;text-align: left;">
							@if(str_contains($data[0]['datas'][$i]->auditor_id,','))
							{{explode(',',$data[0]['datas'][$i]->auditor_id)[0]}} - {{explode(',',$data[0]['datas'][$i]->auditor_name)[0]}}<br>
							{{explode(',',$data[0]['datas'][$i]->auditor_id)[1]}} - {{explode(',',$data[0]['datas'][$i]->auditor_name)[1]}}
							@else
							{{$data[0]['datas'][$i]->auditor_id}} - {{$data[0]['datas'][$i]->auditor_name}}
							@endif
							</td>
							<td style="border:1px solid black; font-size: 15px; width: 20%; height: 20;text-align: left;">{{$data[0]['datas'][$i]->auditee_id}} - {{$data[0]['datas'][$i]->auditee_name}}</td>
							<td style="border:1px solid black; font-size: 15px; width: 20%; height: 20;text-align: left;">
							{{date('M-Y',strtotime($data[0]['datas'][$i]->schedule_date))}}
							</td>
							<td style="border:1px solid black; font-size: 15px; width: 20%; height: 20;text-align: left;">
							{{$data[0]['datas'][$i]->due_date}}
							</td>
							<td style="border:1px solid black; font-size: 15px; width: 20%; height: 20;text-align: center;">
								<a style="text-decoration: none;color: blue;" href="10.109.52.4/mirai/public/handling/qa/special_process/{{$data[0]['datas'][$i]->schedule_id}}">Input Penanganan</a>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<br><br>
			<div style="width: 80%">
				<br>
					<span style="font-weight: bold; background-color: orange;">&#8650; <i>Click Here For</i> &#8650;</span><br>
					<br>
					<a style="width: 50px;text-decoration: none;font-size:16px;" href="10.109.52.4/mirai/public/index/qa/special_process">&nbsp;&nbsp;&nbsp; Monitoring <small>監視</small> &nbsp;&nbsp;&nbsp;</a>
				<br>
				<br>
				<p>
					<b>Thanks & Regards,</b>
				</p>
				<p>PT. Yamaha Musical Products Indonesia<br>
					Jl. Rembang Industri I / 36<br>
					Kawasan Industri PIER - Pasuruan<br>
					Phone   : 0343 – 740290<br>
					Fax.    : 0343 - 740291
				</p>
			</div>
		</center>
	</div>
</body>
</html>