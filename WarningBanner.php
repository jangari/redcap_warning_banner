<?php

namespace INTERSECT\WarningBanner;

use ExternalModules\AbstractExternalModule;
use Project;

class WarningBanner extends \ExternalModules\AbstractExternalModule {

    function redcap_module_configure_button_display($project_id = null) {
        // Only super users may configure on the project level.
        return defined("SUPER_USER") && SUPER_USER == 1 ? true : null;
    }

    function redcap_survey_page_top($project_id, $record, $instrument, $event_id, $group_id, $survey_hash, $response_id, $repeat_instance)
    {
        $show_banner = false;
        $projectVals = Project::getProjectVals();
        $status = $projectVals["status"];
        $purpose = $projectVals["purpose"];

        // Override if need be
        $override_project = $this -> getProjectSetting('override-project');
        if ($override_project){
            $enable_dev_warning_survey = $this -> getProjectSetting('enable-dev-warning-survey-project');
            $dev_warning_survey_text = $this -> getProjectSetting('dev-warning-survey-text-project');
            $enable_practice_warning_survey = $this -> getProjectSetting('enable-practice-warning-survey-project');
            $practice_warning_survey_text = $this -> getProjectSetting('practice-warning-survey-text-project');
        } else {
            $enable_dev_warning_survey = $this -> getProjectSetting('enable-dev-warning-survey');
            $dev_warning_survey_text = $this -> getProjectSetting('dev-warning-survey-text');
            $enable_practice_warning_survey = $this -> getProjectSetting('enable-practice-warning-survey');
            $practice_warning_survey_text = $this -> getProjectSetting('practice-warning-survey-text');
        }

        // Apply warning text, either from default or system/project setting
        $dev_warning_survey_text = ($dev_warning_survey_text != "") ? $dev_warning_survey_text : $this -> tt('default-dev-warning-text');
        $practice_warning_survey_text = ($practice_warning_survey_text != "") ? $practice_warning_survey_text : $this -> tt('default-practice-warning-text');

        // Test whether to show banner
        if ($purpose == 0 && $enable_practice_warning_survey){
            $warning = $practice_warning_survey_text;
            $show_banner = true;
        } elseif ($purpose != 0 && $status == 0 && $enable_dev_warning_survey) {
            $warning = $dev_warning_survey_text;
            $show_banner = true;
        }

        // Build and show
        if ($show_banner) {
        echo "<div id='warning-banner' class='red' style='margin:15px 15px 15px;'>
        <img src='". APP_PATH_IMAGES ."exclamation_red.png' alt=''>
        <span style='font-size:14px;'><b>".$this->tt("msg_warning").":</b> ".$warning."</span></div>";
        }
    }

    function redcap_every_page_top($project_id=null) {

            $show_banner = false;
            $projectVals = Project::getProjectVals();
            $status = $projectVals["status"];
            $purpose = $projectVals["purpose"];
            $override_project = $this -> getProjectSetting('override-project');

        if (PAGE === "Surveys/invite_participants.php" && !($_GET["email_log"]) == '1'){ // Don't run on the Survey Invitation Log page

            // Override if need be
            if ($override_project){
                $enable_dev_warning_user = $this -> getProjectSetting('enable-dev-warning-user-project');
                $dev_warning_user_text = $this -> getProjectSetting('dev-warning-user-text-project');
                $enable_practice_warning_user = $this -> getProjectSetting('enable-practice-warning-user-project');
                $practice_warning_user_text = $this -> getProjectSetting('practice-warning-user-text-project');
            } else {
                $enable_dev_warning_user = $this -> getProjectSetting('enable-dev-warning-user');
                $dev_warning_user_text = $this -> getProjectSetting('dev-warning-user-text');
                $enable_practice_warning_user = $this -> getProjectSetting('enable-practice-warning-user');
                $practice_warning_user_text = $this -> getProjectSetting('practice-warning-user-text');
            }

            // Apply warning text, either from default or system/project setting
            $dev_warning_user_text = ($dev_warning_user_text != "") ? $dev_warning_user_text : $this -> tt('default-dev-warning-text');
            $practice_warning_user_text = ($practice_warning_user_text != "") ? $practice_warning_user_text :$this -> tt('default-practice-warning-text');


            // Test whether to show banner
            if ($purpose == 0 && $enable_practice_warning_user){
                $warning = $practice_warning_user_text;
                $show_banner = true;
            } elseif ($purpose != 0 && $status == 0 && $enable_dev_warning_user) {
                $warning = $dev_warning_user_text;
                $show_banner = true;
            }

            // Build and show
            if ($show_banner) {
            echo "<div id='warning-banner' class='red' style='width:100%;max-width:902px;margin:15px 0px 15px;display:none;'>
            <img src='". APP_PATH_IMAGES ."exclamation_red.png' alt=''>
            <span style='font-size:14px;'><b>".$this->tt("msg_warning").":</b> ".$warning."</span></div>";
            echo "<script type='text/javascript'>
                $(document).ready(function(){
                    var banner = $('div#warning-banner');
                    var url_field = $('input#longurl').parent();
                    banner.detach().insertBefore(url_field);
                    var participant_list = $('div#partlist_outerdiv');
                    banner.insertBefore(participant_list);
                    banner.show();
                });
            </script>";
        }
    }

    // Convert existing yellow warning to red.
    if (PAGE === "DataEntry/record_home.php"){
        $add_edit_red = ($override_project) ? $this -> getProjectSetting('convert-add-edit-red-project') : $this -> getProjectSetting('convert-add-edit-red');
        if ($status == 0 && $add_edit_red) {
            echo "<script type='text/javascript'>
                $(document).ready(function(){
                var warning = $('div.projhdr').siblings('div.yellow')
                warning.children('img').attr('src','" . APP_PATH_IMAGES . "exclamation_red.png')
                warning.removeClass('yellow').addClass('red')
                });
                </script>";
            }
        }
    }
}
