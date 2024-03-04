<?php
/**
 * Displays a warning banner for projects that are in development mode, or which have practice as their purpose.
 * Warnings can be enabled or disabled, and customised, at the system level, and overriden at the project level by an administrator.
 * The purpose is to discourage users from collecting real data in practice projects or development mode.
 * @author Aidan Wilson <aidan.wilson@intersect.org.au>
 * @link https://github.com/jangari/redcap_warning_banner
 */

namespace INTERSECT\WarningBanner;

use ExternalModules\AbstractExternalModule;
use Project;

class WarningBanner extends \ExternalModules\AbstractExternalModule {

    function get_module_settings() {
        // Define the settings
        $settings = array();
        // Override project settings with custom project settings
        $settings['override_project'] = $this -> getProjectSetting('override-project');
        if ($settings['override_project']) { // This block is not firing. 'override_project' is a boolean. How do I test this?
            $settings['enable_dev_warning_survey'] = $this -> getProjectSetting('enable-dev-warning-survey-project');
            $settings['dev_warning_survey_text'] = $this -> getProjectSetting('dev-warning-survey-text-project');
            $settings['dev_warning_survey_col'] = $this -> getProjectSetting('dev-warning-survey-col-project');
            $settings['enable_practice_warning_survey'] = $this -> getProjectSetting('enable-practice-warning-survey-project');
            $settings['practice_warning_survey_text'] = $this -> getProjectSetting('practice-warning-survey-text-project');
            $settings['practice_warning_survey_col'] = $this -> getProjectSetting('practice-warning-survey-col-project');
            $settings['enable_dev_warning_user'] = $this -> getProjectSetting('enable-dev-warning-user-project');
            $settings['dev_warning_user_text'] = $this -> getProjectSetting('dev-warning-user-text-project');
            $settings['dev_warning_user_col'] = $this -> getProjectSetting('dev-warning-user-col-project');
            $settings['enable_practice_warning_user'] = $this -> getProjectSetting('enable-practice-warning-user-project');
            $settings['practice_warning_user_text'] = $this -> getProjectSetting('practice-warning-user-text-project');
            $settings['practice_warning_user_col'] = $this -> getProjectSetting('practice-warning-user-col-project');
            $settings['add_edit_col'] = $this -> getProjectSetting('add-edit-col-project'); 
        } else {
            $settings['enable_dev_warning_survey'] = $this -> getProjectSetting('enable-dev-warning-survey');
            $settings['dev_warning_survey_text'] = $this -> getProjectSetting('dev-warning-survey-text');
            $settings['dev_warning_survey_col'] = $this -> getProjectSetting('dev-warning-survey-col');
            $settings['enable_practice_warning_survey'] = $this -> getProjectSetting('enable-practice-warning-survey');
            $settings['practice_warning_survey_text'] = $this -> getProjectSetting('practice-warning-survey-text');
            $settings['practice_warning_survey_col'] = $this -> getProjectSetting('practice-warning-survey-col');
            $settings['enable_dev_warning_user'] = $this -> getProjectSetting('enable-dev-warning-user');
            $settings['dev_warning_user_text'] = $this -> getProjectSetting('dev-warning-user-text');
            $settings['dev_warning_user_col'] = $this -> getProjectSetting('dev-warning-user-col');
            $settings['enable_practice_warning_user'] = $this -> getProjectSetting('enable-practice-warning-user');
            $settings['practice_warning_user_text'] = $this -> getProjectSetting('practice-warning-user-text');
            $settings['practice_warning_user_col'] = $this -> getProjectSetting('practice-warning-user-col');
            $settings['add_edit_col'] = $this -> getProjectSetting('add-edit-col');
        }
        // set default text from tt array if unset in system or project module configuration
        $settings['dev_warning_survey_text'] = ($settings['dev_warning_survey_text'] != "") ? $settings['dev_warning_survey_text'] : $this -> tt('default-dev-warning-text');
        $settings['practice_warning_survey_text'] = ($settings['practice_warning_survey_text'] != "") ? $settings['practice_warning_survey_text'] : $this -> tt('default-practice-warning-text');
        $settings['dev_warning_user_text'] = ($settings['dev_warning_user_text'] != "") ? $settings['dev_warning_user_text'] : $this -> tt('default-dev-warning-text');
        $settings['practice_warning_user_text'] = ($settings['practice_warning_user_text'] != "") ? $settings['practice_warning_user_text'] : $this -> tt('default-practice-warning-text');

        // set default colour from default if unset in system or project module configuration
        $settings['add_edit_col'] = ($settings['add_edit_col'] != "") ? $settings['add_edit_col'] : "red";

        return $settings;
    }

    function redcap_module_configure_button_display() {
        // Only super users may configure on the project level.
        return defined("SUPER_USER") && SUPER_USER == 1 ? true : null;
    }

