<?php
	include('/var/www/twiverse.php');

	try{
		mysql_start();
		$set = mysql_fetch_assoc(mysql_throw(mysql_query("select stamp_height from user where id=".$_SESSION['twitter']['id'])));
		mysql_close();

		$filename = $_GET['stamp'];
		//$filename = 'DSG2cHQWkAYCk5h.png';
		exec('curl https://pbs.twimg.com/media/'.$filename.' > /tmp/'.$filename);
		`sync`;
		exec('mogrify -trim /tmp/'.$filename);
		`sync`;
                $imgsize = getimagesize(/tmp/'.$filename);
                if ($imgsize[0]*$imgsize[1] < 64*64){
			exec('python imgread.py /tmp/'.$filename);
			`sync`;
			exec('blender --background --python genstamp.py /tmp/'.$filename.' '.$set['stamp_height']);
			`sync`;
			header('Content-Type: application/force-download');
			header('Content-Length: '.filesize('/tmp/'.$filename));
			header('Content-Disposition: attachment; filename="'.pathinfo($filename)['filename'].'.stl"');
			readfile('/tmp/'.$filename);
		}else{
			echo 'ã'ãã®ã¹ã¿ã³ãã®3Dãã¼ã 'ãã®ã¹ã¿ã³ãã®3Dãã¼ã¿ã¯åºåã§ãã¾ããã'
		}
		unlink('/tmp/'.$filename);
	}catch(Exception $e){
		catch_default($e);
	}
?>
