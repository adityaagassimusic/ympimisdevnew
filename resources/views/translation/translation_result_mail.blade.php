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
	</style>
</head>
<body>
	<div>
		<center>
			<p>This is an automatic notification. Please do not reply to this address. 返信不要の自動通知です。</p>
			<span style="font-weight: bold; color: purple; font-size: 24px;">TRANSLATION REQUEST</span><br>
			<span style="font-weight: bold; font-size: 26px;">{{ $data['translation']->translation_id }}</span><br>
			<span style="font-weight: bold; font-size: 26px; color: green;">
				Your Request Has Been Translated by {{ $data['translation']->pic_id }} - {{ $data['translation']->pic_name }}
			</span><br>
			<span style="font-weight: bold; font-size: 24px;">Please check attached file(s)/email body.</span>
			<br>
			<span style="font-weight: bold;">ID:</span><br>
			<span>{{ $data['translation']->requester_id }}</span><br>
			<span style="font-weight: bold;">Name:</span><br>
			<span>{{ $data['translation']->requester_name }}</span><br>
			<span style="font-weight: bold;">Department:</span><br>
			<span>{{ $data['translation']->department_name }}</span><br>
			<br>
			<span style="font-weight: bold;">Category:</span><br>
			<span>{{ $data['translation']->document_type }}</span><br>
			<span style="font-weight: bold;">Due Date:</span><br>
			<span>{{ date('d F Y', strtotime($data['translation']->request_date)) }}</span><br>
			<span style="font-weight: bold;">Number of Page(s):</span><br>
			<span>{{ $data['translation']->number_page }} Page(s)</span><br>
			<span style="font-weight: bold;">Number of Attachments:</span><br>
			<span>{{ count($data['filenames']) }} Att(s)</span><br>
			<span style="font-weight: bold;">Note:</span><br>
			<span>{{ $data['translation']->remark }}</span><br>
			<span style="font-weight: bold;">Translation Request:</span><br>
			<span><?= $data['translation']->translation_request ?></span><br>
			<span style="font-weight: bold;">Translation Result:</span><br>
			<span><?= $data['translation']->translation_result ?></span><br>
			<br>
			<br>
			<a href="http://10.109.52.4/mirai/public/index/translation">&#10148; Translation Request Monitoring</a>
		</center>
	</div>
</body>
</html>