<?php

namespace auth_guardiankey;
//use context_user;
defined('MOODLE_INTERNAL') || die();

class api {

    public static function user_login_failed(\core\event\user_login_failed $event) {
      //  $event = \core\event\user_login_failed::create(array('userid' => $user->id,
      // 'other' => array('username' => $username, 'reason' => $failurereason)));
        $userid = $event->objectid;

        return true;
    }

}
