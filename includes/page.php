<?php
function page($query,$recpage,$starting,$to_page,$search,$act){
global $show_navi;
global $page_showing;
global $starting;
global $recpage;

        $numrows    =   mysql_num_rows($query);
        $next       =   $starting+$recpage;
        $var        =   ((intval($numrows/$recpage))-1)*$recpage;
        $page_showing   =   intval($starting/$recpage)+1;
        $total_page =   ceil($numrows/$recpage);
 
        if($numrows % $recpage != 0){
            $last = ((intval($numrows/$recpage)))*$recpage;
        }else{
            $last = ((intval($numrows/$recpage))-1)*$recpage;
        }
        $previous = $starting-$recpage;
		$anc = "<ul id='pagination'>";
        if($previous < 0){
            $anc .= "<li class='previous-off'>First</li>";
            $anc .= "<li class='previous-off'>Previous</li>";
        }else{
            $anc .= "<li class='next'><a href='?send=$to_page///$search/$act'>First </a></li>";
            $anc .= "<li class='next'><a href='?send=$to_page//$previous/$search/$act'>Previous </a></li>";
        }
 
        ################If you dont want the numbers just comment this block###############
        $norepeat = 4;//no of pages showing in the left and right side of the current page in the anchors
        $j = 1;
        $anch = "";
        for($i=$page_showing; $i>1; $i--){
            $fpreviousPage = $i-1;
            $page = ceil($fpreviousPage*$recpage)-$recpage;
            $anch = "<li><a href='?send=$to_page//$page/$search/$act'>$fpreviousPage </a></li>".$anch;
            if($j == $norepeat) break;
            $j++;
        }
        $anc .= $anch;
        $anc .= "<li class='active'>".$page_showing."</li>";
        $j = 1;
        for($i=$page_showing; $i<$total_page; $i++){
            $fnextPage = $i+1;
            $page = ceil($fnextPage*$recpage)-$recpage;
            $anc .= "<li><a href='?send=$to_page//$page/$search/$act'>$fnextPage</a></li>";
            if($j==$norepeat) break;
            $j++;
        }
        ############################################################
        if($next >= $numrows){
            $anc .= "<li class='previous-off'>Next</li>";
            $anc .= "<li class='previous-off'>Last</li>";
        }else{
            $anc .= "<li class='next'><a href='?send=$to_page//$next/$search/$act'>Next </a></li>";
            $anc .= "<li class='next'><a href='?send=$to_page//$last/$search/$act'>Last</a></li>";
        }
		 $anc .= "<li ><div align='right' class='page'><form action='?send=$to_page/$search/$act' method='post'>
		 halaman : $page_showing dari $total_page &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		 halaman : <input type='text' size='1' name='page' value='$page_showing' class='search'> 
		 <input type='submit' name='submit' value='GO'></form></div></li>";
            $anc .= "</ul>";
        $show_navi = $anc;
 
       // $this->total = "<ul id='pagination'><li><font color='#0063e3' size='3'>Halaman : <b>$page_showing</b> dari <b>$total_page</b>. Total Records : <b>$numrows</b></li></ul>";
}

function show_navi(){
global $show_navi;

return "$show_navi";
}

function page_showing(){
global $page_showing;

return "$page_showing";
}

function starting(){
global $starting;

return "$starting";
}

function recpage(){
global $recpage;

return "$recpage";
}
?>