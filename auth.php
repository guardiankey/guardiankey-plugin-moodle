<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * GuardianKEY authentication login - prevents user login.
 *
 * @package auth_guardiankey
 * @author Gesiel and Paulo Angelo
 * @license http://www.gnu.org/copyleft/gpl.html GNU Public License
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir.'/authlib.php');

define('AES_256_CBC', 'aes-256-cbc');




class auth_plugin_guardiankey extends auth_plugin_base {


    /**
     * Constructor.
     */
    public function __construct() {
        $this->authtype = 'guardiankey';
        $this->config = get_config('auth_guardiankey');
    }

    function user_authenticated_hook(&$user, $username, $password) {
        global $DB;

        $keyb64 	  = get_config('auth_guardiankey', 'key');
        $salt   = get_config('auth_guardiankey', 'salt');
        $ivb64 	  = get_config('auth_guardiankey', 'iv');
        $hashid  = get_config('auth_guardiankey', 'hashid');
        $reverse = get_config('auth_guardiankey', 'reverse');
        $timestamp = time();
        $usernamehash=md5($username.$salt);

        if(strlen($hashid)>0){
          // save userhash
          if(!$DB->record_exists('auth_guardiankey_user_hash',array('userid' => $user->id, 'userhash' => $usernamehash))){
            $userhashrecord = new stdClass();
            $userhashrecord->userid= $user->id;
            $userhashrecord->userhash= $usernamehash;
            $DB->insert_record('auth_guardiankey_user_hash', $userhashrecord, $returnid=true, $bulk=false) ;
          }

	        // Send UDP. 
          $key=base64_decode($keyb64);
          $iv=base64_decode($ivb64);
          $agent=$hashid;
          $service="Moodle";
          $ip=$_SERVER['REMOTE_ADDR'];
          $clientreverse= ($reverse==1)?  gethostbyaddr($ip) : "";
          $usernamehash;
          $authmethod="";
          $loginfailed="0";
          $ua=str_replace("'","",$_SERVER['HTTP_USER_AGENT']);
          $ua=str_replace("|","",$ua);
          $ua=substr($ua,0,100);
          $message = $timestamp."|". $agent."|". $service."|". $clientreverse."|". $ip."|". $usernamehash."|". $authmethod."|". $loginfailed."|". $ua."|";
          $cipher = openssl_encrypt($message, AES_256_CBC, $key, 0, $iv);
          $payload=$hashid."|".$cipher;
          $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
          socket_sendto($socket, $payload, strlen($payload), 0, "collector.ids-hogzilla.com", "8888");
        }
    }
 
