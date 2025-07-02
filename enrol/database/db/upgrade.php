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
 * Database enrolment plugin upgrade.
 *
 * @package    enrol_database
 * @copyright  2011 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

function xmldb_enrol_database_upgrade($oldversion) {
    global $DB;

    if ($oldversion < 2025070300) {
        // Remove duplicated enrolment records, keeping only the one with the highest ID.
        $sql = "SELECT *
                  FROM {enrol} e1
                 WHERE EXISTS (
                    SELECT *
                      FROM {enrol} e2
                     WHERE e1.courseid = e2.courseid
                       AND e1.id > e2.id
                       AND e2.enrol = 'database'
                    )";
        $todelete = $DB->get_recordset_sql($sql);
        $database = enrol_get_plugin('database');
        foreach ($todelete  as $instance) {
            $database->delete_instance($instance);
        }
        $todelete->close();
        upgrade_plugin_savepoint(true, 2025070300, 'enrol', 'database');
    }

    // Automatically generated Moodle v5.0.0 release upgrade line.
    // Put any upgrade step following this.

    return true;
}
