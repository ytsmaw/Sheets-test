<?php    
    date_default_timezone_set("America/New_York");
    //create a new project
    if(isset($_POST['newProj'])){ //check if form was submitted
        
        $input = $_POST['name']; //get input text
        $handle = fopen("activeProjects.html", "a+");
        $projectHTML =file_get_contents("activeProjectTemplate.html");
        $projectID = "proj".rand(0,1000000); //create a random project ID

        //edit the template 
        $projectHTML= str_replace("{projectID}",$projectID,$projectHTML);
        $projectHTML= str_replace("{name}",$_POST['name'],$projectHTML);
        $projectHTML= str_replace("{bgColor}",randcol(),$projectHTML);
        $projectHTML= str_replace("{firstStation}",$_POST['firstStation'],$projectHTML);
        $projectHTML= str_replace("{lastStation}",$_POST['lastStation'],$projectHTML);
        $projectHTML= str_replace("{totalMade}",$_POST['totalMade'],$projectHTML);
        $projectHTML= str_replace("{date}","Created: ".date('m/d/y h:i'),$projectHTML);

        fwrite($handle, $projectHTML);
        
        header("Location: ".$_SERVER['REQUEST_URI']);
        die();
       
    }    

    //end project
    if(isset($_POST['endProj'])){
        $activeProjectsHTML = file_get_contents("activeProjects.html");
        $start="<!--".$_POST['projectID']."-->";
        $stop="<!--/".$_POST['projectID']."-->";
        $clippedProjects = change_all_between($start, $stop, $activeProjectsHTML, '');

        $handle = fopen("activeProjects.html", "w+");
        fwrite($handle, $clippedProjects);
        
        header("Location: ".$_SERVER['REQUEST_URI']);
        die();

    }

    //change stations
    if(isset($_POST['cngStations'])){
        $activeProjectsHTML = file_get_contents("activeProjects.html");
        
        //change the first one
        $start="<!--st1".$_POST['projectID']."-->";
        $stop="<!--/st1".$_POST['projectID']."-->";
        $newStr='<!--st1'.$_POST['projectID'].'-->
            <input class="stationField" name="projectFirstStation" type="number" value="'.$_POST['projectFirstStation'].'" min=1; max=8>
            <!--/st1'.$_POST['projectID'].'-->';
        $activeProjectsHTML = change_all_between($start, $stop, $activeProjectsHTML, $newStr);
        
        //change the first one
        $start="<!--st2".$_POST['projectID']."-->";
        $stop="<!--/st2".$_POST['projectID']."-->";
        $newStr='<!--st2'.$_POST['projectID'].'-->
            <input class="stationField" name="projectLastStation" type="number" value="'.$_POST['projectLastStation'].'" min=1; max=8>
            <!--/st2'.$_POST['projectID'].'-->';
        $activeProjectsHTML = change_all_between($start, $stop, $activeProjectsHTML, $newStr);
        
        $handle = fopen("activeProjects.html", "w+");
        fwrite($handle, $activeProjectsHTML);
        
        header("Location: ".$_SERVER['REQUEST_URI']);
        die();
    }

    //delete everything in between two characters in a string
    function change_all_between($beginning, $end, $string, $replace) {
      $beginningPos = strpos($string, $beginning);
      $endPos = strpos($string, $end);
      if ($beginningPos === false || $endPos === false) {
        return $string;
      }

      $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

      return str_replace($textToDelete, $replace, $string);
    }

    function randcol(){

        $rayzie = array("0","1","2","3","4","5","6","7","8","9","A","B","C","D","E","F");
        
        $sum = 81;
        $hm = array(15, 15, 0, 0, 0, 0);
        
        while($sum > 60){
            $sum = 0;
            for($i=0;$i<6;$i++){
                $hm[$i] = rand(0,15);
                $sum = $sum + $hm[$i];
            }
        }
        $sum = Math.floor($sum / 6);
        
        $colkek = "#".$rayzie[$hm[0]].$rayzie[$hm[1]].$rayzie[$hm[2]].$rayzie[$hm[3]].$rayzie[$hm[4]].$rayzie[$hm[5]];
        return $colkek;
    }
?>