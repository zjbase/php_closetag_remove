<?php
if (isset($_GET['dir'])){  
$basedir=$_GET['dir'];
}else{
$basedir = '.';
}
checkdir($basedir);

function checkdir($basedir){
    if ($dh = opendir($basedir)) {
        while (($file = readdir($dh)) !== false) {
            if ($file != '.' && $file != '..'){
                $dirname = $basedir. DIRECTORY_SEPARATOR . $file;
                if (!is_dir($dirname)) {
                    echo "filename: $dirname ".check_close_tag($dirname)." <br>";
                }else{
                    checkdir($dirname);
                }
            }
        }
        closedir($dh);
    }
}
function check_close_tag ($filename) {
    if(substr($filename,-3)=='php'){
        $s = file_get_contents($filename);
        $l = strlen($s);
        $pos = strrpos($s,"?>");
        $pos2 = strrpos($s,"<?php");
        //var_dump($pos);
        //var_dump($pos2);

        if($pos > $pos2)
        {
            $s2 = substr($s,0,$pos);
            $s3 = substr($s,$pos+3);
            if(trim($s3)===""){
                //echo "empty";
                if(isset($_GET['w'])){
                    rewrite ($filename, $s2);
                    return ("<font color=red>found, automatically removed.</font>");                     
                }else{
                    return ("<font color=red>found</font>");
                }
            }else{
                return ("<font color=red>not pure php </font>");
            }
        }else{
            return ;
        }
    }
}


function rewrite ($filename, $data) {
    $filenum = @fopen($filename, "w");
    if($filenum){
        flock($filenum, LOCK_EX);
        fwrite($filenum, $data);
        fclose($filenum);
        return "OK";
    }else{
        return "ERROR"; 
    }
}
