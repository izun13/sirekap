<div class="judul">Diagnosis Penyakit</div>
<form action="" method="post" name='text'>
	
	
	<?php
	$submit=$_POST["submit"];
	if(($submit != "Proses Diagnosa")){
	?>
	<table class='tabelbox2'>
		<tr>			
			<td valign="top" bgcolor="EFEFEF">
				<table class=''>
				<tr>
					<td align="center" colspan="2" bgcolor="CDCDCD">Gejala Luar</td>
				</tr>	
				<?php
				$query_gjl = mysql_query("select * from tb_gejala WHERE jenis = 'Gejala Luar' ");	
				while ($r_gjl= mysql_fetch_array($query_gjl)){	
				echo "<tr>
						<td valign='top'><input type='checkbox' name='$r_gjl[id_gejala]'></td>
						<td>$r_gjl[gejala]</td>
					</tr>";
				}
				?>
				</table>
			</td>	
			
			<td valign="top" bgcolor="">
				<table class=''>
				<tr>
					<td align="center" colspan="2" bgcolor="CDCDCD">Gejala Dalam</td>
				</tr>	
				<?php
				
				$query_gjl = mysql_query("select * from tb_gejala WHERE jenis = 'Gejala Dalam' ");	
				while ($r_gjl= mysql_fetch_array($query_gjl)){	
				echo "<tr>
						<td valign='top'><input type='checkbox' name='$r_gjl[id_gejala]'></td>
						<td>$r_gjl[gejala]</td>
					</tr>";
				}
				?>
				</table>
			</td>	
			
			<td valign="top" bgcolor="EFEFEF">
				<table class=''>
				<tr>
					<td align="center" colspan="2" bgcolor="CDCDCD">Gejala Lain</td>
				</tr>	
				<?php
				
				$query_gjl = mysql_query("select * from tb_gejala WHERE jenis = 'Gejala Lain' ");	
				while ($r_gjl= mysql_fetch_array($query_gjl)){					
				echo "<tr>
						<td valign='top'><input type='checkbox' name='$r_gjl[id_gejala]'></td>
						<td>$r_gjl[gejala]</td>
					</tr>";
				}
				?>
				</table>			
			</td>	
		</tr>
		<tr>			
			<td align="center" colspan="3"> &nbsp;</td>	
		</tr>
		<tr>			
			<td align="center" colspan="3" valign="top" bgcolor="CDCDCD"> <input type="submit" name="submit" value="Proses Diagnosa">&nbsp;</td>	
		</tr>
	</table>
	
	
	<?php
	}
	if(($submit == "Proses Diagnosa")){
	echo"DAFTAR PENYAKIT :";
	echo"<table class='tabelbox1'>";
	echo"<tr><th>ID</th>
			<th>Penyakit</th>
			<th>Nilai CF</th>
			<th>Nilai CF (%)</th>
			<th width='80'>Detail</th></tr>";
		
			$jum = 0;
			$j = 0;
			$gjl = array();
			$query = mysql_query("select * from tb_gejala");
			while ($r_gjl= mysql_fetch_array($query)){
				if($_POST[$r_gjl['id_gejala']] == "on"){
					 
					$gjl[$j] = $r_gjl['id_gejala'];
					
					$jum++;
					$j++;
				}
			}
		
			$query_pny = mysql_query("SELECT*FROM tb_penyakit");
			while($r_pny=mysql_fetch_array($query_pny)){
				$jum_gejala = 0;
				$i=1;
				
				for($j=0;$j<=$jum;$j++){
						$query_pny_gjl = mysql_query("SELECT*FROM view_penyakit_gejala where id_penyakit='".$r_pny['id_penyakit']."' and id_gejala='".$gjl[$j]."'");
						$num_row=mysql_num_rows($query_pny_gjl); 
						if($num_row){
						$r_pg=mysql_fetch_array($query_pny_gjl); 
						$b = $i-1;
						if($i == 1) $mb[$i] = $r_pg['nilai_mb'];
						else $mb[$i] = $mb[$b] + ($r_pg['nilai_mb'] * (1 - $mb[$b]));
						
						if($i == 1) $md[$i] = $r_pg['nilai_md'];
						else $md[$i] = $md[$b] + ($r_pg['nilai_md'] * (1 - $md[$b]));
						
						$cf = round($mb[$i] - $md[$i],2);
						$sen_cf = $cf * 100;
					
						$jum_gejala++;
						}
				$i++;
				}
				
				if($jum_gejala == $jum){					
					$data[] = array('cf'=>$cf,'sencf'=>$sen_cf,'id'=>$r_pny['id_penyakit'],'pny'=>$r_pny['penyakit']);
				}
				
			}
			
			if($data){
				rsort($data);
				foreach($data as $key=>$value){
				if($curr_pny == NULL)$curr_pny = $value['id'];
				if($curr_cf == NULL)$curr_cf = $value['cf'];
				if($curr_pcf == NULL)$curr_pcf = $value['sencf'];
					$id_peny = $value['id'];
					$peny = $value['pny'];
					$nil_cf = $value['cf'];
					$persen_cf = $value['sencf'];
					echo"<tr><td>$id_peny</td>
							<td>$peny</td>
							<td align=''>$nil_cf</td>
							<td align=''>$persen_cf</td>
							<td align=''><img src='img/data.png' width='15' title='detail'><a href='cetak_pdf.php?pny=".$id_peny."'target='_blank'> Detail </a></td></tr>";
				}
			}else{
				?>
			<script language="JavaScript">alert('Penyakit Tidak Ditemukan, Silahkan Coba Lagi...!');
			document.location.href='index.php?send=diagnosa';
			</script>
			<?php
			}
		
	echo"</table>";
	
		echo $str;
		echo"<div>&nbsp;</div>";
		$query_pny = mysql_query("select * from view_penyakit_solusi WHERE id_penyakit = '$curr_pny' ");		
		$r_pny= mysql_fetch_array($query_pny);
			echo "<div class='merah'>".$r_pny['penyakit']." : ".$r_pny['definisi']."</div>";
		
		echo"<div>SOLUSI :</div>";
		$query_ps = mysql_query("select * from view_penyakit_solusi WHERE id_penyakit = '$curr_pny' ");	
		
		while ($r_ps= mysql_fetch_array($query_ps)){		  
			$solusi = str_replace('#','<br>',$r_ps['solusi']);
			echo "<div class=''>".$solusi."</div>";
		}	
	
	?>
	
	<div>&nbsp;</div>
	<input type="submit" name="submit" value="Kembali">
	</form>
	<?php
	}
	?>
</body>