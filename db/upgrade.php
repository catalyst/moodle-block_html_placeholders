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
 * This file keeps track of upgrades to the html_placeholders block
 *
 * @since Moodle 2.0
 * @package block_html_placeholders
 * @copyright 2010 Dongsheng Cai
 * @license http://www.gnu.org/copyleft/gpl.html_placeholders GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Upgrade code for the HTML block.
 *
 * @param int $oldversion
 */
function xmldb_block_html_placeholders_upgrade($oldversion) {
    global $CFG;

    if ($oldversion < 2020061503) {
        if (!empty($CFG->block_html_placeholders_allowcssclasses)) {
            set_config('allowcssclasses', $CFG->block_html_placeholders_allowcssclasses, 'block_html_placeholders');
            unset_config('block_html_placeholders_allowcssclasses');
        }

        if (!empty($CFG->block_html_placeholders_placeholders)) {
            set_config('placeholders', $CFG->block_html_placeholders_placeholders, 'block_html_placeholders');
            unset_config('block_html_placeholders_placeholders');
        }

        upgrade_block_savepoint(true, 2020061503, 'html_placeholders');
    }

    return true;
}
