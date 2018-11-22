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
 * Admin settings and defaults.
 *
 * @package    auth_guardiankey
 * @copyright  Paulo Angelo Alves Resende
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {

    require_once($CFG->dirroot.'/lib/outputlib.php');

    $subjectdefault=new lang_string('auth_guardiankey_emailsubject_default', 'auth_guardiankey');
    $emailtextdefault=new lang_string('auth_guardiankey_emailtext_default', 'auth_guardiankey');
    $emailhtmldefault=new lang_string('auth_guardiankey_emailhtml_default', 'auth_guardiankey');

    // Introductory explanation.
    $settings->add(new admin_setting_heading('auth_guardiankey/pluginname', '',
                   new lang_string('auth_guardiankeydescription', 'auth_guardiankey')));

    $settings->add(new admin_setting_configtext('auth_guardiankey/hashid',
                new lang_string('auth_guardiankey_hashid_key', 'auth_guardiankey'),
                new lang_string('auth_guardiankey_hashid',     'auth_guardiankey'), '', PARAM_RAW_TRIMMED));
    
    $settings->add(new admin_setting_configtext('auth_guardiankey/organizationId',
                new lang_string('auth_guardiankey_orgid_key', 'auth_guardiankey'),
                new lang_string('auth_guardiankey_orgid',     'auth_guardiankey'), '', PARAM_RAW_TRIMMED));
    
    $settings->add(new admin_setting_configtext('auth_guardiankey/authGroupId',
                new lang_string('auth_guardiankey_authgroupid_key', 'auth_guardiankey'),
                new lang_string('auth_guardiankey_authgroupid',     'auth_guardiankey'), '', PARAM_RAW_TRIMMED));
    
    $settings->add(new admin_setting_configtext('auth_guardiankey/key',
                new lang_string('auth_guardiankey_key_key', 'auth_guardiankey'),
                new lang_string('auth_guardiankey_key',     'auth_guardiankey'), '', PARAM_RAW_TRIMMED));
    $settings->add(new admin_setting_configtext('auth_guardiankey/iv',
                new lang_string('auth_guardiankey_iv_key', 'auth_guardiankey'),
                new lang_string('auth_guardiankey_iv',     'auth_guardiankey'), '', PARAM_RAW_TRIMMED));
    
    $settings->add(new admin_setting_configtext('auth_guardiankey/salt',
                new lang_string('auth_guardiankey_salt_key', 'auth_guardiankey'),
                new lang_string('auth_guardiankey_salt',     'auth_guardiankey'), '', PARAM_RAW_TRIMMED));
    
    $settings->add(new admin_setting_configtext('auth_guardiankey/service',
                new lang_string('auth_guardiankey_service_key', 'auth_guardiankey'),
                new lang_string('auth_guardiankey_service',     'auth_guardiankey'), '', PARAM_RAW_TRIMMED));
    
    $settings->add(new admin_setting_configselect('auth_guardiankey/reverse',
                new lang_string('auth_guardiankey_reverse_key', 'auth_guardiankey'),
                new lang_string('auth_guardiankey_reverse',     'auth_guardiankey'), 1, array(new lang_string('no') ,new lang_string('yes')  )));

    /* Email */
    $settings->add(new admin_setting_heading('auth_guardiankey/emailsettingheader', '',
                   new lang_string('auth_guardiankeyemailsettingheader', 'auth_guardiankey')));
    $settings->add(new admin_setting_configtext('auth_guardiankey/emailsubject',
                new lang_string('auth_guardiankey_emailsubject_key', 'auth_guardiankey'),
                new lang_string('auth_guardiankey_emailsubject',     'auth_guardiankey'), $subjectdefault, PARAM_RAW_TRIMMED));
    $settings->add(new admin_setting_configtextarea('auth_guardiankey/emailtext',
                new lang_string('auth_guardiankey_emailtext_key', 'auth_guardiankey'),
                new lang_string('auth_guardiankey_emailtext',     'auth_guardiankey'), $emailtextdefault));
    $settings->add(new admin_setting_confightmleditor('auth_guardiankey/emailhtml',
                new lang_string('auth_guardiankey_emailhtml_key', 'auth_guardiankey'),
                new lang_string('auth_guardiankey_emailhtml',     'auth_guardiankey'), $emailhtmldefault ));

    
    
    $settings->add(new admin_setting_configselect('auth_guardiankey/active',
        new lang_string('auth_guardiankey_active_key', 'auth_guardiankey'),
        new lang_string('auth_guardiankey_active',     'auth_guardiankey'), 1, array(new lang_string('no')  ,new lang_string('yes') )));
    
    $settings->add(new admin_setting_configselect('auth_guardiankey/test',
                new lang_string('auth_guardiankey_test_key', 'auth_guardiankey'),
                new lang_string('auth_guardiankey_test',     'auth_guardiankey'), 1, array(new lang_string('no')  ,new lang_string('yes') )));
    
    $settings->add(new admin_setting_configtext('auth_guardiankey/supportaddr',
                new lang_string('auth_guardiankey_supportaddr_key', 'auth_guardiankey'),
                new lang_string('auth_guardiankey_supportaddr',     'auth_guardiankey'), "", PARAM_RAW_TRIMMED));


}
