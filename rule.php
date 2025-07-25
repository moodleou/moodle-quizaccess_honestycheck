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
 * Implementaton of the quizaccess_honestycheck plugin.
 *
 * @package   quizaccess_honestycheck
 * @copyright 2011 The Open University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

// This work-around is required until Moodle 4.2 is the lowest version we support.
if (class_exists('\mod_quiz\local\access_rule_base')) {
    class_alias('\mod_quiz\local\access_rule_base', '\quizaccess_honestycheck_parent_class_alias');
    class_alias('\mod_quiz\form\preflight_check_form', '\quizaccess_honestycheck_preflight_form_alias');
    class_alias('\mod_quiz\quiz_settings', '\quizaccess_honestycheck_quiz_settings_class_alias');
} else {
    require_once($CFG->dirroot . '/mod/quiz/accessrule/accessrulebase.php');
    class_alias('\quiz_access_rule_base', '\quizaccess_honestycheck_parent_class_alias');
    class_alias('\mod_quiz_preflight_check_form', '\quizaccess_honestycheck_preflight_form_alias');
    class_alias('\quiz', '\quizaccess_honestycheck_quiz_settings_class_alias');
}



/**
 * A rule requiring the student to promise not to cheat.
 *
 * @copyright  2011 The Open University
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class quizaccess_honestycheck extends quizaccess_honestycheck_parent_class_alias {

    /**
     * Whether the honesty check is required.
     *
     * @param int $attemptid The attempt id.
     */
    public function is_preflight_check_required($attemptid) {
        return empty($attemptid);
    }

    /**
     * Add the preflight check form fields.
     *
     * @param quizaccess_honestycheck_preflight_form_alias $quizform The quiz form.
     * @param MoodleQuickForm $mform The Moodle form object.
     * @param int $attemptid The attempt id.
     */
    public function add_preflight_check_form_fields(quizaccess_honestycheck_preflight_form_alias $quizform,
            MoodleQuickForm $mform, $attemptid) {

        $mform->addElement('header', 'honestycheckheader',
                get_string('honestycheckheader', 'quizaccess_honestycheck'));
        $mform->addElement('static', 'honestycheckmessage', '',
                get_string('honestycheckstatement', 'quizaccess_honestycheck'));
        $mform->addElement('checkbox', 'honestycheck', '',
                get_string('honestychecklabel', 'quizaccess_honestycheck'));
    }

    /**
     * Validate the preflight check.
     *
     * @param array $data The submitted form data.
     * @param array $files The submitted files.
     * @param array $errors The errors.
     * @param int $attemptid The attempt ID.
     */
    public function validate_preflight_check($data, $files, $errors, $attemptid) {
        if (empty($data['honestycheck'])) {
            $errors['honestycheck'] = get_string('youmustagree', 'quizaccess_honestycheck');
        }

        return $errors;
    }

    /**
     * Make a new instance of the honestycheck.
     *
     * @param quizaccess_honestycheck_quiz_settings_class_alias $quizobj The quiz object.
     * @param int $timenow The current time.
     * @param bool $canignoretimelimits Whether the user can ignore time limits.
     */
    public static function make(quizaccess_honestycheck_quiz_settings_class_alias $quizobj, $timenow, $canignoretimelimits) {

        if (empty($quizobj->get_quiz()->honestycheckrequired)) {
            return null;
        }

        return new self($quizobj, $timenow);
    }

    /**
     * Add settings form fields to the quiz settings form.
     *
     * @param mod_quiz_mod_form $quizform The quiz form.
     * @param MoodleQuickForm $mform The Moodle form object.
     */
    public static function add_settings_form_fields(
            mod_quiz_mod_form $quizform, MoodleQuickForm $mform) {
        $mform->addElement('select', 'honestycheckrequired',
            get_string('honestycheckrequired', 'quizaccess_honestycheck'), [
                0 => get_string('notrequired', 'quizaccess_honestycheck'),
                1 => get_string('honestycheckrequiredoption', 'quizaccess_honestycheck'),
            ]);
        $mform->addHelpButton('honestycheckrequired',
            'honestycheckrequired', 'quizaccess_honestycheck');
    }

    /**
     * Save the settings for a quiz.
     *
     * @param stdClass $quiz The quiz object.
     */
    public static function save_settings($quiz) {
        global $DB;
        if (empty($quiz->honestycheckrequired)) {
            $DB->delete_records('quizaccess_honestycheck', ['quizid' => $quiz->id]);
        } else {
            if (!$DB->record_exists('quizaccess_honestycheck', ['quizid' => $quiz->id])) {
                $record = new stdClass();
                $record->quizid = $quiz->id;
                $record->honestycheckrequired = 1;
                $DB->insert_record('quizaccess_honestycheck', $record);
            }
        }
    }

    /**
     * Delete the settings for a quiz.
     *
     * @param stdClass $quiz The quiz object.
     */
    public static function delete_settings($quiz) {
        global $DB;
        $DB->delete_records('quizaccess_honestycheck', ['quizid' => $quiz->id]);
    }

    /**
     * Get the settings SQL.
     *
     * @param int $quizid The quiz id.
     */
    public static function get_settings_sql($quizid) {
        return [
            'honestycheckrequired',
            'LEFT JOIN {quizaccess_honestycheck} honestycheck ON honestycheck.quizid = quiz.id',
            [],
        ];
    }
}
