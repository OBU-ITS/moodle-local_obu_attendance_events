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

namespace local_obu_attendance_events\event;

defined('MOODLE_INTERNAL') || die();

/**
 * metalinking_groups_created
 *
 * Class for event to be triggered when a new blog entry is associated with a context.
 *
 * @property-read array $other {
 *      Extra information about event.
 *
 *      - string associatetype: type of blog association, course/coursemodule.
 *      - int blogid: id of blog.
 *      - int associateid: id of associate.
 *      - string subject: blog subject.
 * }
 *
 * @package    local_obu_metalinking_events
 * @since      Moodle 4.1.13
 * @copyright  2024 Joe Souch
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

class attendance_sessions_moved extends \core\event\base
{
    /**
     * Init method.
     *
     * @return void
     */
    protected function init() {
        $this->data['crud'] = 'u';
        $this->data['edulevel'] = self::LEVEL_TEACHING;
        $this->data['objecttable'] = 'attendance_sessions';
    }

    /**
     * Returns description of what happened.
     *
     * @return string
     */
    public function get_description() {
        return "Attendance sessions from course with id '{$this->other['childid']}' have been transferred to course with id '{$this->other['parentid']}'";
    }

    /**
     * Return localised event name.
     *
     * @return string
     */
    public static function get_name() {
        return get_string('eventattendancesessionsmoved', 'local_obu_attendance_events');
    }

    /**
     * Get URL related to the action
     *
     * @return \moodle_url
     */
    public function get_url() {
        return new \moodle_url('/course/view.php', array('id' => $this->courseid));
    }

    /**
     * Create instance of event.
     *
     * @return attendance_sessions_moved
     */
    public static function create_from_metalinked_courses($childid, $parentid) {
        $data = array(
            'courseid' => $parentid,
            'context' => \context_course::instance($parentid),
            'other' => array(
                'childid' => $childid,
                'parentid' => $parentid)
        );

        $event = self::create($data);

        return $event;
    }

    public static function get_other_mapping() {
        // Nothing to map.
        return false;
    }
}