    // Check if config exists
    // If no, register 
    // Save configs
    // check for events in the WS
    function execute_task() {

        global $DB;

	      $options= array( 'location' =>  'http://ws.ids-hogzilla.com/ws/',
	                       'uri'      =>  'http://ws.ids-hogzilla.com/ws/');

	      $client=new SoapClient(NULL,$options);

        $keyb64 = get_config('auth_guardiankey', 'key');
        if(strlen($keyb64)==0){
            // Create new Key
            $key = openssl_random_pseudo_bytes(32);
            $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(AES_256_CBC));
            $keyb64 = base64_encode($key);
            $ivb64 =  base64_encode($iv);
            $adminuser = $DB->get_record('user', array('id'=>'2'));
            $email = $adminuser->email;
            $hashid =  $client->register($email,$keyb64,$ivb64);
            $salt = md5(rand().rand().rand().rand().$hashid);

            if(strlen($hashid)>0){
                set_config('key', $keyb64, 'auth_guardiankey');
                set_config('iv', $ivb64, 'auth_guardiankey');
                set_config('hashid', $hashid, 'auth_guardiankey');
                set_config('salt', $salt, 'auth_guardiankey');
                set_config('reverse', 1, 'auth_guardiankey');
            }

        }else {
            $ivb64   = get_config('auth_guardiankey', 'iv');
            $hashid  = get_config('auth_guardiankey', 'hashid');
            $key = base64_decode($keyb64);
            $iv = base64_decode($ivb64);
            $timestamp = time();
            $timestampcipher = openssl_encrypt($timestamp, AES_256_CBC, $key, 0, $iv);
            $events= $client->listevents($hashid,$timestampcipher);

            foreach( $events as $event ){
              $this->processEvent($event);
            }

       }


    }

   function processEvent($event) {

     print_r($event);

     global $DB, $CFG;

     $userid = $DB->get_record('auth_guardiankey_user_hash', array('userhash'=>$event["userhash"]));
     $user = $DB->get_record('user', array('id'=>$userid->userid));
     $emailsubject 	 = get_config('auth_guardiankey', 'emailsubject');
     $emailtext 	  = get_config('auth_guardiankey', 'emailtext');
     $emailhtml 	  = get_config('auth_guardiankey', 'emailhtml');
     $testmode 	    = get_config('auth_guardiankey', 'test');
     $supportaddr 	= get_config('auth_guardiankey', 'supportaddr');
     //$dateformat 	  = get_config('auth_guardiankey', 'dateformat');
     //$timeformat 	  = get_config('auth_guardiankey', 'timeformat');
     $date = userdate($event["time"], get_string('strftimedatetimeshort', 'langconfig'));
     $time = userdate($event["time"], get_string('strftimetime', 'langconfig'));
/*
    [agent] => asdf
    [ip] => ip
    [ip_reverse] => 
    [city] => 
    [useragent] => 
    [system] => 
    [time] => 
    [signature] => 
    [userhash] => 
*/
      $emailhtml=str_replace("[IP]",$event["ip"],$emailhtml);
      $emailhtml=str_replace("[IP_REVERSE]",$event["ip_reverse"],$emailhtml);
      $emailhtml=str_replace("[CITY]",$event["city"],$emailhtml);
      $emailhtml=str_replace("[USER_AGENT]",$event["useragent"],$emailhtml);
      $emailhtml=str_replace("[SYSTEM]",$event["system"],$emailhtml);
      $emailhtml=str_replace("[DATE]",$date,$emailhtml);
      $emailhtml=str_replace("[TIME]",$time,$emailhtml);
      $emailhtml=str_replace("[]","",$emailhtml);
      $emailhtml=str_replace("()","",$emailhtml);
      
      $emailtext=str_replace("[IP]",$event["ip"],$emailtext);
      $emailtext=str_replace("[IP_REVERSE]",$event["ip_reverse"],$emailtext);
      $emailtext=str_replace("[CITY]",$event["city"],$emailtext);
      $emailtext=str_replace("[USER_AGENT]",$event["useragent"],$emailtext);
      $emailtext=str_replace("[SYSTEM]",$event["system"],$emailtext);
      $emailtext=str_replace("[DATE]",$date,$emailtext);
      $emailtext=str_replace("[TIME]",$time,$emailtext);
      $emailtext=str_replace("[]","",$emailtext);
      $emailtext=str_replace("()","",$emailtext);

     // Get information from table user-hash
     // Send e-mail to user

      $emailuser = new stdClass();
      $emailuser->email = $CFG->supportemail;
      $emailuser->firstname = $CFG->supportname;
      $emailuser->lastname = 'Moodle administration';
      $emailuser->username = 'moodleadmin';
      $emailuser->maildisplay = 2;
      $emailuser->alternatename = "";
      $emailuser->firstnamephonetic = "";
      $emailuser->lastnamephonetic = "";
      $emailuser->middlename = "";

  
      if($testmode != 1)
        $success = email_to_user($user, $emailuser, $emailsubject, $emailtext, $emailhtml, '', '', true);

      if(strlen(trim($supportaddr))>0){
        // Send an e-mail for the support address
        $mailer =& get_mailer();
        $result = $mailer->send($supportaddr, $emailsubject." (user $emailuser)", $emailhtml, 'quoted-printable', 1);
      }
      
      
      /*
              $message = new \core\message\message();
              $message->component = 'auth_guardiankey';
              $message->name = 'instantmessage';
              //$message->userfrom = $USER;
              $message->userto = $user;
              $message->subject = 'message subject 1';
              $message->fullmessage = 'message body';
              $message->fullmessageformat = FORMAT_MARKDOWN;
              $message->fullmessagehtml = '<p>message body</p>';
              $message->smallmessage = 'small message';
              $message->notification = '0';
              $message->contexturl = 'http://GalaxyFarFarAway.com';
              $message->contexturlname = 'Context name';
              //$message->replyto = "random@example.com";
              $content = array('*' => array('header' => ' test ', 'footer' => ' test ')); // Extra content for specific processor
              $message->set_additional_content('email', $content);
              $messageid = message_send($message);
      */

   }

    function user_login($username, $password) {
        return false;
    }

    /**
     * No password updates.
     */
    function user_update_password($user, $newpassword) {
        return false;
    }



    function prevent_local_passwords() {
        return false;
    }

    /**
     * Returns true if this authentication plugin is 'internal'.
     *
     * @return bool
     */
    function is_internal() {
        return true;
    }

    /**
     * Returns true if this authentication plugin can change the user's
     * password.
     *
     * @return bool
     */
    function can_change_password() {
        return false;
    }

    /**
     * Returns the URL for changing the user's pw, or empty if the default can
     * be used.
     *
     * @return moodle_url
     */
    function change_password_url() {
        return null;
    }

    /**
     * Returns true if plugin allows resetting of internal password.
     *
     * @return bool
     */
    function can_reset_password() {
        return false;
    }

    /**
     * Returns true if plugin can be manually set.
     *
     * @return bool
     */
    function can_be_manually_set() {
        return true;
    }




}


