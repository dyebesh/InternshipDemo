<?php  

/*Raw query for the plugin goes in this section fo file*/

/**
 * Validate category item outside the  category 
 * @param  $courseid int
 * @return array
 */
function validate_category($courseid){
global $CFG, $DB;
    $sql= "SELECT
            count(mdl_grade_items.itemname) as activity_status
        FROM
            mdl_course
        LEFT JOIN mdl_grade_categories ON mdl_grade_categories.courseid = mdl_course.id
        LEFT JOIN mdl_grade_items ON mdl_grade_items.categoryid = mdl_grade_categories.id
        WHERE
            mdl_course.id ={$courseid}  and mdl_grade_items.itemtype='mod'and depth=1 ";

   return $DB->get_record_sql($sql,['courseid'=>$courseid]);

}

/**
 * Validate Weight total for category 
 * @param  $courseid int
 * @return array
 */
function validate_weighttotal($courseid){
global $CFG, $DB;
  $sql =" SELECT
                CASE
                WHEN SUM( mdl_grade_items.aggregationcoef ) = 1 
                AND SUM( mdl_grade_items.aggregationcoef ) > 0 THEN
                    1 ELSE 0 
                    END AS category_status 
            FROM
                mdl_course
                LEFT JOIN mdl_grade_items ON mdl_grade_items.courseid = mdl_course.id 
            WHERE
            mdl_course.id = {$courseid}
            AND mdl_grade_items.itemtype = 'category'" ;
return $DB->get_record_sql($sql,['courseid'=>$courseid]);
}

/**
 * Validate grade total for category 
 * @param  $courseid int
 * @return array
 */
function validate_gradetotal($courseid){
global $CFG, $DB;
        $sql =" SELECT
                        CASE
                        WHEN AVG( mdl_grade_items.grademax ) = 100 
                        THEN
                            1 ELSE 0 
                            END AS total 
                    FROM
                        mdl_course
                        LEFT JOIN mdl_grade_items ON mdl_grade_items.courseid = mdl_course.id 
                    WHERE
                    mdl_course.id = {$courseid}
                    AND mdl_grade_items.itemtype = 'mod'" ;

     return $DB->get_record_sql($sql,['courseid'=>$courseid]);               
}