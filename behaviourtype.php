<?php

defined('MOODLE_INTERNAL') || die();


class qbehaviour_interactive_for_digitalliteracy_type extends qbehaviour_interactive_type {
    public function is_archetypal() {
        return false;
    }
    /* @link question_attempt */
    public function can_questions_finish_during_the_attempt() {
        return true;
    }
}
