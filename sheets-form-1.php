 


<html>
<head>

    <link rel="stylesheet" href="sheets-form.css">
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
    <script data-cfasync="false" src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <?php require_once('SheetsPHP.php')?>
    <title>Counter HTML Form</title>
    
    
    
</head>
<body>
    <div id="content-wrapper">
        <div id="newProjectTitle">NEW PROJECT</div>
        <div id="newProjectFormWrapper">
            <form id="newProjectForm" action="" method="post">
                <p><label for="name">Project Name:</label>
                <input id="name" name="name" type="text" value=""></p>
                <div id="sandwichedWrapper">
                    <div id="leftSideWrapper">
                        <p><label for="firstStation" class="newProjectLabel">First Station:</label>
                            <input id="firstStation" name="firstStation" type="number" value="" min=1 max=8></p>
                        <p><label for="lastStation" class="newProjectLabel">Last Station:</label>
                            <input id="lastStation" name="lastStation" type="number" value="" min=1 max=8></p>
                        <p><label for="totalMade" class="newProjectLabel">Total made:</label>
                            <input id="totalMade" name="totalMade" type="number" value=""></p>
                    </div>
                    <div id="rightSideWrapper">
                        <p><label for="numDiscs" class="newProjectLabel"># of Discs:</label>
                            <input id="numDiscs" name="numDiscs" type="number" value=1></p>
                        <p><label for="numInserts" class="newProjectLabel"># of Inserts:</label>
                            <input id="numInserts" name="numInserts" type="number" value=1></p>
                        <p><label for="size" class="newProjectLabel">Size:</label>
                            <select id="size" name="size">
                                <option value='LP'>LP</option>
                                <option value='10-inch'>10-inch</option>
                                <option value='7-inch'>7-inch</option>
                            </select></p>
                    </div>
                </div>
                <input id="newProjectSubmit" name="newProjectSubmit" type="submit" value="Create New Project">
            </form>
        </div>

        <div id="activeProjectsTitle">ACTIVE PROJECTS</div>
        <div id="activeProjects">
            <?php 
            $activeProjectsHTML = file_get_contents("activeProjects.html");
            if($activeProjectsHTML === ""){
                echo "<p id='noActiveProjects'>No active Projects!!!</p>";
            }else{
                echo $activeProjectsHTML;
            }
            ?>
        </div>
    </div>
    
    <div id="result">something has gone wrong</div>
 
    <script type="text/javascript">    
        document.getElementById('result').style.visibility = 'hidden';
        $('#result').text('everything is not necessarily fine');
        // variable to hold request
        var request;
        var phprequest;
        var serializedData;
        var stats = false;
        
        // bind to the submit event of our form
        function sendData(formData,postType){
            //alert('huh');            
            
            // abort any pending request
            if (request) {
                request.abort();
            }
            // setup some local variables
            
            //alert('no');
            // let's disable the inputs for the duration of the ajax request
            // Note: we disable elements AFTER the form data has been serialized.
            // Disabled form elements will not be serialized.
            $('#result').text('Sending data...');
            
            //change what ajax is sending based on what button was pressed
            switch(postType){
                case 'newProj':
                    serializedData = 'newProj='+formData['name']+"||"+formData['firstStation']+"||"+formData['lastStation']+"||"
                        +formData['totalMade']+"||"+formData['numDiscs']+", "+formData['numInserts']+", "+formData['size'];
                    break;
                    
                case 'endProj':
                    serializedData = 'endProj='+formData['projectName'];
                    break;
                    
                case 'cngStations':
                    serializedData = 'cngStations='+formData['projectName']+"||"
                        +formData['projectFirstStation']+"||"+formData['projectLastStation'];
                    break;
                           }
            
            // fire off the request to /form.php
            request = $.ajax({
                url: "https://script.google.com/macros/s/AKfycbxv4wFDp2dwwAG9Wx51g7AUy1F_ZEH1UvkKBwbQiBBQtvLtleI/exec",
                type: "get",
                data: serializedData
            });
            
            
            //alert('request made');
            // callback handler that will be called on success
            request.done(function (response, textStatus, jqXHR){
                // log a message to the console
                $('#result').text('AJAX done, now for PHP');
                console.log("Hooray, it worked!");
                
                
                switch(postType){
                    case 'newProj':
                        phprequest = $.ajax({
                            url: 'SheetsPHP.php',
                            type: 'post',
                            data: {
                                newProj: "start",
                                name: formData['name'],
                                firstStation: formData['firstStation'],
                                lastStation: formData['lastStation'],
                                totalMade: formData['totalMade']
                            }
                        });
                        break;
                        
                    case 'endProj':
                        phprequest = $.ajax({
                            url: 'SheetsPHP.php',
                            type: 'post',
                            data: {
                                endProj: "start",
                                projectID: formData['projectID']
                               }
                        });
                        break;
                        
                    case 'cngStations':
                        phprequest = $.ajax({
                            url: 'SheetsPHP.php',
                            type: 'post',
                            data: {
                                cngStations: "start",
                                projectID: formData['projectID'],
                                projectFirstStation: formData['projectFirstStation'],
                                projectLastStation: formData['projectLastStation']
                               }
                        });
                        break;
                               }
                
                $('#result').text('php done');
                location.reload(true);
            });

            // callback handler that will be called on failure
            request.fail(function (jqXHR, textStatus, errorThrown){
                // log the error to the console
                console.error(
                    "The following error occured: "+
                    textStatus, errorThrown
                );
            });

            // callback handler that will be called regardless
            // if the request failed or succeeded
            request.always(function () {
                // reenable the inputs
                $inputs.prop("disabled", false);
            });

            // prevent default posting of form
            event.preventDefault();
            
        }
        
        $(document).ready(function( $ ) {
            //new project jquery
            $('#newProjectForm').submit(function(event) {
                var dataArray = $(this).serializeArray(); //get the data from the form
                var dataObj = {};
                $(dataArray).each(function(i, field){
                  dataObj[field.name] = field.value;   //put it in a way that's easy to read
                });
                sendData(dataObj, "newProj");  //send it to the sheet and PHP
            });
            
            //end project jquery
            $('.endProjForm').submit(function(event) {
                var dataArray = $(this).serializeArray();
                var dataObj = {};
                $(dataArray).each(function(i, field){
                  dataObj[field.name] = field.value;
                });
                sendData(dataObj, "endProj");
            });
            
            $('.changeStationsForm').submit(function(event) {
                var dataArray = $(this).serializeArray();
                var dataObj = {};
                $(dataArray).each(function(i, field){
                  dataObj[field.name] = field.value;
                });
                sendData(dataObj, "cngStations");
            });

            $('#result').text('javascript OK');
            //$('#newProjectForm').submit(function(event) {alert('wow');});
        });
        
        
        $(document).keydown(function(e){
            switch(e.keyCode) {
                case 69:
				    stats = !stats;
                    break;
                            }
        if (stats){ 	
            document.getElementById('result').style.visibility = 'visible';
        }else{
            document.getElementById('result').style.visibility = 'hidden';
        }
        });
        
    </script>
    
    
</body>
</html>
