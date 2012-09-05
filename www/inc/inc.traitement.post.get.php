<?php
	function filterParameters($array) {
       		$allowed = '<p><ul><li><b><strong>';
            // Check if the parameter is an array
            if(is_array($array)) {
                // Loop through the initial dimension
                foreach($array as $key => $value) {
                    // Check if any nodes are arrays themselves
                    if(is_array($array[$key]))
                        // If they are, let the function call itself over that particular node
                        $array[$key] = filterParameters($array[$key]);
               
                    // Check if the nodes are strings
                    if(is_string($array[$key]))
                        // If they are, perform the real escape function over the selected node
                        $array[$key] = strip_tags($array[$key], $allowed);
                }           
            }
            // Check if the parameter is a string
            if(is_string($array))
                // If it is, perform a  mysql_real_escape_string on the parameter
                $array = mysql_real_escape_string($array);
           
            // Return the filtered result
            return $array;
       
        }
	
	$_POST = filterParameters($_POST);
	$_GET = filterParameters($_GET);
?>