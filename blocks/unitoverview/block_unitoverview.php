<?php
use moodle\blocks\moodleblock;
require_once $CFG->dirroot.'/grade/lib.php';
require_once $CFG->dirroot.'/blocks/unitoverview/lib.php';
require_once $CFG->dirroot.'/blocks/unitoverview/querylib.php';
require_once $CFG->dirroot.'/grade/edit/tree/lib.php';

class block_unitoverview extends block_list {
    public function init() {
        global $DB;
    }
    
    /**
     * Allows block configuration setting
     * @param  $courseid int
     * 
     */
    function has_config() {
        return false;
    }

    /** 
    * Allows multiple interface 
    * @return bool
    * 
    **/

    public function instance_allow_multiple() {
     return false;
    }

    /**
    *  Allows testing for versoning  
    * @return bool
    * @params
    */
    function _self_test() {
        return true;
    }

    /**
    * Main content that displays in the block plugin.
    * Section contains title|content|footer
    * 
    */
    function get_content() {
        global $CFG, $OUTPUT;
        
        $courseid           = required_param('id', PARAM_INT);
        $gtree              = new grade_tree($courseid, false, false);
        $gpr                = new grade_plugin_return(array('type'=>'edit', 'plugin'=>'tree', 'courseid'=>$courseid));
        $grade_edit_tree    = new grade_edit_tree($gtree, false, $gpr);
        $activites          = json_decode(json_encode($grade_edit_tree->gtree->levels));
        
        $this->content              = new stdClass;
        $this->content->icons   = array();
        $this->content->items   = array();
        $message = array();
        $iconcategory           = $OUTPUT->pix_icon('i/folder', get_string('course'));
        $value = 0.0;
        $this->title = "<a href=$CFG->wwwroot/admin/settings.php?section=gradecategorysettings
        >Unit Overview</a>";
        foreach ($activites as $rows) 
        {
            foreach($rows as $records){
                if($records->type=="courseitem" && (int)$records->object->grademax != (int) 100){
                    $message['coursetotal'] = "The course total is now (".$records->object->grademax.") but it must be equal to 100."; 
                }
                if($records->type=="category"){
                    foreach($records->children as $item){
                        if($item->type=="categoryitem"){
                            $this->content->items[] = $iconcategory."<b>".$records->object->fullname."&nbsp&nbsp&nbsp".grade_percentage($item->object->aggregationcoef) ."%</b>";
                            $value += grade_percentage($item->object->aggregationcoef);
                        }
                        if($item->type=="item" && $item->depth==1){
                            $message['activity'] = "The category item (".$item->object->itemname.") must be inside a category."; 
                            $this->content->items[] = "&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp".$item->object->itemname."&nbsp&nbsp&nbsp".grade_percentage($item->object->aggregationcoef)."%";
                        }

                    }
                }

            }
                        
        }
        if($value != 100)
            $message['weight'] = "The sum category item weight must be 100 %.";
        $this->page->requires->js_call_amd('block_unitoverview/confirm_modal','init',$message);
        $this->content->footer = "Total &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp".$value."%".$OUTPUT->single_button(new moodle_url('index.php', array('id'=>$courseid)), "Validate", 'post',['class'=>'validate','formid'=>'validate_form']);;
        return $this->content;
    }

    protected function specific_definition($mform) {   
 
    }

    /*Restrict which pages to display the plugin
    *
    *
    */
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

    /**
     * Add custom html attributes to aid with theming and styling
     *
     * @return array
     */
    function html_attributes() {
        global $CFG;

        $attributes = parent::html_attributes();

        if (!empty($CFG->block_html_allowcssclasses)) {
            if (!empty($this->config->classes)) {
                $attributes['class'] .= ' '.$this->config->classes;
            }
        }

        return $attributes;
    }
}
