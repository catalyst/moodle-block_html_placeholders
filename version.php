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
 * Version details
 *
 * @package    block_html_placeholders
 * @copyright  Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html_placeholders GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$plugin->version   = 2020061503;        // The current plugin version (Date: YYYYMMDDXX).
$plugin->release   = 2020061503;
$plugin->maturity  = MATURITY_STABLE;
$plugin->requires  = 2020060900;        // Requires this Moodle version.
$plugin->component = 'block_html_placeholders';      // Full name of the plugin (used for diagnostics).
$plugin->supported = [39, 401];  // Available as of Moodle 3.9.0 or later.