    function redcap_survey_page_top() { 
        // get settings
        $settings = $this -> get_module_settings();

        // Get project status and purpose
        $project_values = Project::getProjectVals();

        // Initialise a bool to hide the banner by default
        $show_banner = false;

        // Test whether to show banner
        if (isset($project_values['purpose']) && $project_values['purpose'] == 0 && $settings['enable_practice_warning_survey']){
            $warning = $settings['practice_warning_survey_text'];
            $col = $settings['practice_warning_survey_col'] ?? "red";
            $show_banner = true;
        } elseif (isset($project_values['purpose']) && $project_values['purpose'] != 0 && isset($project_values['status']) && $project_values['status'] == 0 && $settings['enable_dev_warning_survey']) {
            $warning = $settings['dev_warning_survey_text'];
            $col = $settings['dev_warning_survey_col'] ?? "red";
            $show_banner = true;
        }

        // Build and show
        if ($show_banner) {
            echo "<div id='warning-banner' class='".$col."' style='margin:15px 15px 15px;'>
            <img src='". APP_PATH_IMAGES ."exclamation_red.png' alt=''>
            <span style='font-size:14px;'><b>".$this->tt("msg_warning").":</b> ".$warning."</span></div>";
        }
    }

    function redcap_every_page_top() {

        // get settings and project vals
        $settings = $this -> get_module_settings();

        // Get project status and purpose
        $project_values = Project::getProjectVals();

        // Initialise a bool to hide the banner by default
        $show_banner = false;

        // Test whether to show banner
        if (isset($project_values['purpose']) && $project_values['purpose'] == 0 && $settings['enable_practice_warning_user']){
            $warning = $settings['practice_warning_user_text'];
            $col = $settings['practice_warning_user_col'] ?? "red";
            $show_banner = true;
        } elseif (isset($project_values['purpose']) && $project_values['purpose'] != 0 && isset($project_values['status']) && $project_values['status'] == 0 && $settings['enable_dev_warning_user']) {
            $warning = $settings['dev_warning_user_text'];
            $col = $settings['dev_warning_user_col'] ?? "red";
            $show_banner = true;
        }

        if (PAGE === "Surveys/invite_participants.php" && !($_GET["email_log"]) == '1' && $show_banner){ // Don't run on the Survey Invitation Log page
            echo "<div id='warning-banner' class='".$col."' style='width:100%;max-width:902px;margin:15px 0px 15px;display:none;'>
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

        if (PAGE === "DataEntry/record_home.php" && $show_banner){ // Record home page.
            // Build and show
            if ($show_banner) {
                echo "<div id='warning-banner' class='".$col."' style='width:100%;max-width:700px;margin:15px 0px 15px;display:none;'>
                <img src='". APP_PATH_IMAGES ."exclamation_red.png' alt=''>
                <span style='font-size:14px;'><b>".$this->tt("msg_warning").":</b> ".$warning."</span></div>";
                echo "<script type='text/javascript'>
                    $(document).ready(function(){
                        var banner = $('div#warning-banner');
                        var targetDiv = $('div#record_display_name');
                        banner.detach().insertBefore(targetDiv);
                        banner.show();
                    });
                </script>";
            }
            // Convert existing yellow warning to selected colour class.
            if ($project_values['status'] == 0 && isset($settings['add_edit_col'])) {
                echo "<script type='text/javascript'>
                    $(document).ready(function(){
                    var warning = $('div.projhdr').siblings('div.yellow')
                    warning.children('img').attr('src','" . APP_PATH_IMAGES . "exclamation_red.png')
                    warning.removeClass('yellow').addClass('".$settings['add_edit_col']."')
                    });
                </script>";
            }
        }
    }
    
    function redcap_data_entry_form_top() {
        // get module settings
        $settings = $this -> get_module_settings();

        // Get project status and purpose
        $project_values = Project::getProjectVals();

        // Initialise a bool to hide the banner by default
        $show_banner = false;

        // Test whether to show banner
        if (isset($project_values['purpose']) && $project_values['purpose'] == 0 && $settings['enable_practice_warning_user']){
            $warning = $settings['practice_warning_user_text'];
            $col = $settings['practice_warning_user_col'] ?? "red";
            $show_banner = true;
        } elseif (isset($project_values['purpose']) && $project_values['purpose'] != 0 && isset($project_values['status']) && $project_values['status'] == 0 && $settings['enable_dev_warning_user']) {
            $warning = $settings['dev_warning_user_text'];
            $col = $settings['dev_warning_user_col'] ?? "red";
            $show_banner = true;
        }

        // Build and show
        if ($show_banner) {
            echo "<div id='warning-banner' class='".$col."' style='width:100%;max-width:790px;margin:15px 5px;display:none;'>
            <img src='". APP_PATH_IMAGES ."exclamation_red.png' alt=''>
            <span style='font-size:14px;'><b>".$this->tt("msg_warning").":</b> ".$warning."</span></div>";
            echo "<script type='text/javascript'>
                $(document).ready(function(){
                    var banner = $('div#warning-banner');
                    var targetDiv = $('div#form-title');
                    banner.detach().insertBefore(targetDiv);
                    banner.show();
                });
            </script>";
        }
    }
}
