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
 * Unit test for shortcodes functionality.
 *
 * @package block_html_placeholders
 * @copyright 2021 Catalyst IT
 * @license http://www.gnu.org/copyleft/gpl.html_placeholders GNU GPL v3 or later
 */

namespace block_html_placeholders;

defined('MOODLE_INTERNAL') || die();

/**
 * Unit test for shortcodes functionality.
 *
 * @package block_html_placeholders
 * @copyright 2021 Catalyst IT
 * @license http://www.gnu.org/copyleft/gpl.html_placeholders GNU GPL v3 or later
 */
class shortcodes_testcase extends \advanced_testcase {

    /**
     * Data provide for test_shortcodes.
     * @return array
     */
    public function shortcodes_data_provider(): array {
        return [
            ['htmlplaceholder', 'Test {{profession}}', 'Test Doctor'],
            ['placeholder', 'Test {{profession}}', 'Test {{profession}}'],
            ['htmlplaceholder', null, null],
            ['htmlplaceholder', 'Test {{type}}', 'Test On-line'],
            ['htmlplaceholder', 'Test string {{not-exist}}', 'Test string {{not-exist}}'],
            ['htmlplaceholder', 'Test {{type}} and {{profession}}', 'Test On-line and Doctor'],
        ];
    }

    /**
     * Test shortcodes.
     *
     * @dataProvider shortcodes_data_provider
     *
     * @param string $shortcode Shortcode.
     * @param ?string $content Content.
     * @param ?string $expected Expected result.
     */
    public function test_shortcodes(string $shortcode, ?string $content, ?string $expected) {
        $this->resetAfterTest();
        $this->setAdminUser();

        $testsetting = <<<SETTING
profession | Doctor
type | On-line
SETTING;

        set_config('block_html_placeholders_placeholders', $testsetting);
        $this->assertSame($expected, shortcodes::placeholder($shortcode, [], $content, new \stdClass(), null));
    }

    /**
     * Test that shortcodes commit placeholders to user preferences from URL.
     */
    public function test_shortcodes_commit_placeholders() {
        $this->resetAfterTest();
        $this->setAdminUser();

        $testsetting = "profession | Doctor";
        set_config('block_html_placeholders_placeholders', $testsetting);

        $this->assertNull(get_user_preferences('profession'));

        $content = "{{profession}}";
        $actual = shortcodes::placeholder('htmlplaceholder', [], $content, new \stdClass(), null);
        $this->assertNull(get_user_preferences('profession'));
        $this->assertSame('Doctor', $actual);

        $_POST['profession'] = 'Doc';
        $content = "{{profession}}";
        $actual = shortcodes::placeholder('htmlplaceholder', [], $content, new \stdClass(), null);
        $this->assertSame('Doc', $actual);
        $this->assertSame('Doc', get_user_preferences('profession'));
    }

}
