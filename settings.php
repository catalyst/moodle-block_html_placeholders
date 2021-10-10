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
 * Settings for the HTML block
 *
 * @copyright 2012 Aaron Barnes
 * @license   http://www.gnu.org/copyleft/gpl.html_placeholders GNU GPL v3 or later
 * @package   block_html_placeholders
 */

defined('MOODLE_INTERNAL') || die;

if ($ADMIN->fulltree) {
    $settings->add(new admin_setting_configcheckbox(
        'block_html_placeholders/allowcssclasses',
        get_string('allowadditionalcssclasses', 'block_html_placeholders'),
        get_string('configallowadditionalcssclasses', 'block_html_placeholders'),
        0)
    );

    $settings->add(new admin_setting_configtextarea(
        'block_html_placeholders/placeholders',
        get_string('placeholders', 'block_html_placeholders'),
        get_string('placeholders_desc', 'block_html_placeholders'),
        '')
    );
}
