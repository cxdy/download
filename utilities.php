<?php
function check_empty_fields($required_fields_array){
    //initialize an array to store error messages
    $form_errors = array();
    //loop through the required fields array snd popular the form error array
    foreach($required_fields_array as $name_of_field){
        if(!isset($_POST[$name_of_field]) || $_POST[$name_of_field] == NULL){
            $form_errors[] = $name_of_field . " is a required field";
        }
    }
    return $form_errors;
}

/**
 * @param $fields_to_check_length, an array containing the name of fields
 * for which we want to check min required length e.g array('username' => 4, 'email' => 12)
 * @return array, containing all errors
 */
function check_min_length($fields_to_check_length){
    //initialize an array to store error messages
    $form_errors = array();
    foreach($fields_to_check_length as $name_of_field => $minimum_length_required){
        if(strlen(trim($_POST[$name_of_field])) < $minimum_length_required && $_POST[$name_of_field] != NULL){
            $form_errors[] = $name_of_field . " is too short, must be {$minimum_length_required} characters long";
        }
    }
    return $form_errors;
}

/**
 * @param $form_errors_array, the array holding all
 * errors which we want to loop through
 * @return string, list containing all error messages
 */
function show_errors($form_errors_array){
    $errors = "<p><ul style='color: red;'>";

    //loop through error array and display all items in a list
    foreach($form_errors_array as $the_error){
        $errors .= "<li> {$the_error} </li>";
    }
    $errors .= "</ul></p>";
    return $errors;
}

/**
 * @param $page, redirect user to page specified
 */
function redirectTo($page){
    header("Location: {$page}.php");
}
/**
 * @param $required_fields_array, n array containing the list of all required fields
 * @return array, containing all errors
 */
function getRandomBytes($nbBytes = 32)
{
    $bytes = openssl_random_pseudo_bytes($nbBytes, $strong);
    if (false !== $bytes && true === $strong) {
        return $bytes;
    }
    else {
        throw new \Exception("Unable to generate secure token from OpenSSL.");
    }
}

function _token(){

    if(!isset($_SESSION['token'])){
        $randonToken = base64_encode(openssl_random_pseudo_bytes(32));
        return $_SESSION['token'] = $randonToken;
    }

    return $_SESSION['token'];
}

function validate_token($requestToken){
    if(isset($_SESSION['token']) && $requestToken === $_SESSION['token']){
        unset($_SESSION['token']);

        return true;
    }

    return false;
}

?>
