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
 * Landing page.
 *
 * @package    block_html_placeholders
 * @copyright  Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');

$edit = optional_param('edit', null, PARAM_BOOL);
$redirect = optional_param('redirect', null, PARAM_LOCALURL);

require_course_login($SITE);

if ($redirect) {
    $placeholders = new \block_html_placeholders\placeholders();
    $placeholders->remember_placeholders_for_user();
    redirect($redirect);
}

$header = $SITE->shortname;
$pagetitle = $SITE->shortname;
$context = context_system::instance();

if (!isguestuser()) {
    $PAGE->set_blocks_editing_capability('moodle/my:manageblocks');
}

$params = array();
$PAGE->set_context($context);
$PAGE->set_url('/blocks/html_placeholders/landing.php', $params);
$PAGE->set_pagelayout('mydashboard');
$PAGE->blocks->add_region('content');
$PAGE->set_title($pagetitle);
$PAGE->set_heading($header);

if ($PAGE->user_allowed_editing()) {
    if ($edit !== null) {
        $USER->editing = $edit;
    }

    $params = array('edit' => !$edit);

    if (empty($edit)) {
        $editstring = get_string('updatemymoodleon');
    } else {
        $editstring = get_string('updatemymoodleoff');
    }

    $url = new moodle_url("$CFG->wwwroot/blocks/html_placeholders/landing.php", $params);
    $button = $OUTPUT->single_button($url, $editstring);
    $PAGE->set_button($button);
} else {
    $USER->editing = $edit = 0;
}

echo $OUTPUT->header();
echo $OUTPUT->custom_block_region('content');
echo $OUTPUT->footer();
