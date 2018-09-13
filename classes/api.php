<?php
namespace auth_guardiankey;

use auth_plugin_guardiankey;

// require_once(dirname(__FILE__).'/../auth.php');


//use context_user;
defined('MOODLE_INTERNAL') || die();

class api {

    public static function user_login_failed(\core\event\user_login_failed $event) {
        global $DB;
        
          //  $event = \core\event\user_login_failed::create(array('userid' => $user->id,
          // 'other' => array('username' => $username, 'reason' => $failurereason)));
          //$userid = $event->objectid;
        $userid = $event->userid;
        $username = $event->other['username'];
        
        $authobj = new auth_plugin_guardiankey();
        $user="";
        $user = $DB->get_record('user', array('id'=>$userid));
        
        $authobj->user_authenticated_hook($user, $username, "", 1);
        return true;
    }

}
