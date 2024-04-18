
<script type="text/javascript">
	window.onload = function(){
		new JsDatePick({
			useMode:2,
			target:"inputField1",
			dateFormat:"%Y-%m-%d"
		});
		new JsDatePick({
			useMode:2,
			target:"inputField2",
			dateFormat:"%Y-%m-%d"
		});
		new JsDatePick({
			useMode:2,
			target:"inputField3",
			dateFormat:"%Y-%m-%d"
		});
		new JsDatePick({
			useMode:2,
			target:"inputField4",
			dateFormat:"%Y-%m-%d"
		});
	};			
</script>
<body>
<div><span class='judul'>LAPORAN OSS RBA PROYEK</span>
<p>	

<div id="border">
<form action="reports/oss_rba/proyek_excel.php" method="post" target="_blank" autocomplete="off">
	<table class="">
		<tr>
			<td width="300"><b>Rincian Realisasi Investasi</b></td><td>:</td>
			<td width=""><input type='text' size='10' name='search1' value='<?php echo $search1;?>' id="inputField1" class='search'> 
			s/d  <input type='text' size='10' name='search2' value='<?php echo $search2;?>' id="inputField2" class='search'></td>
			<td align="" width=""><input type="submit" name="cetak" value="CETAK EXCEL"></td>
		</tr>

	</table>
</form>	
<form action="reports/oss_rba/proyek_sektor_excel.php" method="post" target="_blank" autocomplete="off">
	<table class="">
		<tr>
			<td width="300"><b>Rincian Realisasi Investasi Per Sektor</b></td><td>:</td>
			<td width=""><input type='text' size='10' name='search1' value='<?php echo $search1;?>' id="inputField3" class='search'> 
			s/d  <input type='text' size='10' name='search2' value='<?php echo $search2;?>' id="inputField4" class='search'></td>
			<td align="" width=""><input type="submit" name="cetak" value="CETAK EXCEL"></td>
		</tr>

	</table>
</form>	

</body>
