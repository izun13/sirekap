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
	};
</script>
<body>
<?php
mysql_close();
mysql_connect("172.17.20.7","smsgateway","sms432432432");
$con=mysql_select_db("gammu");
//if($con){echo "OK";}else{echo "KO";}

	$recpage = 20;
	$to_page = "data-sms";
	
	$search = explode("x",$search);
	$search1 = $search[0]; 
	if($search1 == "") $search1 = $_POST["search1"];
	$search2 = $search[1]; 
	if($search2 == "") $search2 = $_POST["search2"];
	$search3 = $search[2]; 
	if($search3 == "") $search3 = $_POST["search3"];
	
	$search1 = str_replace("_","-",$search1);
	$search2 = str_replace("_","-",$search2);
	if(($search1 != "") and ($search2 != "") and ($search1 == $search2)) $search2 = date('Y-m-d', strtotime('+1 days', strtotime($search2)));
		
	$tabel = "select*from sentitems order by ID desc";
	if(($search1 != "") and ($search2 != "")){		
		$tabel = "select*from sentitems WHERE SendingDateTime >= '$search1' and SendingDateTime <= '$search2' order by id desc";
	}
	if(($search3 != "")){		
		$tabel = "select*from sentitems WHERE Status = '$search3' order by id desc";
	}
	if(($search1 != "") and ($search2 != "") and ($search3 != "")){		
		$tabel = "select*from sentitems WHERE SendingDateTime >= '$search1' and SendingDateTime <= '$search2' and Status = '$search3' order by id desc";
	}
		
		$query = mysql_query($tabel);
		if(isset($_GET['page'])) $starting = $_GET['page'];		
		if(isset($_POST['page'])) $starting = ($_POST['page'])*$recpage-$recpage;
		if($starting=='') $starting = 0;
		
		$search1x = str_replace("-","_",$search1);
		$search2x = str_replace("-","_",$search2);
		$search = $search1x."x".$search2x."x".$search3;
		page($query,$recpage,$starting,$to_page,$search);
		
?>	
<div class="judul">Data Pengiriman SMS</div>
<table width='100%'>
<tr><td>	
	<form action='datasms' method='post' autocomplete="off"> 
		Tanggal : <input type='text' size='10' name='search1' value='<?php echo $search1;?>' id="inputField1" class='search'> 
		s/d : <input type='text' size='10' name='search2' value='<?php echo $search2;?>' id="inputField2" class='search'> 
		Status Pengiriman <select name="search3">
			<option value="">Pilih Status</option>
			<?php
			$pilihan = array('SendingOK','SendingOKNoReport','SendingError','DeliveryOK','DeliveryFailed','DeliveryPending','DeliveryUnknown','Error');
			for ($x=0;$x<count($pilihan);$x++){
				$selected = "";
				if($pilihan[$x] == $search3)$selected = "selected";
				echo"<option value='".$pilihan[$x]."' $selected>".$pilihan[$x]."</option>";			
			}
			?>			
			</select>
		<input type='submit' name='submit' value='Tampilkan'>
	</form>
</td></tr>	
</table>
<?php
	echo"<a href='includes/sms/rekap_sms_excel.php?search1=$search1&search2=$search2&search3=$search3'><img src=\"img/excel.png\" width='50' title='cetak excel'></img></a>";
	echo"<a href='includes/sms/rekap_sms_pdf.php?search1=$search1&search2=$search2&search3=$search3'  target='_blank'><img src=\"img/pdf.png\" width='38' title='cetak PDF'></img></a>";
?>
	<table class="tabelbox1" width="800">
		<tr>
			<th><b>No.</b></th>
			<th><b>Tanggal </b></th>
			<th><b>Nomor SMSC</b></th>
			<th><b>Nomor Tujuan</b></th>		
			<th><b>Isi SMS</b></th>
			<th><b>Status</b></th>
		</tr>
		<?php
		$starting = starting();
		$recpage = recpage();
		
		// Nampilin Data				
		$query = mysql_query($tabel." LIMIT $starting,$recpage");
		$current = $_REQUEST["x"];
		$current1 = 0;
		
		$i=$starting+1;		
		while ($r= mysql_fetch_array($query)){
			
		if($i % 2==0){
			echo "<tr class='cyan'>";
		}else{
			echo "<tr>";
		}
		
		$tgl1 = "";
		if($r['SendingDateTime']) $tgl1 = tgl2($r['SendingDateTime']);
		if($r['DestinationNumber']) $telp = preg_replace("/[^0-9]/", "", $r['DestinationNumber']);
		echo "
		  <td align='center'>$i</td>
		  <td align='center'>$tgl1</td>
		  <td align='center'>$r[SMSCNumber]</td>
		  <td align='left'>$telp</td>
		  <td align='left'>$r[TextDecoded]</td>
		  <td align='center'>$r[Status]</td>";
		
		//echo "<td align='center'><a href=input-kesehatan-$id-$starting-$search><img src=\"img/edit.png\" width='25' title='ubah'></img></td>";
		//echo "<td align='center'></a><a href='hapus-kesehatan-$id-$starting-$search' onclick = \"return confirm ('apakah anda yakin ingin menghapusnya?')\")><img src=\"img/hapus.png\" width='25' title='hapus'></img></a></td>";
			
		echo "</tr>";
		$i++;	
		}
		?>
		
</table>

<table width='100%'>
<tr><td width='100'>
<?php 
		//echo"<a href=index.php?send=input-penduduk&x=$current&page=$starting><img src=\"img/edit.png\" width='30' title='ubah'></img></a>				
		//<a href='index.php?send=hapus-penduduk&x=$current&page=$starting' onclick = \"return confirm ('apakah anda yakin ingin menghapusnya?')\")><img src=\"img/hapus.png\" width='30' title='hapus'></img></a>
		//<a href='includes/penduduk/cetak_pdf.php?x=$current' target='_blank'><img src=\"img/cetak.png\" width='30' height='35' title='cetak'></img></a>	";
?>
</td><td>
<?php
	$page_showing = page_showing();
	echo show_navi();
?>
</td></tr>
</table>
</body>
