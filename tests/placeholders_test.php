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
 * Unit test for placeholder functionality.
 *
 * @package block_html_placeholders
 * @copyright 2021 Catalyst IT
 * @license http://www.gnu.org/copyleft/gpl.html_placeholders GNU GPL v3 or later
 */

namespace block_html_placeholders;

defined('MOODLE_INTERNAL') || die();

/**
 * Unit test for placeholder functionality.
 *
 * @package block_html_placeholders
 * @copyright 2021 Catalyst IT
 * @license http://www.gnu.org/copyleft/gpl.html_placeholders GNU GPL v3 or later
 */
class placeholders_testcase extends \advanced_testcase {

    /**
     * Creates an HTML block on a course.
     *
     * @param \stdClass $course Course object
     * @return \block_html_placeholders Block instance object
     */
    protected function create_block($course) {
        $page = $this->construct_page($course);
        $page->blocks->add_block_at_end_of_default_region('html_placeholders');

        return $this->load_block($course);
    }

    /**
     * Loads block to the course page.
     *
     * @param \stdClass $course Course object
     * @return \block_html_placeholders Block instance object
     */
    protected function load_block($course) {
        // Load the block.
        $page = $this->construct_page($course);
        $page->blocks->load_blocks();
        $blocks = $page->blocks->get_blocks_for_region($page->blocks->get_default_region());

        return end($blocks);
    }

    /**
     * Constructs a page object for the test course.
     *
     * @param \stdClass $course Moodle course object
     * @return \moodle_page Page object representing course view
     */
    protected static function construct_page($course) {
        $context = \context_course::instance($course->id);
        $page = new \moodle_page();
        $page->set_context($context);
        $page->set_course($course);
        $page->set_pagelayout('standard');
        $page->set_pagetype('course-view');
        $page->blocks->load_blocks();

        return $page;
    }

    /**
     * Test placeholders.
     */
    public function test_placeholders() {
        $this->resetAfterTest();
        $this->setAdminUser();

        $testsetting = <<<SETTING
profession | Doctor
type | On-line
 | NoPlaceholder
empty |
space | Two Words
SETTING;

        set_config('block_html_placeholders_placeholders', $testsetting);

        $course = $this->getDataGenerator()->create_course();
        $block = $this->create_block($course);
        $data = (object)[
            'title' => 'Block title {{profession}} {{type}} {{empty}} {{profession}} {{space}}',
            'text' => [
                'text' => 'Block text {{profession}} {{type}} {{empty}} {{profession}} {{space}}',
                'itemid' => 0,
                'format' => FORMAT_HTML
            ]
        ];
        $block->instance_config_save($data);

        $this->assertNull(get_user_preferences('profession'));
        $this->assertNull(get_user_preferences('type'));
        $this->assertNull(get_user_preferences('space'));

        // Load the block.
        $block = $this->load_block($course);
        $this->assertStringContainsString('Block title Doctor On-line {{empty}} Doctor Two Words', $block->get_title());
        $this->assertStringContainsString('Block text Doctor On-line {{empty}} Doctor Two Words', $block->get_content()->text);

        // As placeholders came from the config, user data shouldn't be set.
        $this->assertNull(get_user_preferences('profession'));
        $this->assertNull(get_user_preferences('type'));
        $this->assertNull(get_user_preferences('space'));

        // Emulate URL params and load the block.
        $_POST['profession'] = 'Doc';
        $_POST['space'] = 'Space';

        $block = $this->load_block($course);

        $this->assertStringContainsString('Block title Doc On-line {{empty}} Doc Space', $block->get_title());
        $this->assertStringContainsString('Block text Doc On-line {{empty}} Doc Space', $block->get_content()->text);

        // Now user data should be updated.
        $this->assertSame('Doc', get_user_preferences('profession'));
        $this->assertNull(get_user_preferences('type'));
        $this->assertSame('Space', get_user_preferences('space'));

        // Unset placeholders in URL and check that placeholders are coming from user data.
        unset($_POST['profession']);
        $_POST['space'] = '';
        $block = $this->load_block($course);

        $this->assertStringContainsString('Block title Doc On-line {{empty}} Doc Space', $block->get_title());
        $this->assertStringContainsString('Block text Doc On-line {{empty}} Doc Space', $block->get_content()->text);
        $this->assertSame('Doc', get_user_preferences('profession'));
        $this->assertNull(get_user_preferences('type'));
        $this->assertSame('Space', get_user_preferences('space'));
    }

}
