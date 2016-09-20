<?php

/* @PrivacyManager/privacySettings.twig */
class __TwigTemplate_58982735c44cb972561f1b2b11cfe312a7a43356f13d9ddaf8ffb1dd85f34866 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("admin.twig", "@PrivacyManager/privacySettings.twig", 1);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "admin.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 3
        ob_start();
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_TeaserHeadline")), "html", null, true);
        $context["title"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 1
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        $context["piwik"] = $this->loadTemplate("macros.twig", "@PrivacyManager/privacySettings.twig", 6);
        // line 7
        if ((isset($context["isSuperUser"]) ? $context["isSuperUser"] : $this->getContext($context, "isSuperUser"))) {
            // line 8
            echo "    <h2 piwik-enriched-headline
        help-url=\"http://piwik.org/docs/privacy/\">";
            // line 9
            echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : $this->getContext($context, "title")), "html", null, true);
            echo "</h2>
    <p>";
            // line 10
            echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_Teaser", "<a href=\"#anonymizeIPAnchor\">", "</a>", "<a href=\"#deleteLogsAnchor\">", "</a>", "<a href=\"#optOutAnchor\">", "</a>"));
            echo "
        ";
            // line 11
            echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_SeeAlsoOurOfficialGuidePrivacy", "<a href=\"http://piwik.org/privacy/\" rel=\"noreferrer\"  target=\"_blank\">", "</a>"));
            echo "</p>

    <h2 id=\"anonymizeIPAnchor\">";
            // line 13
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_UseAnonymizeIp")), "html", null, true);
            echo "</h2>
    <form method=\"post\" action=\"";
            // line 14
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('urlRewriteWithParameters')->getCallable(), array(array("action" => "saveSettings", "form" => "formMaskLength", "token_auth" => (isset($context["token_auth"]) ? $context["token_auth"] : $this->getContext($context, "token_auth"))))), "html", null, true);
            echo "\" id=\"formMaskLength\">
        <div id=\"anonymizeIpSettings\" class=\"form-group\">
            <label>
                ";
            // line 17
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_UseAnonymizeIp")), "html", null, true);
            echo "
            </label>
            <div class=\"form-help\">
                ";
            // line 20
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_AnonymizeIpInlineHelp")), "html", null, true);
            echo "
                ";
            // line 21
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_AnonymizeIpDescription")), "html", null, true);
            echo "
            </div>
            <label class=\"radio\">
                <input id=\"anonymizeIPEnable-1\" type=\"radio\" name=\"anonymizeIPEnable\" value=\"1\" ";
            // line 24
            if (($this->getAttribute((isset($context["anonymizeIP"]) ? $context["anonymizeIP"] : $this->getContext($context, "anonymizeIP")), "enabled", array()) == "1")) {
                echo "checked ";
            }
            echo "/>
                ";
            // line 25
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
            echo "
            </label>
            <label class=\"radio\">
                <input class=\"indented-radio-button\" id=\"anonymizeIPEnable-0\" type=\"radio\" name=\"anonymizeIPEnable\" value=\"0\" ";
            // line 28
            if (($this->getAttribute((isset($context["anonymizeIP"]) ? $context["anonymizeIP"] : $this->getContext($context, "anonymizeIP")), "enabled", array()) == "0")) {
                echo " checked ";
            }
            echo "/>
                ";
            // line 29
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
            echo "
            </label>
            <input type=\"hidden\" name=\"token_auth\" value=\"";
            // line 31
            echo twig_escape_filter($this->env, (isset($context["token_auth"]) ? $context["token_auth"] : $this->getContext($context, "token_auth")), "html", null, true);
            echo "\"/>
        </div>
        <div id=\"anonymizeIPenabled\">
            <div class=\"form-group\">
                <label>
                    ";
            // line 36
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_AnonymizeIpMaskLengtDescription")), "html", null, true);
            echo "<br/>
                </label>
                <div class=\"form-help\">
                    ";
            // line 39
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_GeolocationAnonymizeIpNote")), "html", null, true);
            echo "
                </div>
                <label class=\"radio\">
                    <input id=\"maskLength-1\" type=\"radio\" name=\"maskLength\" value=\"1\" ";
            // line 42
            if (($this->getAttribute((isset($context["anonymizeIP"]) ? $context["anonymizeIP"] : $this->getContext($context, "anonymizeIP")), "maskLength", array()) == "1")) {
                echo " checked ";
            }
            echo "/>
                    ";
            // line 43
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_AnonymizeIpMaskLength", "1", "192.168.100.xxx")), "html", null, true);
            echo "
                </label>
                <label class=\"radio\">
                    <input id=\"maskLength-2\" type=\"radio\" name=\"maskLength\" value=\"2\" ";
            // line 46
            if (($this->getAttribute((isset($context["anonymizeIP"]) ? $context["anonymizeIP"] : $this->getContext($context, "anonymizeIP")), "maskLength", array()) == "2")) {
                echo " checked ";
            }
            echo "/>
                    ";
            // line 47
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_AnonymizeIpMaskLength", "2", "192.168.xxx.xxx")), "html", null, true);
            echo "
                    <span class=\"form-description\">";
            // line 48
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Recommended")), "html", null, true);
            echo "</span>
                </label>
                <label class=\"radio\">
                    <input id=\"maskLength-3\" type=\"radio\" name=\"maskLength\" value=\"3\" ";
            // line 51
            if (($this->getAttribute((isset($context["anonymizeIP"]) ? $context["anonymizeIP"] : $this->getContext($context, "anonymizeIP")), "maskLength", array()) == "3")) {
                echo " checked ";
            }
            echo "/>
                    ";
            // line 52
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_AnonymizeIpMaskLength", "3", "192.xxx.xxx.xxx")), "html", null, true);
            echo "
                </label>
            </div>
            <div class=\"form-group\">
                <label>
                    ";
            // line 57
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_UseAnonymizedIpForVisitEnrichment")), "html", null, true);
            echo "<br/>
                </label>
                <div class=\"form-help\">
                    ";
            // line 60
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_UseAnonymizedIpForVisitEnrichmentNote")), "html", null, true);
            echo "
                </div>
                <label class=\"radio\">
                    <input id=\"useAnonymizedIpForVisitEnrichment-1\" type=\"radio\" name=\"useAnonymizedIpForVisitEnrichment\" value=\"1\" ";
            // line 63
            if (($this->getAttribute((isset($context["anonymizeIP"]) ? $context["anonymizeIP"] : $this->getContext($context, "anonymizeIP")), "useAnonymizedIpForVisitEnrichment", array()) == "1")) {
                echo "checked ";
            }
            echo "/>
                    ";
            // line 64
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
            echo "
                    <span class=\"form-description\">";
            // line 65
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_RecommendedForPrivacy")), "html", null, true);
            echo "</span>
                </label>
                <label class=\"radio\">
                    <input id=\"useAnonymizedIpForVisitEnrichment-2\" type=\"radio\" name=\"useAnonymizedIpForVisitEnrichment\" value=\"0\" ";
            // line 68
            if (($this->getAttribute((isset($context["anonymizeIP"]) ? $context["anonymizeIP"] : $this->getContext($context, "anonymizeIP")), "useAnonymizedIpForVisitEnrichment", array()) == "0")) {
                echo " checked ";
            }
            echo "/>
                    ";
            // line 69
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
            echo "
                </label>
            </div>
        </div>

        <input type=\"hidden\" name=\"nonce\" value=\"";
            // line 74
            if ($this->getAttribute((isset($context["anonymizeIP"]) ? $context["anonymizeIP"] : $this->getContext($context, "anonymizeIP")), "enabled", array())) {
                echo twig_escape_filter($this->env, (isset($context["deactivateNonce"]) ? $context["deactivateNonce"] : $this->getContext($context, "deactivateNonce")), "html", null, true);
            } else {
                echo twig_escape_filter($this->env, (isset($context["activateNonce"]) ? $context["activateNonce"] : $this->getContext($context, "activateNonce")), "html", null, true);
            }
            echo "\">
        <input type=\"submit\" value=\"";
            // line 75
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Save")), "html", null, true);
            echo "\" id=\"privacySettingsSubmit\"/>
    </form>

    ";
            // line 78
            if ((isset($context["isDataPurgeSettingsEnabled"]) ? $context["isDataPurgeSettingsEnabled"] : $this->getContext($context, "isDataPurgeSettingsEnabled"))) {
                // line 79
                echo "
        <div class=\"ui-confirm\" id=\"confirmDeleteSettings\">
            <h2 id=\"deleteLogsConfirm\">";
                // line 81
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteLogsConfirm")), "html", null, true);
                echo "</h2>

            <h2 id=\"deleteReportsConfirm\">";
                // line 83
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteReportsConfirm")), "html", null, true);
                echo "</h2>

            <h2 id=\"deleteBothConfirm\">";
                // line 85
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteBothConfirm")), "html", null, true);
                echo "</h2>
            <input role=\"yes\" type=\"button\" value=\"";
                // line 86
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
                echo "\"/>
            <input role=\"no\" type=\"button\" value=\"";
                // line 87
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
                echo "\"/>
        </div>
        <div class=\"ui-confirm\" id=\"saveSettingsBeforePurge\">
            <h2>";
                // line 90
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_SaveSettingsBeforePurge")), "html", null, true);
                echo "</h2>
            <input role=\"yes\" type=\"button\" value=\"";
                // line 91
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Ok")), "html", null, true);
                echo "\"/>
        </div>
        <div class=\"ui-confirm\" id=\"confirmPurgeNow\">
            <h2>";
                // line 94
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_PurgeNowConfirm")), "html", null, true);
                echo "</h2>
            <input role=\"yes\" type=\"button\" value=\"";
                // line 95
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
                echo "\"/>
            <input role=\"no\" type=\"button\" value=\"";
                // line 96
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
                echo "\"/>
        </div>
        <h2 id=\"deleteLogsAnchor\">";
                // line 98
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteOldVisitorLogs")), "html", null, true);
                echo "</h2>
        <p>";
                // line 99
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteDataDescription")), "html", null, true);
                echo " ";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteDataDescription2")), "html", null, true);
                echo "</p>
        <form method=\"post\" action=\"";
                // line 100
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('urlRewriteWithParameters')->getCallable(), array(array("action" => "saveSettings", "form" => "formDeleteSettings", "token_auth" => (isset($context["token_auth"]) ? $context["token_auth"] : $this->getContext($context, "token_auth"))))), "html", null, true);
                echo "\" id=\"formDeleteSettings\">
            <div id=\"deleteLogSettingEnabled\" class=\"form-group\">
                <label>
                    ";
                // line 103
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_UseDeleteLog")), "html", null, true);
                echo "
                </label>
                <div class=\"form-help\">
                    ";
                // line 106
                echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteLogInfo", $this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "deleteTables", array())));
                echo "
                    ";
                // line 107
                if ( !(isset($context["canDeleteLogActions"]) ? $context["canDeleteLogActions"] : $this->getContext($context, "canDeleteLogActions"))) {
                    // line 108
                    echo "                        <br/>
                        <br/>
                        ";
                    // line 110
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_CannotLockSoDeleteLogActions", (isset($context["dbUser"]) ? $context["dbUser"] : $this->getContext($context, "dbUser")))), "html", null, true);
                    echo "
                    ";
                }
                // line 112
                echo "                </div>
                <label class=\"radio\">
                    <input id=\"deleteEnable-1\" type=\"radio\" name=\"deleteEnable\" value=\"1\" ";
                // line 114
                if (($this->getAttribute($this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "config", array()), "delete_logs_enable", array()) == "1")) {
                    echo " checked ";
                }
                echo "/>
                    ";
                // line 115
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
                echo "
                </label>
                <label class=\"radio\">
                    <input class=\"indented-radio-button\" id=\"deleteEnable-2\" type=\"radio\" name=\"deleteEnable\" value=\"0\"
                            ";
                // line 119
                if (($this->getAttribute($this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "config", array()), "delete_logs_enable", array()) == "0")) {
                    echo " checked ";
                }
                echo "/>
                    ";
                // line 120
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
                echo "
                </label>
                <div class=\"clearfix\"><br/></div>
                <div class=\"alert alert-warning\" style=\"width: 40%;\">
                    ";
                // line 124
                echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteLogDescription2"));
                echo "
                    <a href=\"http://piwik.org/faq/general/#faq_125\" rel=\"noreferrer\"  target=\"_blank\">
                        ";
                // line 126
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_ClickHere")), "html", null, true);
                echo "
                    </a>
                </div>
            </div>

            <div id=\"deleteLogSettings\" class=\"form-group\">
                <label for=\"deleteOlderThan\">
                    ";
                // line 133
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteLogsOlderThan")), "html", null, true);
                echo "
                </label>
                <div class=\"form-help\">
                    ";
                // line 136
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_LeastDaysInput", "1")), "html", null, true);
                echo "
                </div>
                <div class=\"input-group\">
                    <input type=\"text\" id=\"deleteOlderThan\" value=\"";
                // line 139
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "config", array()), "delete_logs_older_than", array()), "html", null, true);
                echo "\" name=\"deleteOlderThan\"/>
                    <span class=\"input-group-addon\">";
                // line 140
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Intl_PeriodDays")), "html", null, true);
                echo "</span>
                </div>
            </div>

            <h2 id=\"deleteReportsAnchor\" class=\"secondary\">";
                // line 144
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteOldArchivedReports")), "html", null, true);
                echo "</h2>

            <div id=\"deleteReportsSettingEnabled\" class=\"form-group\">
                <label>
                    ";
                // line 148
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_UseDeleteReports")), "html", null, true);
                echo "
                </label>
                <div class=\"form-help\">
                    ";
                // line 151
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteReportsDetailedInfo", "archive_numeric_*", "archive_blob_*")), "html", null, true);
                echo "
                </div>
                <label class=\"radio\">
                    <input id=\"deleteReportsEnable-1\" type=\"radio\" name=\"deleteReportsEnable\" value=\"1\" ";
                // line 154
                if (($this->getAttribute($this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "config", array()), "delete_reports_enable", array()) == "1")) {
                    echo "checked=\"checked\"";
                }
                echo " />
                    ";
                // line 155
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
                echo "
                </label>
                <label class=\"radio\">
                    <input class=\"indented-radio-button\" id=\"deleteReportsEnable-2\" type=\"radio\" name=\"deleteReportsEnable\" value=\"0\" ";
                // line 158
                if (($this->getAttribute($this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "config", array()), "delete_reports_enable", array()) == "0")) {
                    echo "checked=\"checked\"";
                }
                echo "/>
                    ";
                // line 159
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
                echo "
                </label>
                <div class=\"clearfix\"><br/></div>
                <div class=\"alert alert-warning\" style=\"width: 40%;\">
                    ";
                // line 163
                ob_start();
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_UseDeleteLog")), "html", null, true);
                $context["deleteOldLogs"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
                // line 164
                echo "                    ";
                echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteReportsInfo", "<em>", "</em>"));
                echo "
                    <span id='deleteOldReportsMoreInfo'><br/><br/>
                        ";
                // line 166
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteReportsInfo2", (isset($context["deleteOldLogs"]) ? $context["deleteOldLogs"] : $this->getContext($context, "deleteOldLogs")))), "html", null, true);
                echo "<br/><br/>
                        ";
                // line 167
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteReportsInfo3", (isset($context["deleteOldLogs"]) ? $context["deleteOldLogs"] : $this->getContext($context, "deleteOldLogs")))), "html", null, true);
                echo "</span>
                </div>
            </div>

            <div id=\"deleteReportsSettings\">
                <div class=\"form-group\">
                    <label for=\"deleteReportsOlderThan\">
                        ";
                // line 174
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteReportsOlderThan")), "html", null, true);
                echo "
                    </label>
                    <div class=\"form-help\">
                        ";
                // line 177
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_LeastMonthsInput", "3")), "html", null, true);
                echo "
                    </div>
                    <div class=\"input-group\">
                        <input type=\"text\" id=\"deleteReportsOlderThan\" value=\"";
                // line 180
                echo twig_escape_filter($this->env, $this->getAttribute($this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "config", array()), "delete_reports_older_than", array()), "html", null, true);
                echo "\" name=\"deleteReportsOlderThan\"/>
                        <span class=\"input-group-addon\">";
                // line 181
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Intl_PeriodMonths")), "html", null, true);
                echo "</span>
                    </div>
                </div>
                <div class=\"form-group\">
                    <label class=\"checkbox\">
                        <input id=\"deleteReportsKeepBasic\" type=\"checkbox\" name=\"deleteReportsKeepBasic\" value=\"1\"
                               ";
                // line 187
                if ($this->getAttribute($this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "config", array()), "delete_reports_keep_basic_metrics", array())) {
                    echo "checked=\"checked\"";
                }
                echo ">
                        ";
                // line 188
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_KeepBasicMetrics")), "html", null, true);
                echo "
                        <span class=\"form-description\">";
                // line 189
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Recommended")), "html", null, true);
                echo "</span>
                    </label>
                </div>
                <h3>
                    ";
                // line 193
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_KeepDataFor")), "html", null, true);
                echo "
                </h3>
                <div class=\"form-group\">
                    <label class=\"checkbox\">
                        <input id=\"deleteReportsKeepDay\" type=\"checkbox\" name=\"deleteReportsKeepDay\" value=\"1\"
                               ";
                // line 198
                if ($this->getAttribute($this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "config", array()), "delete_reports_keep_day_reports", array())) {
                    echo "checked=\"checked\"";
                }
                echo ">
                        ";
                // line 199
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_DailyReports")), "html", null, true);
                echo "
                    </label>
                    <label class=\"checkbox\">
                        <input type=\"checkbox\" name=\"deleteReportsKeepWeek\" value=\"1\" id=\"deleteReportsKeepWeek\"
                               ";
                // line 203
                if ($this->getAttribute($this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "config", array()), "delete_reports_keep_week_reports", array())) {
                    echo "checked=\"checked\"";
                }
                echo ">
                        ";
                // line 204
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_WeeklyReports")), "html", null, true);
                echo "
                    </label>
                    <label class=\"checkbox\">
                        <input type=\"checkbox\" name=\"deleteReportsKeepMonth\" value=\"1\" id=\"deleteReportsKeepMonth\"
                               ";
                // line 208
                if ($this->getAttribute($this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "config", array()), "delete_reports_keep_month_reports", array())) {
                    echo "checked=\"checked\"";
                }
                echo ">
                        ";
                // line 209
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_MonthlyReports")), "html", null, true);
                echo "
                        <span class=\"form-description\">";
                // line 210
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Recommended")), "html", null, true);
                echo "</span>
                    </label>
                    <label class=\"checkbox\">
                        <input type=\"checkbox\" name=\"deleteReportsKeepYear\" value=\"1\" id=\"deleteReportsKeepYear\"
                               ";
                // line 214
                if ($this->getAttribute($this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "config", array()), "delete_reports_keep_year_reports", array())) {
                    echo "checked=\"checked\"";
                }
                echo ">
                        ";
                // line 215
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_YearlyReports")), "html", null, true);
                echo "
                        <span class=\"form-description\">";
                // line 216
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Recommended")), "html", null, true);
                echo "</span>
                    </label>
                    <label class=\"checkbox\">
                        <input type=\"checkbox\" name=\"deleteReportsKeepRange\" value=\"1\" id=\"deleteReportsKeepRange\"
                               ";
                // line 220
                if ($this->getAttribute($this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "config", array()), "delete_reports_keep_range_reports", array())) {
                    echo "checked=\"checked\"";
                }
                echo ">
                        ";
                // line 221
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_RangeReports")), "html", null, true);
                echo "
                    </label>
                    <label class=\"checkbox\">
                        <input type=\"checkbox\" name=\"deleteReportsKeepSegments\" value=\"1\" id=\"deleteReportsKeepSegments\"
                               ";
                // line 225
                if ($this->getAttribute($this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "config", array()), "delete_reports_keep_segment_reports", array())) {
                    echo "checked=\"checked\"";
                }
                echo ">
                        ";
                // line 226
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_KeepReportSegments")), "html", null, true);
                echo "
                    </label>
                </div>
            </div>


            <h2 for=\"deleteLowestInterval\" id=\"scheduleSettingsHeadline\">
                ";
                // line 233
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteSchedulingSettings")), "html", null, true);
                echo "
            </h2>
            <div id=\"deleteSchedulingSettings\" class=\"form-group\">
                <div class=\"form-help\">
                    ";
                // line 237
                if ($this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "lastRun", array())) {
                    echo "<strong>";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_LastDelete")), "html", null, true);
                    echo ":</strong>
                        ";
                    // line 238
                    echo twig_escape_filter($this->env, $this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "lastRunPretty", array()), "html", null, true);
                    echo "
                        <br/>
                        <br/>
                    ";
                }
                // line 242
                echo "                    <strong>";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_NextDelete")), "html", null, true);
                echo ":</strong>
                    ";
                // line 243
                echo $this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "nextRunPretty", array());
                echo "
                    <br/>
                    <br/>
                    <em><a id=\"purgeDataNowLink\" href=\"#\">";
                // line 246
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_PurgeNow")), "html", null, true);
                echo "</a></em>
                        <span class=\"loadingPiwik\" style=\"display:none;\"><img
                                    src=\"./plugins/Morpheus/images/loading-blue.gif\"/> ";
                // line 248
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_PurgingData")), "html", null, true);
                echo "</span>
                    <span id=\"db-purged-message\" style=\"display: none;\"><em>";
                // line 249
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DBPurged")), "html", null, true);
                echo "</em></span>
                </div>

                <label>";
                // line 252
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteDataInterval")), "html", null, true);
                echo "</label>
                <select id=\"deleteLowestInterval\" name=\"deleteLowestInterval\">
                    <option ";
                // line 254
                if (($this->getAttribute($this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "config", array()), "delete_logs_schedule_lowest_interval", array()) == "1")) {
                    echo " selected=\"selected\" ";
                }
                // line 255
                echo "                            value=\"1\"> ";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Intl_PeriodDay")), "html", null, true);
                echo "</option>
                    <option ";
                // line 256
                if (($this->getAttribute($this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "config", array()), "delete_logs_schedule_lowest_interval", array()) == "7")) {
                    echo " selected=\"selected\" ";
                }
                // line 257
                echo "                            value=\"7\">";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Intl_PeriodWeek")), "html", null, true);
                echo "</option>
                    <option ";
                // line 258
                if (($this->getAttribute($this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "config", array()), "delete_logs_schedule_lowest_interval", array()) == "30")) {
                    echo " selected=\"selected\" ";
                }
                // line 259
                echo "                            value=\"30\">";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Intl_PeriodMonth")), "html", null, true);
                echo "</option>
                </select>
            </div>

            <h3 id=\"databaseSizeHeadline\">
                ";
                // line 264
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_ReportsDataSavedEstimate")), "html", null, true);
                echo "
            </h3>
            <div ";
                // line 266
                if ((($this->getAttribute($this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "config", array()), "delete_reports_enable", array()) == "0") && ($this->getAttribute($this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "config", array()), "delete_logs_enable", array()) == "0"))) {
                    echo "style=\"display:none;\"";
                }
                // line 267
                echo "                 id=\"deleteDataEstimateSect\" class=\"form-group\">
                ";
                // line 268
                if (($this->getAttribute($this->getAttribute((isset($context["deleteData"]) ? $context["deleteData"] : $this->getContext($context, "deleteData")), "config", array()), "enable_auto_database_size_estimate", array()) == "0")) {
                    // line 269
                    echo "                    <div class=\"form-help\">
                        <a id=\"getPurgeEstimateLink\" href=\"#\">";
                    // line 270
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_GetPurgeEstimate")), "html", null, true);
                    echo "</a>
                    </div>
                ";
                }
                // line 273
                echo "                <div id=\"deleteDataEstimate\"></div>
                <span class=\"loadingPiwik\" style=\"display:none;\">
                    <img src=\"./plugins/Morpheus/images/loading-blue.gif\"/> ";
                // line 275
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_LoadingData")), "html", null, true);
                echo "
                </span>
            </div>

            <input type=\"button\" value=\"";
                // line 279
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Save")), "html", null, true);
                echo "\" id=\"deleteLogSettingsSubmit\" class=\"submit\"/>

        </form>

    ";
            }
            // line 284
            echo "
    <h2 id=\"DNT\">";
            // line 285
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DoNotTrack_SupportDNTPreference")), "html", null, true);
            echo "</h2>
    <p>
        ";
            // line 287
            if ((isset($context["dntSupport"]) ? $context["dntSupport"] : $this->getContext($context, "dntSupport"))) {
                // line 288
                echo "            ";
                $context["action"] = "deactivateDoNotTrack";
                // line 289
                echo "            ";
                $context["nonce"] = (isset($context["deactivateNonce"]) ? $context["deactivateNonce"] : $this->getContext($context, "deactivateNonce"));
                // line 290
                echo "            <strong>";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DoNotTrack_Enabled")), "html", null, true);
                echo "</strong>
            <br/>
            ";
                // line 292
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DoNotTrack_EnabledMoreInfo")), "html", null, true);
                echo "
        ";
            } else {
                // line 294
                echo "            ";
                $context["action"] = "activateDoNotTrack";
                // line 295
                echo "            ";
                $context["nonce"] = (isset($context["activateNonce"]) ? $context["activateNonce"] : $this->getContext($context, "activateNonce"));
                // line 296
                echo "            ";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DoNotTrack_Disabled")), "html", null, true);
                echo " ";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DoNotTrack_DisabledMoreInfo")), "html", null, true);
                echo "
        ";
            }
            // line 298
            echo "    </p>
    <div class=\"form-group\">
        <div class=\"form-help\">
            ";
            // line 301
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DoNotTrack_Description")), "html", null, true);
            echo "
        </div>
        <a class=\"btn\" href='";
            // line 303
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('urlRewriteWithParameters')->getCallable(), array(array("module" => "PrivacyManager", "nonce" => (isset($context["nonce"]) ? $context["nonce"] : $this->getContext($context, "nonce")), "action" => (isset($context["action"]) ? $context["action"] : $this->getContext($context, "action"))))), "html", null, true);
            echo "#DNT'>
            ";
            // line 304
            if ((isset($context["dntSupport"]) ? $context["dntSupport"] : $this->getContext($context, "dntSupport"))) {
                // line 305
                echo "                ";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DoNotTrack_Disable")), "html", null, true);
                echo "
            ";
            } else {
                // line 307
                echo "                ";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DoNotTrack_Enable")), "html", null, true);
                echo "
            ";
            }
            // line 309
            echo "        </a>

        ";
            // line 311
            if ((isset($context["dntSupport"]) ? $context["dntSupport"] : $this->getContext($context, "dntSupport"))) {
                // line 312
                echo "            (";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_NotRecommended")), "html", null, true);
                echo ")
        ";
            } else {
                // line 314
                echo "            (";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Recommended")), "html", null, true);
                echo ")
        ";
            }
            // line 316
            echo "    </div>
