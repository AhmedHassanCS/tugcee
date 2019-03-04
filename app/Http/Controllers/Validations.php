<?php 

namespace App\Http\Controllers;

class Validations
{
    protected static $error='';
    static function error()
    {
        global $error;
        return $error;
    }
    static function validate_username($username)
    {
        global $error;
       if (!preg_match("/^[a-zA-Z0-9]*$/",$username)) {
            $error="Invalid username: username can only contain letters numbers and periods '.'";
            return false;
        }
        else return true;

    }

    static function validate_email($email)
    {
        global $error;
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error="Wrong email address!";
            return false;
        }
        else return true;
    }

    static function validate_name($name)
    {
        global $error;
       if (!preg_match("/^[a-zA-Z '-]+$/",$name)) {
            $error="Invalid Name";
            return false;
        }
        else return true;

    }

    static function validate_password($pass)
    {
        global $error;
            if (strlen($pass) < 8) {
                $error = "Password Must Contain At Least 8 Characters!";
                return false;
            }
            elseif(!preg_match("#[0-9]+#",$pass)) {
                $error = "Password Must Contain At Least 1 Number!";
                return false;
            }
            elseif(!preg_match("#[A-Z]+#",$pass)) {
                $error = "Your Password Must Contain At Least 1 Capital Letter!";
                return false;
            }
            elseif(!preg_match("#[a-z]+#",$pass)) {
                $error = "Your Password Must Contain At Least 1 Lowercase Letter!";
                return false;
            }
            else return true;
        
    }

    static function validate_phone($phone)
    {
        global $error;
       if (!preg_match("/^[0-9]+$/",$phone)) {
            $error="Invalid phone number!";
            return false;
        }
        else return true;

    }
}