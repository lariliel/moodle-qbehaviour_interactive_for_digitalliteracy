<?php

namespace qbehaviour_interactive_for_digitalliteracy\privacy;

defined('MOODLE_INTERNAL') || die();

class provider implements \core_privacy\local\metadata\null_provider {
    // This polyfill allows the provider to work on both old (pre-7) and new PHP versions.
    use \core_privacy\local\legacy_polyfill;

    /**
     * Get the language string identifier with the component's language
     * file to explain why this plugin stores no data.
     *
     * @return  string
     */
    public static function _get_reason() {
        return 'privacy:metadata';
    }
}
