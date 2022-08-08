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

        // Apply default text
        $dev_warning_survey_text = ($dev_warning_survey_text != "") ? $dev_warning_survey_text : "This project is not yet approved to collect real data.<br/>Unless you are a pilot tester, you should STOP NOW.";
        $practice_warning_survey_text = ($practice_warning_survey_text != "") ? $practice_warning_survey_text : "This project is only for practice/testing purposes and is not for collecting real data.<br/>Unless you are a pilot tester, you should STOP NOW.";

        if ($purpose == 0 && $enable_practice_warning_survey){
            $warning = $practice_warning_survey_text;
            $show_banner = true;
        } elseif ($purpose != 0 && $status == 0 && $enable_dev_warning_survey) {
            $warning = $dev_warning_survey_text;
            $show_banner = true;
        }
        if ($show_banner) {
        echo "<style>
        div.warn-bnr {
            background-color: rgb(250, 44, 23, 0.9);
            color: white;
            position: fixed;
            top: 100px;
            z-index: 10;
            left: 50%;
            margin-right: -50%;
            transform: translate(-50%, -50%);
            box-shadow: 10px 10px 8px #888888;
            border: rgb(250,44,23,1);
            border-radius: 5px;
        }
        div.warn-bnr p {
            text-align: center;
            text-decoration: italic;
            margin: 10px 10px 10px 10px;
        }
        </style>";
        echo "<div id='warning-banner' class='warn-bnr' onclick='dissmissWarnBnr()'><p><i class='fas fa-exclamation-triangle'></i> WARNING <i class='fas fa-exclamation-triangle'></i><br/>".$warning."<br/><span style='font-style: italic; font-size: 80%; text-align: right;'>Dismiss</span></p></div>";
        echo "<script>
        function dissmissWarnBnr(){
            $('div[id=\"warning-banner\"]').hide()
        };
        </script>";
    }
    }

function redcap_every_page_top($project_id=null) {

        $show_banner = false;
        $projectVals = Project::getProjectVals();
        $status = $projectVals["status"];
        $purpose = $projectVals["purpose"];
        $override_project = $this -> getProjectSetting('override-project');

    if (PAGE === "Surveys/invite_participants.php" && !($_GET["email_log"]) == '1'){ // Don't run on the Survey Invitation Log page

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
        $dev_warning_user_text = ($dev_warning_user_text != "") ? $dev_warning_user_text : "This project is not yet approved to collect real data.<br/>You must NOT send survey invitations until the project has been approved by an administrator.<br/>You may test surveys yourself or send to pilot testers.";
        $practice_warning_user_text = ($practice_warning_user_text != "") ? $practice_warning_user_text : "This project is only for practice/testing purposes and is not for collecting real data.<br/>You must NOT send survey invitations to research participants.";

        if ($purpose == 0 && $enable_practice_warning_user){
            $warning = $practice_warning_user_text;
            $show_banner = true;
        } elseif ($purpose != 0 && $status == 0 && $enable_dev_warning_user) {
            $warning = $dev_warning_user_text;
            $show_banner = true;
        }
        if ($show_banner) {
        echo "<style>
        div.warn-bnr {
            background-color: rgb(250, 44, 23, 0.9);
            color: white;
            position: fixed;
            top: 100px;
            z-index: 10;
            left: 50%;
            margin-right: -50%;
            transform: translate(-50%, -50%);
            box-shadow: 10px 10px 8px #888888;
            border: rgb(250,44,23,1);
            border-radius: 5px;
        }
        div.warn-bnr p {
            text-align: center;
            text-decoration: italic;
            margin: 10px 10px 10px 10px;
        }
        </style>";
        echo "<div id='warning-banner' class='warn-bnr' onclick='dissmissWarnBnr()'><p><span style='font-size: 120%; font-style: bold;'><i class='fas fa-exclamation-triangle'></i> WARNING <i class='fas fa-exclamation-triangle'></i></span><br/>".$warning."<br/><span style='font-style: italic; font-size: 80%; text-align: right;'>Dismiss</span></p></div>";
        echo "<script>
        function dissmissWarnBnr(){
            $('div[id=\"warning-banner\"]').hide()
        };
        </script>";
    }
}
if (PAGE === "DataEntry/record_home.php"){
    /* echo APP_PATH_IMAGES; */
    /* echo DIRECTORY_SEPARATOR; */
    // Convert yellow warning on add/edit record page to red. 
    $add_edit_red = ($override_project) ? $this -> getProjectSetting('convert-add-edit-red-project') : $this -> getProjectSetting('convert-add-edit-red');
    /* echo var_dump($this -> getProjectSetting('add-edit-red'), $override_project); */
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
