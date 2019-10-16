<?php
$settings->add(new admin_setting_heading(
            'headerconfig',
            get_string('headerconfig', 'unitoverview'),
            get_string('descconfig', 'unitoverview')
        ));
 
$settings->add(new admin_setting_configcheckbox(
            'unitoverview/Allow_HTML',
            get_string('labelallowhtml', 'unitoverview'),
            get_string('descallowhtml', 'unitoverview'),
            0)
);

