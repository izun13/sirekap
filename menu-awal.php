<?php

   $active = '';
   if(strstr($_GET['send'],"helpdesk")) $active = 'active-menu';
   echo"<li class=$active><a href='?send=entrihelpdesk'><span>Helpdesk IT</span></a></li>";
   $active = '';
   if(strstr($_GET['send'],"izinusaha")) $active = 'active-menu';
   echo"<li class=$active><a href='?send=izinusaha'><span>Perizinan Berusaha</span></a></li>";
   $active = '';
   if(strstr($_GET['send'],"nonizin")) $active = 'active-menu';
   echo"<li class=$active><a href='?send=nonizin'><span>Perizinan Non Berusaha</span></a></li>";
   $active = '';
   if(strstr($_GET['send'],"realisasi")) $active = 'active-menu';
   echo"<li class=$active><a href='?send=realisasi'><span>Realisasi Investasi</span></a></li>";
   $active = '';
   if(strstr($_GET['send'],"login")) $active = 'active-menu';
   echo"<li class=$active><a href='?send=login'><span>Login</span></a></li>";

?>
