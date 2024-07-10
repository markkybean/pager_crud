<?php
$target_path = basename( $_FILES['uploadedfile']['name']); 

echo $target_path . '<br>';
var_dump($_FILES);
echo '<br>';

if(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path)) {
    //echo "The file ".  basename( $_FILES['uploadedfile']['name']). 
    //" has been uploaded";
    //echo "<meta http-equiv='refresh' content='0;trn_circulation_add.php' />";
            
        exit(0);
} else{
    echo "There was an error uploading the file, please try again!";
    //echo "<meta http-equiv='refresh' content='0;trn_circulation_add.php' />";
            
        exit(0);
}


?>