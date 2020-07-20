<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/question/behaviour/interactive/renderer.php');

class qbehaviour_interactive_for_digitalliteracy_renderer extends qbehaviour_interactive_renderer {
    public function controls(question_attempt $qa, question_display_options $options) {
        if ($options->readonly === qbehaviour_interactive::READONLY_EXCEPT_TRY_AGAIN
            || !$qa->get_question()->checkbutton) {
            return '';
        }
        return $this->submit_button($qa, $options);
    }
}
