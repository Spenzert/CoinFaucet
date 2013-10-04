<?php
/* 
CoinFaucet Project 
Based on code.google.com/p/btcfaucet by Spenzert and KrusherPT/etsoberano
Current development by Spenzert
-----------------------------------------------------------------------------------
CoinFaucet Project is licensed under a MIT License
-----------------------------------------------------------------------------------
Copyright (c) 2013 Spenzert

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

You should always put the credits of the original creator somewhere in 
your project(s) that are based of this project. Credits on source-code 
are enough, but visible public credits are appreciated.
-----------------------------------------------------------------------------------
*/
?>
<html>
  <body>
  <center>
    <form action="" method="post">
	Bitcoin Address: <input type="text" name="address" style="width: 300px;" />
	<p><p/>
	<p><p/>

<?php

$username = "DB_USERNAME";
$password = "DB_PASSWORD";
$hostname = "DB_HOST"; 

//connection to the database
$dbhandle = mysql_connect($hostname, $username, $password)
 or die("Unable to connect to MySQL");
// echo "Connected to MySQL<br>";

//select a database to work with
$selected = mysql_select_db("DB_DATABASE", $dbhandle)
  or die("Could not select DB_DATABASE");
  
// Purge records
mysql_query("DELETE FROM ip_table WHERE access_date < DATE_SUB(NOW(), INTERVAL 1440 MINUTE)");

$ip = mysql_real_escape_string($_SERVER['REMOTE_ADDR']);
$result = mysql_query("SELECT ip FROM ip_table WHERE ip = '$ip'");
if($result && mysql_num_rows($result) > 0){
  die("You already received your free Bitcoins today!");
} 
else {
// $result = mysql_query("INSERT INTO ip_table (ip, access_date) VALUES ('$ip', NOW())");
  
require_once('recaptchalib.php');

// Get a key from https://www.google.com/recaptcha/admin/create
$publickey = "PUBLIC_KEY";
$privatekey = "PRIVATE_KEY";

# the response from reCAPTCHA
$resp = null;
# the error code from reCAPTCHA, if any
$error = null;

# was there a reCAPTCHA response?
if ($_POST["recaptcha_response_field"]) {
        $resp = recaptcha_check_answer ($privatekey,
                                        $_SERVER["REMOTE_ADDR"],
                                        $_POST["recaptcha_challenge_field"],
                                        $_POST["recaptcha_response_field"]);

        if ($resp->is_valid) {
                $file = 'pending.txt';
		        $address = $_POST["address"]."\n";
		        $fh = fopen($file, 'a') or die("can't open file");
		        fwrite($fh, $address);
		        fclose($fh);
                echo "Soon you will receive your Bitcoins!";
                $result = mysql_query("INSERT INTO ip_table (ip, access_date) VALUES ('$ip', NOW())");
        } else {
                echo "<!-- Captcha Challenge Failed -->";
                $error = $resp->error;
        }
}
echo recaptcha_get_html($publickey, $error);
}

?>


    <br/>
	
    <input type="submit" value="Send Bitcoins" />
    </form>
	</center>
  </body>
</html>
