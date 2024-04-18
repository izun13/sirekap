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
$recpage = 25;
$to_page = "lihattbl";

$search = explode(";",$search);
$search0 = $search[0]; 
$search1 = $search[1]; 
$search2 = $search[2];

if($_POST["search0"]) $search0=$_POST["search0"];
if($_POST["search1"]) $search1=$_POST["search1"];
if($_POST["search2"]) $search2=$_POST["search2"];

		
?>	
		
<div><span class='judul'>Lihat Tabel : <?php echo $search0;?></span></div>

<form action='<?php echo "?send=".$to_page;?>' method='post' autocomplete="off"> 
<table>
<tr><td width="100px">Tabel </td><td>:</td>
	<td width="230px">
		<select name="search0" id="pilih">
			<option value="">Pilih Tabel</option>
			<?php
			$query_jns=mysql_query("SELECT*FROM tabel order by id asc");
				while($r_jns=mysql_fetch_array($query_jns)){
					$selected = "";
					if($r_jns["nama_tabel"] == $search0)$selected = "selected";
					echo"<option value='".$r_jns["nama_tabel"]."' $selected>".$r_jns["nama_tabel"]."</option>";			
				}
			?>			
			</select></td>
	<td><input type='submit' name='tombol' value='Tampilkan'></td>
</tr>
</table>
<?php 
$tabel = "SELECT*FROM $search0 ORDER BY id desc";
?>
<table>
<tr>
<td width="100px">Kolom </td><td>:</td>
<td width="230px">
		<select name="search1" id="pilih2">
			<option value="">Pilih Kolom</option>
			<?php
			
			$query = mysql_query($tabel);
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				//$type[$name] = mysql_field_type($query, $i);
					$selected = "";
					if($name == $search1)$selected = "selected";
					echo"<option value='".$name."' $selected>".$name."</option>";	
			}
			?>			
			</select></td>
	<td><input type='text' name='search2' size='20' value='<?php echo $search2;?>'> <input type='submit' name='tombol' value='Cari'></td>
</tr>
<?php
//}
?>	
</table>
</form>

<script type="text/javascript">
        $('#pilih').selectize({
            create: true,
            sortField: 'text'
        });
        $('#pilih2').selectize({
            create: true,
            sortField: 'text'
        });
</script>
<?php 
if (($_POST["tombol"]=="Tampilkan") or ($search0!="")){
?>
<!--<div id="border">-->
	<table class="tabelbox1">
		<?php
		// Nampilin Data	
		if(($search1 != "") and ($search2 != ""))$tabel = "SELECT*FROM $search0 WHERE $search1 LIKE '%$search2%' ORDER BY id desc";			
		$query = mysql_query($tabel);
		if(isset($_GET['page'])) $starting = $_GET['page'];		
		if(isset($_POST['page'])) $starting = ($_POST['page'])*$recpage-$recpage;
		if($starting=='') $starting = 0;
		
		$search=$search0.";".$search1.";".$search2;
		page($query,$recpage,$starting,$to_page,$search);
		
		$starting = starting();
		$recpage = recpage();
		
		echo "<tr>";
		for($i = 0; $i < mysql_num_fields($query); $i++){
			$name = mysql_field_name($query, $i);
			//$type = mysql_field_type($query, $i);
			//$size = mysql_field_len($query, $i);
			//echo $name.' - '.$type.' - '.$size.'<br>';
			echo "<th>$name</th>";
		}
		echo "</tr>";
		
		$query = mysql_query($tabel." LIMIT $starting,$recpage");
		$current = $_REQUEST["x"];
		$current1 = 0;
		
		$baris=$starting+1;		
		while ($r= mysql_fetch_array($query)){
			if($current1 == 0)$current1 = $r[id];		
			if(!$current)$current = $current1;
			$id = $r[id];
			
			if($baris % 2==0){
				echo "<tr class='cyan'>";
			}else{
				echo "<tr>";
			}
			
			//echo "<td align='center'><a href=updatetbl/$id/$starting/$search><img src=\"img/edit.png\" width='25' title='ubah'></img></a></td>";
			
			for($i = 0; $i < mysql_num_fields($query); $i++){
				$name = mysql_field_name($query, $i);
				echo "<td align=''>$r[$name]</td>";
			}
			
			echo "</tr>";
			$baris++;
		}
		?>
		
</table>

<table width='100%'>
<tr><td>
	<?php
	$page_showing = page_showing();
	echo show_navi();
	?>
</td></tr>
</table>
<!--</div>-->
<?php
}
?>
</body>