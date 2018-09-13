<?php

// List of observers.
$observers = [
    [
        'eventname'   => '\core\event\user_login_failed',
        // auth_guardiankey\task\sync_task  <- funca
        // auth_guardiankey\api::user_login_failed 
        'callback'    => '\auth_guardiankey\api::user_login_failed',
    ],
];

