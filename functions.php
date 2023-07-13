<?php include_once "./objects/dbClass.php"; ?>
<?php
    function escape_string($string){

        global $connection;
    
        return mysqli_real_escape_string($connection, $string);
    
    
    }

    function query($sql) {

        global $connection;
        
        return mysqli_query($connection, $sql);
        
        
    }

    function confirm($result){

        global $connection;
        
        if(!$result) {
        
        die("QUERY FAILED " . mysqli_error($connection));
        
        
        }
    }

    function redirect($location){

        return header("Location: $location ");
        
        
    }

    function fetch_array($result){

        return mysqli_fetch_array($result);
        
        
    }

    function set_message($msg){

        if(!empty($msg)) {
        
        $_SESSION['message'] = $msg;
        
        } 
        else {
        
        $msg = "";
        
        
        }
        
        
    }
?>