";
        }
        // line 318
        echo "
<h2 id=\"optOutAnchor\">";
        // line 319
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_OptOutForYourVisitors")), "html", null, true);
        echo "</h2>
<p>
    ";
        // line 321
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_OptOutExplanation")), "html", null, true);
        echo "
    ";
        // line 322
        ob_start();
        echo twig_escape_filter($this->env, (isset($context["piwikUrl"]) ? $context["piwikUrl"] : $this->getContext($context, "piwikUrl")), "html", null, true);
        echo "index.php?module=CoreAdminHome&action=optOut&language=";
        echo twig_escape_filter($this->env, (isset($context["language"]) ? $context["language"] : $this->getContext($context, "language")), "html", null, true);
        $context["optOutUrl"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 323
        echo "    ";
        ob_start();
        echo "<iframe style=\"border: 0; height: 200px; width: 600px;\" src=\"";
        echo twig_escape_filter($this->env, (isset($context["optOutUrl"]) ? $context["optOutUrl"] : $this->getContext($context, "optOutUrl")), "html", null, true);
        echo "\"></iframe>";
        $context["iframeOptOut"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 324
        echo "</p>
<pre>";
        // line 325
        echo twig_escape_filter($this->env, (isset($context["iframeOptOut"]) ? $context["iframeOptOut"] : $this->getContext($context, "iframeOptOut")), "html");
        echo "</pre>
<p>
    ";
        // line 327
        echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_OptOutExplanationBis", (("<a href='" . (isset($context["optOutUrl"]) ? $context["optOutUrl"] : $this->getContext($context, "optOutUrl"))) . "' rel='noreferrer' target='_blank'>"), "</a>"));
        echo "
</p>

<div style=\"height:100px;\"></div>
";
    }

    public function getTemplateName()
    {
        return "@PrivacyManager/privacySettings.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  836 => 327,  831 => 325,  828 => 324,  821 => 323,  815 => 322,  811 => 321,  806 => 319,  803 => 318,  799 => 316,  793 => 314,  787 => 312,  785 => 311,  781 => 309,  775 => 307,  769 => 305,  767 => 304,  763 => 303,  758 => 301,  753 => 298,  745 => 296,  742 => 295,  739 => 294,  734 => 292,  728 => 290,  725 => 289,  722 => 288,  720 => 287,  715 => 285,  712 => 284,  704 => 279,  697 => 275,  693 => 273,  687 => 270,  684 => 269,  682 => 268,  679 => 267,  675 => 266,  670 => 264,  661 => 259,  657 => 258,  652 => 257,  648 => 256,  643 => 255,  639 => 254,  634 => 252,  628 => 249,  624 => 248,  619 => 246,  613 => 243,  608 => 242,  601 => 238,  595 => 237,  588 => 233,  578 => 226,  572 => 225,  565 => 221,  559 => 220,  552 => 216,  548 => 215,  542 => 214,  535 => 210,  531 => 209,  525 => 208,  518 => 204,  512 => 203,  505 => 199,  499 => 198,  491 => 193,  484 => 189,  480 => 188,  474 => 187,  465 => 181,  461 => 180,  455 => 177,  449 => 174,  439 => 167,  435 => 166,  429 => 164,  425 => 163,  418 => 159,  412 => 158,  406 => 155,  400 => 154,  394 => 151,  388 => 148,  381 => 144,  374 => 140,  370 => 139,  364 => 136,  358 => 133,  348 => 126,  343 => 124,  336 => 120,  330 => 119,  323 => 115,  317 => 114,  313 => 112,  308 => 110,  304 => 108,  302 => 107,  298 => 106,  292 => 103,  286 => 100,  280 => 99,  276 => 98,  271 => 96,  267 => 95,  263 => 94,  257 => 91,  253 => 90,  247 => 87,  243 => 86,  239 => 85,  234 => 83,  229 => 81,  225 => 79,  223 => 78,  217 => 75,  209 => 74,  201 => 69,  195 => 68,  189 => 65,  185 => 64,  179 => 63,  173 => 60,  167 => 57,  159 => 52,  153 => 51,  147 => 48,  143 => 47,  137 => 46,  131 => 43,  125 => 42,  119 => 39,  113 => 36,  105 => 31,  100 => 29,  94 => 28,  88 => 25,  82 => 24,  76 => 21,  72 => 20,  66 => 17,  60 => 14,  56 => 13,  51 => 11,  47 => 10,  43 => 9,  40 => 8,  38 => 7,  36 => 6,  33 => 5,  29 => 1,  25 => 3,  11 => 1,);
    }
}
