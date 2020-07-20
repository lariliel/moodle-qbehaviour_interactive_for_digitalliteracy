<?php

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/question/behaviour/interactive/behaviour.php');

class qbehaviour_interactive_for_digitalliteracy extends qbehaviour_interactive {

    public function is_compatible_question(question_definition $question) {
        return $question instanceof qtype_digitalliteracy_question;
    }

    public function can_finish_during_attempt() {
        return true;
    }

    public function process_submit(question_attempt_pending_step $pendingstep) {
        if ($this->qa->get_state()->is_finished()) {
            return question_attempt::DISCARD;
        }

        if (!$this->is_complete_response($pendingstep)) {
            $pendingstep->set_state(question_state::$invalid);

        } else {
            $triesleft = $this->qa->get_last_behaviour_var('_triesleft');
            list($fraction, $state) = $this->grade_response($pendingstep);
            if ($state == question_state::$gradedright || $triesleft == 1) {
                $pendingstep->set_state($state);
                $pendingstep->set_fraction($this->adjust_fraction($fraction, $pendingstep));

            } else {
                $pendingstep->set_behaviour_var('_triesleft', $triesleft - 1);
                $pendingstep->set_state(question_state::$todo);
            }
            $pendingstep->set_new_response_summary($this->question->summarise_response($pendingstep->get_qt_data()));
        }
        return question_attempt::KEEP;
    }

    // Grade the submission and cache the results (question_file_saver object) in the pending step
    protected function grade_response(question_attempt_pending_step $pendingstep) {
        $response = $pendingstep->get_qt_data();
        $gradedata = $this->question->grade_response($response);
        list($fraction, $state) = $gradedata;
        if (count($gradedata) > 2) {
            foreach ($gradedata[2] as $name => $value) {
                $pendingstep->set_qt_var($name, $value);
            }
        }
        return array($fraction, $state);
    }

    public function process_finish(question_attempt_pending_step $pendingstep) {
        if ($this->qa->get_state()->is_finished()) {
            return question_attempt::DISCARD;
        }

        $response = $this->qa->get_last_qt_data();
        if (!$this->question->is_gradable_response($response)) {
            $pendingstep->set_state(question_state::$gaveup);

        } else {
            list($fraction, $state) = $this->grade_response($pendingstep);
            $pendingstep->set_fraction($this->adjust_fraction($fraction, $pendingstep));
            $pendingstep->set_state($state);
        }
        $pendingstep->set_new_response_summary($this->question->summarise_response($response));
        return question_attempt::KEEP;
    }
}
