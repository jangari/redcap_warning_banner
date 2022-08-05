<?php
/**
 * @file
 * Provides ExternalModule class for Warning Banner
 */

namespace INTERSECT\WarningBanner;

use ExternalModules\AbstractExternalModule;
use Project;

/**
 * ExternalModule class for Modify Contact Admin Button.
 */
class WarningBanner extends \ExternalModules\AbstractExternalModule {

    function redcap_module_configure_button_display($project_id = null) {
        // Only super users may configure on the project level.
        return defined("SUPER_USER") && SUPER_USER == 1 ? true : null;
    }

    /**
     * @inheritdoc
     */
    function redcap_survey_page_top($project_id, $record, $instrument, $event_id, $group_id, $survey_hash, $response_id, $repeat_instance)
    {
        $show_banner = false;
        $projectVals = Project::getProjectVals();
        $status = $projectVals["status"];
        $purpose = $projectVals["purpose"];

        // Override if need be

        $override_project = $this -> getProjectSetting('override-project');
        if ($override_project){
            $enable_dev_warning_survey = $this -> getProjectSetting('enable_dev_warning_survey_project');
            $dev_warning_survey_text = $this -> getProjectSetting('dev_warning_survey_text_project');
            $enable_practice_warning_survey = $this -> getProjectSetting('enable_practice_warning_survey_project');
            $practice_warning_survey_text = $this -> getProjectSetting('practice_warning_survey_text_project');
        } else {
            $enable_dev_warning_survey = $this -> getProjectSetting('enable_dev_warning_survey');
            $dev_warning_survey_text = $this -> getProjectSetting('dev_warning_survey_text');
            $enable_practice_warning_survey = $this -> getProjectSetting('enable_practice_warning_survey');
            $practice_warning_survey_text = $this -> getProjectSetting('practice_warning_survey_text');
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
        echo "<div id='warning-banner' class='warn-bnr' onclick='dismiss-warn-bnr()'><p><i class='fas fa-exclamation-triangle'></i> WARNING <i class='fas fa-exclamation-triangle'></i><br/>".$warning."<br/><span style='font-style: italic; font-size: 80%; text-align: right;'>Dismiss</span></p></div>";
        echo "<script>
        function dismiss-warn-bnr(){
            $('div[id=\"warning-banner\"]').hide()
        };
        </script>";
    }

function redcap_every_page_top($project_id=null) {
    if (PAGE === "Surveys/invite_participants.php" && !($_GET["email_log"]) == '1'){

        $show_banner = false;
        $projectVals = Project::getProjectVals();
        $status = $projectVals["status"];
        $purpose = $projectVals["purpose"];

        $override_project = $this -> getProjectSetting('override-project');
        if ($override_project){
            $enable_dev_warning_user = $this -> getProjectSetting('enable_dev_warning_user_project');
            $dev_warning_user_text = $this -> getProjectSetting('dev_warning_user_text_project');
            $enable_practice_warning_user = $this -> getProjectSetting('enable_practice_warning_user_project');
            $practice_warning_user_text = $this -> getProjectSetting('practice_warning_user_text_project');
        } else {
            $enable_dev_warning_user = $this -> getProjectSetting('enable_dev_warning_user');
            $dev_warning_user_text = $this -> getProjectSetting('dev_warning_user_text');
            $enable_practice_warning_user = $this -> getProjectSetting('enable_practice_warning_user');
            $practice_warning_user_text = $this -> getProjectSetting('practice_warning_user_text');
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
        echo "<div id='warning-banner' class='warn-bnr' onclick='dismiss-warn-bnr()'><p><span style='font-size: 120%; font-style: bold;'><i class='fas fa-exclamation-triangle'></i> WARNING <i class='fas fa-exclamation-triangle'></i></span><br/>".$warning."<br/><span style='font-style: italic; font-size: 80%; text-align: right;'>Dismiss</span></p></div>";
        echo "<script>
        function dismiss-warn-bnr(){
            $('div[id=\"warning-banner\"]').hide()
        };
        </script>";
    }
}
}
