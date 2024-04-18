<?php
// Include file class.ezpdf dalam folder fungsiPDF
include ('ezpdf/class.ezpdf.php');
	//require_once "../tanggal.php";
// Koneksi ke database dan tampilkan datanya
include"includes/koneksi.php";
$x = $_REQUEST["pny"];
	
    $pdf = new Cezpdf('A5','potrait');

    // Set margin dan font
    $pdf->ezSetCmMargins(1.5, 1.25, 1.25, 1.25);
    $pdf->selectFont('ezpdf/fonts/Times-Roman.afm');
    //$all = $pdf->openObject();

    // Tampilkan logo
    //$pdf->setStrokeColor(0, 0, 0, 1);
    //$pdf->addJpegFromFile('logo.jpg',20,800,69);

    // Teks di tengah atas untuk judul header
	//addImage(&$img,$x,$y,$w=0,$h=0,$quality=75)
	//$img = "../../image/logo.png";
	//$pdf->addImage($img,100,100);
	//$pdf->addPngFromFile($img,50,780,60,30);
	//$pdf->ezimage('../../image/logo.png',5,60,'none','left');
    //$pdf->addText(120, 790, 14,'<b>UNIMA 87,6fm RADIO</b>');
    //$pdf->addText(120, 780, 10,'Jl. Mayjend. Bambang Soegeng, Mertoyudan, Magelang 56172.');
	// Garis
    //$pdf->line(50, 775, 550, 775);
        
	
	
    // Garis bawah untuk footer
    //$pdf->line(10, 50, 590, 50);
    // Teks kiri bawah
    //$pdf->addText(30,34,8,'Dicetak tgl:' . date( 'd-m-Y, H:i:s'));

    //$pdf->closeObject();

    // Tampilkan object di semua halaman
    //$pdf->addObject($all, 'all');
	
	//$pdf->ezimage("../../images/kotak.png",-10,380,'none',-5);
	
	$pdf->ezText("<b><u>DIAGNOSIS PENYAKIT</u></b>",14,array('justification'=>'center'));
	$pdf->ezSetDy(-10,'makeSpace');
    // Query untuk merelasikan tabel
    $sql_diag = mysql_query("SELECT*FROM tb_penyakit where id_penyakit='$x'");
    $r_diag = mysql_fetch_array($sql_diag);
	
					
	$pdf->ezSetDy(-5,'makeSpace');
		
    $pny[1]['a']="<b>Penyakit";
	$pny[1]['b']=':';
	$pny[1]['c']=$r_diag['penyakit']."</b>";			
    $pny[2]['a']="Definisi";
	$pny[2]['b']=':';
	$pny[2]['c']=$r_diag['definisi'];						
	$pdf->ezTable($pny,'', '', 
				array(	'shaded'=>0,'showLines'=>0,'showHeadings'=>0,'fontSize'=>12,'titleFontSize'=>11,'xPos'=>0,'xOrientation'=>'right','rowGap'=>0,
						'cols'=>array('c'=>array ( 'width'=>290, 'justification'=>'left'))
				) );
	
	$pdf->ezSetDy(-5,'makeSpace');
	$pdf->ezText("<b>Gejala</b>",12,array('justification'=>'left'));
		$query_pg = mysql_query("select * from view_penyakit_gejala WHERE id_penyakit = '$r_diag[id_penyakit]' ");	
		$i = 1;
		while ($r_pg= mysql_fetch_array($query_pg)){
			$gjl[$i]['a'] = $i.".";
			$gjl[$i]['b'] = $r_pg['gejala'];
			$i++;
		}						
	$pdf->ezTable($gjl,'', '', 
				array(	'shaded'=>0,'showLines'=>0,'showHeadings'=>0,'fontSize'=>12,'titleFontSize'=>11,'xPos'=>0,'xOrientation'=>'right','rowGap'=>0,
						'cols'=>array('b'=>array ( 'width'=>335, 'justification'=>'left'))
				) );
					
	$pdf->ezSetDy(-5,'makeSpace');
	$pdf->ezText("<b>Solusi</b>",12,array('justification'=>'left'));
		$query_ps = mysql_query("select * from view_penyakit_solusi WHERE id_penyakit = '$r_diag[id_penyakit]' ");	
		$i = 1;
		while ($r_ps= mysql_fetch_array($query_ps)){
			/*$sls[$i]['a'] = $i.".";*/
			$sls[$i]['b'] = str_replace('#','',$r_ps['solusi']);
			$i++;
		}			
	$pdf->ezTable($sls,'', '', 
				array(	'shaded'=>0,'showLines'=>0,'showHeadings'=>0,'fontSize'=>12,'titleFontSize'=>11,'xPos'=>0,'xOrientation'=>'right','rowGap'=>0,
						'cols'=>array('b'=>array ( 'width'=>335, 'justification'=>'left'))
				) );
	
    // Penomoran halaman
    //$pdf->ezStartPageNumbers(320, 15, 8);
    $pdf->ezStream();

?>