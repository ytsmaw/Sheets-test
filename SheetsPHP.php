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
        $projectHTML= str_replace("{bgColor}","blue",$projectHTML);
        $projectHTML= str_replace("{firstStation}",$_POST['firstStation'],$projectHTML);
        $projectHTML= str_replace("{lastStation}",$_POST['lastStation'],$projectHTML);
        $projectHTML= str_replace("{totalMade}",$_POST['totalMade'],$projectHTML);
        $projectHTML= str_replace("{date}","Created: ".date('m/d/y h:i'),$projectHTML);

        fwrite($handle, $projectHTML);
        
        header("Location: ".$_SERVER['REQUEST_URI']);
        die();
        /*
        $data = $_POST['name']."||".$_POST['firstStation']."||".$_POST['lastStation']."||".$_POST['totalMade']."||"
            .$_POST['numDiscs']." Discs, ".$_POST['numInserts']." Inserts, ".$_POST['size'];
        
        
        echo '<script type="text/javascript">
            sendData("newProj='.$data.'");
            </script>'
            .$data;
        
        
        $url = 'https://script.google.com/macros/s/AKfycbxv4wFDp2dwwAG9Wx51g7AUy1F_ZEH1UvkKBwbQiBBQtvLtleI/exec';

        $ch = curl_init( $url );
        curl_setopt( $ch, CURLOPT_POST, 1);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt( $ch, CURLOPT_HEADER, 0);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);

        $response = curl_exec( $ch );
        */
    }    

    //end project
    if(isset($_POST['endProjButton'])){
        $activeProjectsHTML = file_get_contents("activeProjects.html");
        $start="<!--".$_POST['projectID']."-->";
        $stop="<!--/".$_POST['projectID']."-->";
        $clippedProjects = delete_all_between($start, $stop, $activeProjectsHTML);

        $handle = fopen("activeProjects.html", "w+");
        fwrite($handle, $clippedProjects);

    }

    //delete everything in between two characters in a string
    function delete_all_between($beginning, $end, $string) {
      $beginningPos = strpos($string, $beginning);
      $endPos = strpos($string, $end);
      if ($beginningPos === false || $endPos === false) {
        return $string;
      }

      $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

      return str_replace($textToDelete, '', $string);
    }

?>