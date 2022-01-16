<?php
             
require('config.php');

      if( !isset($_SESSION['online']) ) //if cookie isnt set, set to 0.
          {
     		if(  isset($_COOKIE['online'])  &&  $_COOKIE['online']!='o' )
			{ $_SESSION['online']= $_COOKIE['online'];
			 $_SESSION['user']=$_COOKIE['user'];}
		else{ $_SESSION['online']='o'; }
		//setcookie("online","x" , time()+1000);
          }
      else
          {
          if( $_SESSION['online']!='o' )  //if it is set,and is logged on(a or u)
               {
               if( isset($_POST['out']) )
                    {
                     if ( $_POST['out']=="1" ) //and if we got a logout command -> set cookie to 'o'
                          {
				$_SESSION['online']='o';
                            $_SESSION['user']="";
				if(!isset($_POST['remember'])){setcookie("online","" , time()-100000);}
				if(!isset($_POST['remember'])){setcookie("user", "", time()-100000);}
				$_SESSION['remember']=="false";
                            unset($_POST['out']);
                          }
                     }
                }
           else
               {
               if(isset($_POST['sn']) && isset($_POST['pw']) ) //if cookie set, and pw & sn were entered
                      {
                     if($_POST['remember']=="true"){$tval="true";}
			else{$val="false";}

			    mysql_connect(host,sn,pw);
			    mysql_select_db(db);
                        $uid=$_POST['sn'];
                        $pw=$_POST['pw'];
                        if(mysql_num_rows(mysql_query("SELECT username FROM user WHERE username = '$uid'")))
                        {
                            if(mysql_num_rows(mysql_query("SELECT password FROM user WHERE username = '$uid' AND password='$pw'")))
                            {
                                  if(mysql_num_rows(mysql_query("SELECT privilege FROM user WHERE username = '$uid' AND 					privilege='a'")))
                                  { 
                                         $_SESSION['online']='a';
                                         $_SESSION['user']=$_POST['sn'];
					      $_SESSION['remember']=$tval;	
                                  }                  
                                  if(mysql_num_rows(mysql_query("SELECT privilege FROM user WHERE username = '$uid' AND 					         privilege='m'")))
                                  { 
                                         $_SESSION['online']='m';
                                         $_SESSION['user']=$_POST['sn'];
					      $_SESSION['remember']=$tval;	
						
                                  }   
				  if(mysql_num_rows(mysql_query("SELECT privilege FROM user WHERE username = '$uid' AND 					         privilege='u'")))
                                  { 
                                         $_SESSION['online']='u';
                                         $_SESSION['user']=$_POST['sn'];
					      $_SESSION['remember']=$tval;	
                                  }  
                
                            }
                            else
                            {
                                echo "Invalid password<br>";
                                $_SESSION['online']='o';
                            }                            
                        }
                        else
                        {
                            echo "Invalid user name<br>"; 
                            $_SESSION['online']='o';
                        }                          
                        mysql_close();                              
                            }}}
                                          
      function start($p)
           {  

		if(isset($_COOKIE['online'])){$var=$_COOKIE['online'];}else{$var="o";}

	       if($p=='f')
	       {
			if($_SESSION['online']!='o' || $var!='o'){echo "<!-- ";}
		 }
	       else
		{
	           if($p=="o")
			{
			if(
			  !($_SESSION['online']=='u' || $_SESSION['online']=='a' || $_SESSION['online']=='m')
			    &&
	   		  !($var=='u' || $var=='a' || $var=='m')
			   )
              	    {echo "<!-- ";} 	
			}
               else
		   {
		   if($_SESSION['online']!=$p && $var!=$p)
               	   {echo "<!-- ";}
		   }
		}	  
           }

      function stop($p)
           {
		if(isset($_COOKIE['online'])){$var=$_COOKIE['online'];}else{$var="o";}

		if($p=='f'){if($_SESSION['online']!='o' ||  $var!='o'){echo "-->";}}
	       else{
	       if($p=="o")
			{
			  if(
			     !($_SESSION['online']=='u' || $_SESSION['online']=='a' || $_SESSION['online']=='m')
			    &&
	   		     !($var=='u' || $var=='a' || $var=='m')
			     )
              		  {echo "-->";} 	
			}
               else
		   {
		   if($_SESSION['online']!=$p && $var!=$p)
               	   {echo "--> ";}
		   }
		   }
           }  
                   
      ?>
