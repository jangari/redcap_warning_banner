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

        // adds a warning banner to survey pages if the project is not in production
        $projectVals = Project::getProjectVals();
        $status = $projectVals["status"];
        $purpose = $projectVals["purpose"];
        if ($status == 0){
            switch($purpose){
            case 0:
                $warning = "This project is only for practice/testing purposes and is not for collecting real data.<br/>Unless you are a pilot tester, you should STOP NOW.";
                $bg = "#e8a90b";
                $fg = "black";
                break;
            default:
                $warning = "This project is not yet approved to collect real data.<br/>Unless you are a pilot tester, you should STOP NOW.";
                $bg = "#e84d0b";
                $fg = "white";
                break;
            }
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
                echo "<div id='non-prod-warning' class='warn-bnr' onclick='dismiss()'><p><i class='fas fa-exclamation-triangle'></i> WARNING <i class='fas fa-exclamation-triangle'></i><br/>".$warning."<br/><span style='font-style: italic; font-size: 80%; text-align: right;'>Dismiss</span></p></div>";
        };
        echo "<script>
            function dismiss(){
                $('div[id=\"non-prod-warning\"]').hide()
    };
        </script>";
    }

function redcap_every_page_top($project_id=null) {
    if (PAGE === "Surveys/invite_participants.php" && !($_GET["email_log"]) == '1'){

    $projectVals = Project::getProjectVals();
    $status = $projectVals["status"];
    $purpose = $projectVals["purpose"];
    if ($status == 0){
        switch($purpose){
        case 0:
            $warning = "This project is only for practice/testing purposes and is not for collecting real data.<br/>You must NOT send survey invitations to research participants.";
            break;
        default:
            $warning = "This project is not yet approved to collect real data.<br/>You must NOT send survey invitations until the project has been approved by an administrator.<br/>You may test surveys yourself or send to pilot testers.";
            break;
        }
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
    echo "<div id='non-prod-warning' class='warn-bnr' onclick='dismiss()'><p><span style='font-size: 120%; font-style: bold;'><i class='fas fa-exclamation-triangle'></i> WARNING <i class='fas fa-exclamation-triangle'></i></span><br/>".$warning."<br/><span style='font-style: italic; font-size: 80%; text-align: right;'>Dismiss</span></p></div>";
    };
    echo "<script>
        function dismiss(){
            $('div[id=\"non-prod-warning\"]').hide()
    };
    </script>";
    }
}
}
