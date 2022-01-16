<?php
print<<<END
    <div class="Login">
<div style="height: auto; width:250px;border: solid 1px black;font: normal 11px verdana;background:#B9B9B9;">
END;
        start('o');
 	$t_user= $_SESSION['user']; 
print<<<END
        	Current user: <b> $t_user </b> 
END;
        stop('o'); 
print<<<END
	 	<br>
END;
        start('u'); 
print<<<END
        	<i>Status: Standard</i>
END;
        stop('u');

        start('m');
print<<<END
        	<i>Status: Moderator</i>
END;
        stop('m'); 

        start('a'); 
print<<<END
        	<i>Status: Administrator</i><br><a href="adminuser.php">Overview of user</a><br>
        	<a href="ucountedit.php">Post Options</a><br>
END;
        stop('a'); 
        
        start('o');    
     
if(isset($_SESSION['user'])){
					if(CHECK_FREEZE_U($_SESSION['user'])){
											echo "<font color=\"red\">SUSPENDED</font>";}}
             echo "<form action=\"";



?>
<?php echo url; ?>
<?php 	$t_user= $_SESSION['user']; 
print<<<END
	" method="post">
            <input type="hidden" name="out" value="1">
            <input type="submit" value="logout">
            </form>
END;
        stop('o');
        
        start('f');
print<<<END
        <form action="
END;
?>
<?php echo url; ?>
<?php 
print<<<END
	" method="post">
            <table width=200 border=0><tr><td width=50>
            name:
            </td><td>
            <input type="text" name="sn">
            </td></tr><tr><td>
            password:
            </td><td>
            <input type="password" name="pw">
            </td></tr><tr><td colspan=2>
	     Remember Me: <input type="checkbox" name="remember" value="true">
            <input type="submit" value="submit"><br>
	     <a href="lostpw.php">Lost Password</a>
            </td></tr></table>
            </form>
END;
        stop('f'); 
print<<<END
	</div>
    </div>
END;
?>