<?php 

  //  CONSIDER THIS SECURITY MEASURE ON WHERE THE
  //  FILE CAN ONLY BE CALLED VIA AJAX AND FROM SPECIFIC LOCATIONS
  // 
  // if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_REFERER']!="http://your-site.com/path/to/chat.js") {
  //   die();
  // } 

?>
  
  
<?php

    function getfile($f) {
    
    	if (file_exists($f)) {
            $lines = file($f);
        }	
        
        return $lines; 
        
    }
    
    function getlines($fl){
          return count($fl);	
    }
    $nickname = htmlentities(strip_tags($_GET['nickname']), ENT_QUOTES);
    $state = htmlentities(strip_tags($_GET['state']), ENT_QUOTES);
    $file = htmlentities(strip_tags($_GET['file']), ENT_QUOTES);
    			
    $finish = time() + 50;
    $count = getlines(getfile($file));
    
    while ($count <= $state) {
    
        $now = time();
        usleep(10000);
        
        if ($now <= $finish) {
            $count = getlines(getfile($file));
        } else {
            break;	
        }  
         
    }		 
    
    if ($state == $count) {

        $log['state'] = $state;
        $log['t'] = "continue";
        
        } else {
        
        $text= array();
        $log['state'] = $state + getlines(getfile($file)) - $state;
        
        foreach (getfile($file) as $line_num => $line) {
        if ($line_num >= $state) {
        $line = explode("~\t~", $line);
        if(substr($line[1], 0, 1)!='@')
        $text[] = ''.$line[0].''.$line[1];
        elseif($line[0]==$nickname || substr($line[1], 0, strlen('@'.$nickname))=='@'.$nickname)
        $text[] = ''.$line[0].' '.$line[1].'';
        }
        
        $log['text'] = $text; 
        }
        }
        
        echo json_encode($log);	
        
 ?>