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
 * @package    block_html_placeholders
 * @subpackage backup-moodle2
 * @author     2003 onwards Eloy Lafuente (stronk7) {@link http://stronk7.com}
 * @author     2021 Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @copyright  Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html_placeholders GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Specialised backup task for the html_placeholders block
 * (requires encode_content_links in some configdata attrs)
 */
class backup_html_placeholders_block_task extends backup_block_task {

    protected function define_my_settings() {
    }

    protected function define_my_steps() {
    }

    public function get_fileareas() {
        return array('content');
    }

    public function get_configdata_encoded_attributes() {
        return array('text'); // We need to encode some attrs in configdata.
    }

    public static function encode_content_links($content) {
        return $content; // No special encoding of links.
    }
}

