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
 * @package    auth_guardiankey
 * @copyright  Paulo Angelo Alves Resende
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use \core_privacy\local\metadata\collection;


defined('MOODLE_INTERNAL') || die();

class provider implements
    // This plugin has data.
    \core_privacy\local\metadata\provider,
    // This plugin has some sitewide user preferences to export.
    \core_privacy\local\request\user_preference_provider
{



public static function get_metadata(collection $collection) : collection {
 
    $collection->add_external_location_link('guardiankey.io', [
            'userid' => 'privacy:metadata:guardiankey:userid',
        ], 'privacy:metadata:guardiankey');
 
    return $collection;
}
}
