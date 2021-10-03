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
 * Shortcodes API class.
 *
 * @package    block_html_placeholders
 * @copyright  Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_html_placeholders;

defined('MOODLE_INTERNAL') || die();

/**
 * Shortcodes API class.
 *
 * @package    block_html_placeholders
 * @copyright  Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class shortcodes {

    /**
     * Shortcodes callback.
     *
     * @param string $shortcode Shortcode.
     * @param array $args A list of arguments.
     * @param string|null $content
     * @param object $env The filter environment object, amongst other things contains the context.
     * @param Closure $next The function to pass the content through when embedded shortcodes should apply.
     *
     * @return mixed
     */
    public static function placeholder(string $shortcode, array $args, ?string $content, $env, $next): string {
        if ($shortcode === 'htmlplaceholder') {
            $placeholder = new placeholders();
            $placeholder->remember_placeholders_for_user();
            $content = $placeholder->replace_placeholders($content);
        }

        return $content;
    }

}
