<?php

/**
 * AI Agent Runtime status controller.
 *
 * The route /aiagent is available only to a locally authenticated channel.
 * It is deliberately read-only until the execution and authorization model
 * has been implemented and reviewed.
 */

namespace Zotlabs\Module;

use Zotlabs\Web\Controller;

class Aiagent extends Controller {

    public function get() {
        if (!\local_channel()) {
            return '<p>Sign in to a local Hubzilla channel to view AI Agent Runtime status.</p>';
        }

        return '<section class="generic-content-wrapper">'
            . '<h2>AI Agent Runtime</h2>'
            . '<p>Addon installed and status route active.</p>'
            . '<p>Version: 0.1.0 (foundation only)</p>'
            . '<p>Model execution: not configured.</p>'
            . '</section>';
    }
}
