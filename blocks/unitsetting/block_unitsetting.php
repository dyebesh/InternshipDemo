<?php
use moodle\blocks\moodleblock;
require_once $CFG->dirroot.'/lib/gradelib.php';
class block_unitsetting extends block_list {
    
    public function init() {
        global $DB;
        
        $this->title ="testtaa";

    }
    
    function _self_test() {
        return true;
    }
    
    public function instance_allow_multiple() {
     return false;
    }
    
    function has_config() {
        return false;
    }
    
    function get_content() {
        global $CFG, $DB, $OUTPUT;
        
        if($this->content !== NULL) {
            return $this->content;
        }
        $courseid        = required_param('id', PARAM_INT);
        $settings = grade_get_settings($courseid);
        $course_item = grade_item::fetch_course_item($courseid);
        //$course = get_record('course','id', $courseid);
        $this->title = "Unit Settings";
        $this->content = new stdClass;
        $this->content->items = array();
        $this->content->footer = 'Footer';
        $this->content->icons = array();
        $icon = $OUTPUT->pix_icon('i/course', get_string('course'));
        
        foreach ($settings as $key => $value) {
            if($key != "id"){
                $this->content->items[] =ucwords($key).$value;
             //$linkcss = $course_item ? "" : " class=\"dimmed\" ";
            }
        }
        return $this->content;
    }

    public function applicable_formats() {
     return array(
           'grade-edit-tree-index' => true, 
            'course-view' => false,
            'site-index' => false,
   'course-view-social' => false,
                  'mod' => false, 
             'mod-quiz' => false
        );
    }


}