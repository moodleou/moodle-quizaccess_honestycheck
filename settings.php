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
 * Global configuration settings for the quizaccess_honestycheck plugin.
 *
 * @package   quizaccess_honestycheck
 * @author    Sumaiya Javed <sumaiya.javed@catalyst.net.nz>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

$options = [
    0 => new lang_string('notrequired', 'quizaccess_honestycheck'),
    1 => new lang_string('honestycheckrequiredoption', 'quizaccess_honestycheck'),
];
$setting = new admin_setting_configselect('quizaccess_honestycheck/honestycheckrequired',
    new lang_string('honestycheckrequired', 'quizaccess_honestycheck'), new lang_string('honestycheckrequired_help', 'quizaccess_honestycheck'),
    0, $options);
$setting->set_advanced_flag_options(admin_setting_flag::ENABLED, true);
$setting->set_locked_flag_options(admin_setting_flag::ENABLED, false);
$settings->add($setting);
