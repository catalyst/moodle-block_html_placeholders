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
 * Block instance.
 *
 * @package   block_html_placeholders
 * @author    1999 Martin Dougiamas (http://dougiamas.com)
 * @author    2021 Dmitrii Metelkin (dmitriim@catalyst-au.net)
 * @copyright Catalyst IT
 * @license   http://www.gnu.org/copyleft/gpl.html_placeholders GNU GPL v3 or later
 */

use \block_html_placeholders\placeholders;

/**
 * Block instance.
 *
 * @package   block_html_placeholders
 * @copyright 1999 onwards Martin Dougiamas (http://dougiamas.com)
 * @license   http://www.gnu.org/copyleft/gpl.html_placeholders GNU GPL v3 or later
 */
class block_html_placeholders extends block_base {
    /**
     * Placeholders instance.
     *
     * @var placeholders
     */
    private $placeholders;

    public function init() {
        $this->placeholders = new placeholders();
        $this->title = get_string('pluginname', 'block_html_placeholders');
    }

    public function has_config() {
        return true;
    }

    public function applicable_formats() {
        return array('all' => true);
    }

    public function specialization() {
        $this->placeholders->remember_placeholders_for_user();

        if (isset($this->config->title)) {
            $this->config->title = $this->placeholders->replace_placeholders($this->config->title);
            $this->title = $this->title = format_string($this->config->title, true, ['context' => $this->context]);
        } else {
            $this->title = get_string('newhtml_placeholdersblock', 'block_html_placeholders');
        }
    }

    public function instance_allow_multiple() {
        return true;
    }

    public function get_content() {
        global $CFG;

        require_once($CFG->libdir . '/filelib.php');

        if ($this->content !== null) {
            return $this->content;
        }

        $filteropt = new stdClass;
        $filteropt->overflowdiv = true;
        if ($this->content_is_trusted()) {
            // Fancy html_placeholders allowed only on course, category and system blocks.
            $filteropt->noclean = true;
        }

        $this->content = new stdClass;
        $this->content->footer = '';
        if (isset($this->config->text)) {
            // Rewrite url.
            $this->config->text = file_rewrite_pluginfile_urls(
                $this->config->text, 'pluginfile.php',
                $this->context->id,
                'block_html_placeholders',
                'content',
                null
            );

            $this->config->text = $this->placeholders->replace_placeholders($this->config->text);

            // Default to FORMAT_HTML which is what will have been used before the
            // editor was properly implemented for the block.
            $format = FORMAT_HTML;
            // Check to see if the format has been properly set on the config.
            if (isset($this->config->format)) {
                $format = $this->config->format;
            }
            $this->content->text = format_text($this->config->text, $format, $filteropt);

        } else {
            $this->content->text = '';
        }

        unset($filteropt); // Memory footprint.

        return $this->content;
    }

    public function get_content_for_external($output) {
        global $CFG;
        require_once($CFG->libdir . '/externallib.php');

        $bc = new stdClass;
        $bc->title = null;
        $bc->content = '';
        $bc->contenformat = FORMAT_MOODLE;
        $bc->footer = '';
        $bc->files = [];

        if (!$this->hide_header()) {
            $bc->title = $this->title;
        }

        if (isset($this->config->text)) {
            $filteropt = new stdClass;
            if ($this->content_is_trusted()) {
                // Fancy html_placeholders allowed only on course, category and system blocks.
                $filteropt->noclean = true;
            }

            $this->config->text = $this->placeholders->replace_placeholders($this->config->text);

            $format = FORMAT_HTML;
            // Check to see if the format has been properly set on the config.
            if (isset($this->config->format)) {
                $format = $this->config->format;
            }
            list($bc->content, $bc->contentformat) =
                external_format_text(
                    $this->config->text,
                    $format,
                    $this->context,
                    'block_html_placeholders',
                    'content',
                    null,
                    $filteropt
                );
            $bc->files = external_util::get_area_files($this->context->id, 'block_html_placeholders', 'content', false, false);

        }
        return $bc;
    }

    /**
     * Serialize and store config data
     */
    public function instance_config_save($data, $nolongerused = false) {
        global $DB;

        $config = clone($data);
        // Move embedded files into a proper filearea and adjust HTML links to match.
        $config->text = file_save_draft_area_files(
            $data->text['itemid'],
            $this->context->id,
            'block_html_placeholders',
            'content',
            0,
            ['subdirs' => true],
            $data->text['text']
        );
        $config->format = $data->text['format'];

        parent::instance_config_save($config, $nolongerused);
    }

    public function instance_delete() {
        global $DB;
        $fs = get_file_storage();
        $fs->delete_area_files($this->context->id, 'block_html_placeholders');
        return true;
    }

    /**
     * Copy any block-specific data when copying to a new block instance.
     * @param int $fromid the id number of the block instance to copy from
     * @return boolean
     */
    public function instance_copy($fromid) {
        $fromcontext = context_block::instance($fromid);
        $fs = get_file_storage();
        // This extra check if file area is empty adds one query if it is not empty but saves several if it is.
        if (!$fs->is_area_empty($fromcontext->id, 'block_html_placeholders', 'content', 0, false)) {
            $draftitemid = 0;
            file_prepare_draft_area(
                $draftitemid,
                $fromcontext->id,
                'block_html_placeholders',
                'content',
                0,
                ['subdirs' => true]
            );
            file_save_draft_area_files(
                $draftitemid,
                $this->context->id,
                'block_html_placeholders',
                'content',
                0,
                ['subdirs' => true]
            );
        }

        return true;
    }

    public function content_is_trusted() {
        global $SCRIPT;

        if (!$context = context::instance_by_id($this->instance->parentcontextid, IGNORE_MISSING)) {
            return false;
        }
        // Find out if this block is on the profile page.
        if ($context->contextlevel == CONTEXT_USER) {
            if ($SCRIPT === '/my/index.php') {
                // This is exception - page is completely private, nobody else may see content there
                // that is why we allow JS here.
                return true;
            } else {
                // No JS on public personal pages, it would be a big security issue.
                return false;
            }
        }

        return true;
    }

    /**
     * The block should only be dockable when the title of the block is not empty
     * and when parent allows docking.
     *
     * @return bool
     */
    public function instance_can_be_docked() {
        return (!empty($this->config->title) && parent::instance_can_be_docked());
    }

    /*
     * Add custom html_placeholders attributes to aid with theming and styling
     *
     * @return array
     */
    public function html_attributes() {
        global $CFG;

        $attributes = parent::html_attributes();

        if (!empty(get_config('block_html_placeholders', 'allowcssclasses'))) {
            if (!empty($this->config->classes)) {
                $attributes['class'] .= ' '.$this->config->classes;
            }
        }

        return $attributes;
    }

    /**
     * Return the plugin config settings for external functions.
     *
     * @return stdClass the configs for both the block instance and plugin
     * @since Moodle 3.8
     */
    public function get_config_for_external() {
        global $CFG;

        // Return all settings for all users since it is safe (no private keys, etc..).
        $instanceconfigs = !empty($this->config) ? $this->config : new stdClass();
        $pluginconfigs = (object) ['allowcssclasses' => get_config('block_html_placeholders', 'allowcssclasses')];

        return (object) [
            'instance' => $instanceconfigs,
            'plugin' => $pluginconfigs,
        ];
    }

}
