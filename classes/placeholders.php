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
 * Placeholders class.
 *
 * @package    block_html_placeholders
 * @copyright  Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace block_html_placeholders;

defined('MOODLE_INTERNAL') || die();

/**
 * Placeholders class.
 *
 * @package    block_html_placeholders
 * @copyright  Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class placeholders {

    /**
     * A list of placeholders and their default values from config.
     * @var array
     */
    private $placeholders = [];

    /**
     * Constructor.
     */
    public function __construct() {
        $placeholders = get_config('block_html_placeholders', 'placeholders');
        if (!empty($placeholders)) {
            $placeholderstrings = explode("\n", str_replace("\r\n", "\n", $placeholders));

            foreach ($placeholderstrings as $placeholderstring) {
                $placeholder = new \stdClass();
                $parts = explode('|', $placeholderstring);
                if (count($parts) === 2) {
                    $placeholder->name = trim($parts[0]);
                    $placeholder->default = trim($parts[1]);

                    if (!empty($placeholder->name) && !empty($placeholder->default)) {
                        $this->placeholders[$placeholder->name] = $placeholder->default;
                    }
                }
            }
        }
    }

    /**
     * Replaces all known placeholders in the provided string.
     *
     * @param string $str String to process.
     * @return string
     */
    public function replace_placeholders(string $str): string {
        foreach ($this->placeholders as $placeholder => $defaultvalue) {
            $str = str_replace('{{' . $placeholder . '}}', $this->get_placeholder_value($placeholder, $defaultvalue), $str);
        }

        return $str;
    }

    /**
     * Remember placeholders for a current user.
     */
    public function remember_placeholders_for_user() {
        foreach ($this->placeholders as $placeholder => $defaultvalue) {
            // Remember placeholders if the values come from URL.
            $value = optional_param($placeholder, '', PARAM_TEXT);
            if (!empty($value)) {
                set_user_preference($placeholder, $value);
            }
        }
    }

    /**
     *  Returns placeholder value for provided placeholder.
     *
     * Logic:
     *  1. Try to get from URL.
     *  2. Try to get from user preferences.
     *  3. Fall back to the default value from config.
     *
     * @param string $placeholder Placeholder name.
     * @param string $defaultvalue Default value.
     *
     * @return string
     */
    protected function get_placeholder_value(string $placeholder, string $defaultvalue = ''): string {
        $value = optional_param($placeholder, '', PARAM_TEXT);
        if (empty($value)) {
            $value = get_user_preferences($placeholder, $defaultvalue);
        }

        return $value;
    }

}
