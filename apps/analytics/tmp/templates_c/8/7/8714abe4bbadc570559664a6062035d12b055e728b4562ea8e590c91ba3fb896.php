<?php

/* @CoreAdminHome/generalSettings.twig */
class __TwigTemplate_8714abe4bbadc570559664a6062035d12b055e728b4562ea8e590c91ba3fb896 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("admin.twig", "@CoreAdminHome/generalSettings.twig", 1);
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
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_MenuGeneralSettings")), "html", null, true);
        $context["title"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 1
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "    ";
        $context["piwik"] = $this->loadTemplate("macros.twig", "@CoreAdminHome/generalSettings.twig", 6);
        // line 7
        echo "    ";
        $context["ajax"] = $this->loadTemplate("ajaxMacros.twig", "@CoreAdminHome/generalSettings.twig", 7);
        // line 8
        echo "
    ";
        // line 9
        echo $context["ajax"]->geterrorDiv();
        echo "
    ";
        // line 10
        echo $context["ajax"]->getloadingDiv();
        echo "

    <h2>";
        // line 12
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_ArchivingSettings")), "html", null, true);
        echo "</h2>

    ";
        // line 14
        if ((isset($context["isGeneralSettingsAdminEnabled"]) ? $context["isGeneralSettingsAdminEnabled"] : $this->getContext($context, "isGeneralSettingsAdminEnabled"))) {
            // line 15
            echo "        <div class=\"form-group\">
            <label>";
            // line 16
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_AllowPiwikArchivingToTriggerBrowser")), "html", null, true);
            echo "</label>
            <div class=\"form-help\">
                ";
            // line 18
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_ArchivingInlineHelp")), "html", null, true);
            echo "
                <br/>
                ";
            // line 20
            echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_SeeTheOfficialDocumentationForMoreInformation", "<a href='?module=Proxy&action=redirect&url=http://piwik.org/docs/setup-auto-archiving/' target='_blank'>", "</a>"));
            echo "
            </div>
            <label class=\"radio\">
                <input type=\"radio\" value=\"1\" name=\"enableBrowserTriggerArchiving\" ";
            // line 23
            if (((isset($context["enableBrowserTriggerArchiving"]) ? $context["enableBrowserTriggerArchiving"] : $this->getContext($context, "enableBrowserTriggerArchiving")) == 1)) {
                echo " checked=\"checked\"";
            }
            echo " />
                ";
            // line 24
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
            echo "
                <span class=\"form-description\">";
            // line 25
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Default")), "html", null, true);
            echo "</span>
            </label>
            <label class=\"radio\">
                <input type=\"radio\" value=\"0\" name=\"enableBrowserTriggerArchiving\" ";
            // line 28
            if (((isset($context["enableBrowserTriggerArchiving"]) ? $context["enableBrowserTriggerArchiving"] : $this->getContext($context, "enableBrowserTriggerArchiving")) == 0)) {
                echo " checked=\"checked\"";
            }
            echo " />
                ";
            // line 29
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
            echo "
                <span class=\"form-description\">";
            // line 30
            echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_ArchivingTriggerDescription", "<a href='?module=Proxy&action=redirect&url=http://piwik.org/docs/setup-auto-archiving/' target='_blank'>", "</a>"));
            echo "</span>
            </label>
        </div>
    ";
        } else {
            // line 34
            echo "        <div class=\"form-group\">
            <label>";
            // line 35
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_AllowPiwikArchivingToTriggerBrowser")), "html", null, true);
            echo "</label>
            <label class=\"radio\">
                <input type=\"radio\" checked=\"checked\" disabled=\"disabled\" />
                ";
            // line 38
            if (((isset($context["enableBrowserTriggerArchiving"]) ? $context["enableBrowserTriggerArchiving"] : $this->getContext($context, "enableBrowserTriggerArchiving")) == 1)) {
                // line 39
                echo "                    ";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
                echo "
                ";
            } else {
                // line 41
                echo "                    ";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
                echo "
                ";
            }
            // line 43
            echo "            </label>
        </div>
    ";
        }
        // line 46
        echo "
    <div class=\"form-group\">
        <label for=\"todayArchiveTimeToLive\">
            ";
        // line 49
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_ReportsContainingTodayWillBeProcessedAtMostEvery")), "html", null, true);
        echo "
        </label>
        ";
        // line 51
        if ((isset($context["isGeneralSettingsAdminEnabled"]) ? $context["isGeneralSettingsAdminEnabled"] : $this->getContext($context, "isGeneralSettingsAdminEnabled"))) {
            // line 52
            echo "            <div class=\"form-help\">
                ";
            // line 53
            if ((isset($context["showWarningCron"]) ? $context["showWarningCron"] : $this->getContext($context, "showWarningCron"))) {
                // line 54
                echo "                    <strong>
                        ";
                // line 55
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_NewReportsWillBeProcessedByCron")), "html", null, true);
                echo "<br/>
                        ";
                // line 56
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_ReportsWillBeProcessedAtMostEveryHour")), "html", null, true);
                echo "
                        ";
                // line 57
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_IfArchivingIsFastYouCanSetupCronRunMoreOften")), "html", null, true);
                echo "<br/>
                    </strong>
                ";
            }
            // line 60
            echo "                ";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_SmallTrafficYouCanLeaveDefault", (isset($context["todayArchiveTimeToLiveDefault"]) ? $context["todayArchiveTimeToLiveDefault"] : $this->getContext($context, "todayArchiveTimeToLiveDefault")))), "html", null, true);
            echo "
                <br/>
                ";
            // line 62
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_MediumToHighTrafficItIsRecommendedTo", 1800, 3600)), "html", null, true);
            echo "
            </div>
        ";
        }
        // line 65
        echo "        <div class=\"input-group\">
            <input value='";
        // line 66
        echo twig_escape_filter($this->env, (isset($context["todayArchiveTimeToLive"]) ? $context["todayArchiveTimeToLive"] : $this->getContext($context, "todayArchiveTimeToLive")), "html", null, true);
        echo "' id='todayArchiveTimeToLive' ";
        if ( !(isset($context["isGeneralSettingsAdminEnabled"]) ? $context["isGeneralSettingsAdminEnabled"] : $this->getContext($context, "isGeneralSettingsAdminEnabled"))) {
            echo "disabled=\"disabled\"";
        }
        echo " />
            <span class=\"input-group-addon\">";
        // line 67
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Intl_NSeconds", "")), "html", null, true);
        echo "</span>
        </div>
        <span class=\"form-description\">
            ";
        // line 70
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_RearchiveTimeIntervalOnlyForTodayReports")), "html", null, true);
        echo "
        </span>
    </div>

    ";
        // line 74
        if ((isset($context["isGeneralSettingsAdminEnabled"]) ? $context["isGeneralSettingsAdminEnabled"] : $this->getContext($context, "isGeneralSettingsAdminEnabled"))) {
            // line 75
            echo "        <h2>";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_UpdateSettings")), "html", null, true);
            echo "</h2>

        <div class=\"form-group\">
            <label>";
            // line 78
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_ReleaseChannel")), "html", null, true);
            echo "</label>
            <div class=\"form-help\">
                ";
            // line 80
            echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_DevelopmentProcess", "<a href='?module=Proxy&action=redirect&url=http://piwik.org/participate/development-process/' target='_blank'>", "</a>"));
            echo "
                <br/>
                ";
            // line 82
            echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_StableReleases", "<a href='?module=Proxy&action=redirect&url=http%3A%2F%2Fdeveloper.piwik.org%2Fguides%2Fcore-team-workflow%23influencing-piwik-development' target='_blank'>", "</a>"));
            echo "
                <br />
                ";
            // line 84
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_LtsReleases")), "html", null, true);
            echo "
            </div>
            ";
            // line 86
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["releaseChannels"]) ? $context["releaseChannels"] : $this->getContext($context, "releaseChannels")));
            foreach ($context['_seq'] as $context["_key"] => $context["releaseChannel"]) {
                // line 87
                echo "                <label class=\"radio\">
                    <input type=\"radio\" value=\"";
                // line 88
                echo twig_escape_filter($this->env, $this->getAttribute($context["releaseChannel"], "id", array()), "html_attr");
                echo "\" name=\"releaseChannel\"";
                if ($this->getAttribute($context["releaseChannel"], "active", array())) {
                    echo " checked=\"checked\"";
                }
                echo " />
                    ";
                // line 89
                echo twig_escape_filter($this->env, $this->getAttribute($context["releaseChannel"], "name", array()), "html", null, true);
                echo "
                    ";
                // line 90
                if ($this->getAttribute($context["releaseChannel"], "description", array())) {
                    // line 91
                    echo "                    <span class=\"form-description\">";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["releaseChannel"], "description", array()), "html", null, true);
                    echo "</span>
                    ";
                }
                // line 93
                echo "                </label>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['releaseChannel'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 95
            echo "        </div>

        ";
            // line 97
            if ((isset($context["canUpdateCommunication"]) ? $context["canUpdateCommunication"] : $this->getContext($context, "canUpdateCommunication"))) {
                // line 98
                echo "            <div class=\"form-group\">
                <label>";
                // line 99
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_SendPluginUpdateCommunication")), "html", null, true);
                echo "</label>
                <div class=\"form-help\">
                    ";
                // line 101
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_SendPluginUpdateCommunicationHelp")), "html", null, true);
                echo "
                </div>
                <label class=\"radio\">
                    <input type=\"radio\" name=\"enablePluginUpdateCommunication\" value=\"1\"
                            ";
                // line 105
                if (((isset($context["enableSendPluginUpdateCommunication"]) ? $context["enableSendPluginUpdateCommunication"] : $this->getContext($context, "enableSendPluginUpdateCommunication")) == 1)) {
                    echo " checked=\"checked\"";
                }
                echo "/>
                    ";
                // line 106
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
                echo "
                </label>
                <label class=\"radio\">
                    <input type=\"radio\" name=\"enablePluginUpdateCommunication\" value=\"0\"
                            ";
                // line 110
                if (((isset($context["enableSendPluginUpdateCommunication"]) ? $context["enableSendPluginUpdateCommunication"] : $this->getContext($context, "enableSendPluginUpdateCommunication")) == 0)) {
                    echo " checked=\"checked\"";
                }
                echo "/>
                    ";
                // line 111
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
                echo "
                    <span class=\"form-description\">";
                // line 112
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Default")), "html", null, true);
                echo "</span>
                </label>
            </div>
        ";
            }
            // line 116
            echo "    ";
        }
        // line 117
        echo "
    ";
        // line 118
        if ((isset($context["isGeneralSettingsAdminEnabled"]) ? $context["isGeneralSettingsAdminEnabled"] : $this->getContext($context, "isGeneralSettingsAdminEnabled"))) {
            // line 119
            echo "        <h2>";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_EmailServerSettings")), "html", null, true);
            echo "</h2>

        <div class=\"form-group\">
            <label>";
            // line 122
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_UseSMTPServerForEmail")), "html", null, true);
            echo "</label>
            <div class=\"form-help\">
                ";
            // line 124
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_SelectYesIfYouWantToSendEmailsViaServer")), "html", null, true);
            echo "
            </div>
            <label class=\"radio\">
                <input type=\"radio\" name=\"mailUseSmtp\" value=\"1\" ";
            // line 127
            if (($this->getAttribute((isset($context["mail"]) ? $context["mail"] : $this->getContext($context, "mail")), "transport", array()) == "smtp")) {
                echo "checked";
            }
            echo " />
                ";
            // line 128
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
            echo "
            </label>
            <label class=\"radio\">
                <input type=\"radio\" name=\"mailUseSmtp\" value=\"0\" ";
            // line 131
            if (($this->getAttribute((isset($context["mail"]) ? $context["mail"] : $this->getContext($context, "mail")), "transport", array()) == "")) {
                echo "checked";
            }
            echo " />
                ";
            // line 132
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
            echo "
            </label>
        </div>

        <div id=\"smtpSettings\">
            <div class=\"form-group\">
                <label for=\"mailHost\">";
            // line 138
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_SmtpServerAddress")), "html", null, true);
            echo "</label>
                <input type=\"text\" id=\"mailHost\" value=\"";
            // line 139
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["mail"]) ? $context["mail"] : $this->getContext($context, "mail")), "host", array()), "html", null, true);
            echo "\">
            </div>
            <div class=\"form-group\">
                <label for=\"mailPort\">";
            // line 142
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_SmtpPort")), "html", null, true);
            echo "</label>
                <span class=\"form-help\">";
            // line 143
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_OptionalSmtpPort")), "html", null, true);
            echo "</span>
                <input type=\"text\" id=\"mailPort\" value=\"";
            // line 144
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["mail"]) ? $context["mail"] : $this->getContext($context, "mail")), "port", array()), "html", null, true);
            echo "\">
            </div>
            <div class=\"form-group\">
                <label for=\"mailType\">";
            // line 147
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_AuthenticationMethodSmtp")), "html", null, true);
            echo "</label>
                <span class=\"form-help\">";
            // line 148
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_OnlyUsedIfUserPwdIsSet")), "html", null, true);
            echo "</span>
                <select id=\"mailType\">
                    <option value=\"\" ";
            // line 150
            if (($this->getAttribute((isset($context["mail"]) ? $context["mail"] : $this->getContext($context, "mail")), "type", array()) == "")) {
                echo " selected=\"selected\" ";
            }
            echo "></option>
                    <option id=\"plain\" ";
            // line 151
            if (($this->getAttribute((isset($context["mail"]) ? $context["mail"] : $this->getContext($context, "mail")), "type", array()) == "Plain")) {
                echo " selected=\"selected\" ";
            }
            echo " value=\"Plain\">Plain</option>
                    <option id=\"login\" ";
            // line 152
            if (($this->getAttribute((isset($context["mail"]) ? $context["mail"] : $this->getContext($context, "mail")), "type", array()) == "Login")) {
                echo " selected=\"selected\" ";
            }
            echo " value=\"Login\"> Login</option>
                    <option id=\"cram-md5\" ";
            // line 153
            if (($this->getAttribute((isset($context["mail"]) ? $context["mail"] : $this->getContext($context, "mail")), "type", array()) == "Crammd5")) {
                echo " selected=\"selected\" ";
            }
            echo " value=\"Crammd5\"> Crammd5</option>
                </select>
            </div>
            <div class=\"form-group\">
                <label for=\"mailUsername\">";
            // line 157
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_SmtpUsername")), "html", null, true);
            echo "</label>
                <span class=\"form-help\">";
            // line 158
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_OnlyEnterIfRequired")), "html", null, true);
            echo "</span>
                <input type=\"text\" id=\"mailUsername\" value=\"";
            // line 159
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["mail"]) ? $context["mail"] : $this->getContext($context, "mail")), "username", array()), "html", null, true);
            echo "\"/>
            </div>
            <div class=\"form-group\">
                <label for=\"mailPassword\">";
            // line 162
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_SmtpPassword")), "html", null, true);
            echo "</label>
                <span class=\"form-help\">
                    ";
            // line 164
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_OnlyEnterIfRequiredPassword")), "html", null, true);
            echo "<br/>
                    ";
            // line 165
            echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_WarningPasswordStored", "<strong>", "</strong>"));
            echo "
                </span>
                <input type=\"password\" id=\"mailPassword\" value=\"";
            // line 167
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["mail"]) ? $context["mail"] : $this->getContext($context, "mail")), "password", array()), "html", null, true);
            echo "\"/>
            </div>
            <div class=\"form-group\">
                <label for=\"mailEncryption\">";
            // line 170
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_SmtpEncryption")), "html", null, true);
            echo "</label>
                <span class=\"form-help\">";
            // line 171
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_EncryptedSmtpTransport")), "html", null, true);
            echo "</span>
                <select id=\"mailEncryption\">
                    <option value=\"\" ";
            // line 173
            if (($this->getAttribute((isset($context["mail"]) ? $context["mail"] : $this->getContext($context, "mail")), "encryption", array()) == "")) {
                echo " selected=\"selected\" ";
            }
            echo "></option>
                    <option id=\"ssl\" ";
            // line 174
            if (($this->getAttribute((isset($context["mail"]) ? $context["mail"] : $this->getContext($context, "mail")), "encryption", array()) == "ssl")) {
                echo " selected=\"selected\" ";
            }
            echo " value=\"ssl\">SSL</option>
                    <option id=\"tls\" ";
            // line 175
            if (($this->getAttribute((isset($context["mail"]) ? $context["mail"] : $this->getContext($context, "mail")), "encryption", array()) == "tls")) {
                echo " selected=\"selected\" ";
            }
            echo " value=\"tls\">TLS</option>
                </select>
            </div>
        </div>
    ";
        }
        // line 180
        echo "
    <h2>";
        // line 181
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_BrandingSettings")), "html", null, true);
        echo "</h2>

    <p>";
        // line 183
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_CustomLogoHelpText")), "html", null, true);
        echo "</p>

    <div class=\"form-group\">
        <label>";
        // line 186
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_UseCustomLogo")), "html", null, true);
        echo "</label>
        <div class=\"form-help\">
            ";
        // line 188
        ob_start();
        echo "\"";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_GiveUsYourFeedback")), "html", null, true);
        echo "\"";
        $context["giveUsFeedbackText"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 189
        echo "            ";
        echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_CustomLogoFeedbackInfo", (isset($context["giveUsFeedbackText"]) ? $context["giveUsFeedbackText"] : $this->getContext($context, "giveUsFeedbackText")), "<a href='?module=CorePluginsAdmin&action=plugins' rel='noreferrer' target='_blank'>", "</a>"));
        echo "
        </div>
        <label class=\"radio\">
            <input type=\"radio\" name=\"useCustomLogo\" value=\"1\" ";
        // line 192
        if (($this->getAttribute((isset($context["branding"]) ? $context["branding"] : $this->getContext($context, "branding")), "use_custom_logo", array()) == 1)) {
            echo "checked";
        }
        echo " />
            ";
        // line 193
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
        echo "
        </label>
        <label class=\"radio\">
            <input type=\"radio\" name=\"useCustomLogo\" value=\"0\" ";
        // line 196
        if (($this->getAttribute((isset($context["branding"]) ? $context["branding"] : $this->getContext($context, "branding")), "use_custom_logo", array()) == 0)) {
            echo "checked";
        }
        echo " />
            ";
        // line 197
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
        echo "
        </label>
    </div>

    <div id=\"logoSettings\">
        <form id=\"logoUploadForm\" method=\"post\" enctype=\"multipart/form-data\" action=\"index.php?module=CoreAdminHome&format=json&action=uploadCustomLogo\">
            ";
        // line 203
        if ((isset($context["fileUploadEnabled"]) ? $context["fileUploadEnabled"] : $this->getContext($context, "fileUploadEnabled"))) {
            // line 204
            echo "                <input type=\"hidden\" name=\"token_auth\" value=\"";
            echo twig_escape_filter($this->env, (isset($context["token_auth"]) ? $context["token_auth"] : $this->getContext($context, "token_auth")), "html", null, true);
            echo "\"/>

                ";
            // line 206
            if ((isset($context["logosWriteable"]) ? $context["logosWriteable"] : $this->getContext($context, "logosWriteable"))) {
                // line 207
                echo "                    <div class=\"form-group\">
                        <label for=\"customLogo\">";
                // line 208
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_LogoUpload")), "html", null, true);
                echo "</label>
                        <div class=\"form-help\">";
                // line 209
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_LogoUploadHelp", "JPG / PNG / GIF", 110)), "html", null, true);
                echo "</div>
                        <input name=\"customLogo\" type=\"file\" id=\"customLogo\"/>
                        <img src=\"";
                // line 211
                echo twig_escape_filter($this->env, (isset($context["pathUserLogo"]) ? $context["pathUserLogo"] : $this->getContext($context, "pathUserLogo")), "html", null, true);
                echo "?r=";
                echo twig_escape_filter($this->env, twig_random($this->env), "html", null, true);
                echo "\" id=\"currentLogo\" style=\"max-height: 150px\"/>
                    </div>
                    <div class=\"form-group\">
                        <label for=\"customLogo\">";
                // line 214
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_FaviconUpload")), "html", null, true);
                echo "</label>
                        <div class=\"form-help\">";
                // line 215
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_LogoUploadHelp", "JPG / PNG / GIF", 16)), "html", null, true);
                echo "</div>
                        <input name=\"customFavicon\" type=\"file\" id=\"customFavicon\"/>
                        <img src=\"";
                // line 217
                echo twig_escape_filter($this->env, (isset($context["pathUserFavicon"]) ? $context["pathUserFavicon"] : $this->getContext($context, "pathUserFavicon")), "html", null, true);
                echo "?r=";
                echo twig_escape_filter($this->env, twig_random($this->env), "html", null, true);
                echo "\" id=\"currentFavicon\" width=\"16\" height=\"16\"/>
                    </div>
                ";
            } else {
                // line 220
                echo "                    <div class=\"alert alert-warning\">
                        ";
                // line 221
                echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_LogoNotWriteableInstruction", (("<code>" .                 // line 222
(isset($context["pathUserLogoDirectory"]) ? $context["pathUserLogoDirectory"] : $this->getContext($context, "pathUserLogoDirectory"))) . "</code><br/>"), ((((((isset($context["pathUserLogo"]) ? $context["pathUserLogo"] : $this->getContext($context, "pathUserLogo")) . ", ") . (isset($context["pathUserLogoSmall"]) ? $context["pathUserLogoSmall"] : $this->getContext($context, "pathUserLogoSmall"))) . ", ") . (isset($context["pathUserLogoSVG"]) ? $context["pathUserLogoSVG"] : $this->getContext($context, "pathUserLogoSVG"))) . "")));
                echo "
                    </div>
                ";
            }
            // line 225
            echo "            ";
        } else {
            // line 226
            echo "                <div class=\"alert alert-warning\">
                    ";
            // line 227
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_FileUploadDisabled", "file_uploads=1")), "html", null, true);
            echo "
                </div>
            ";
        }
        // line 230
        echo "        </form>
    </div>

    <div class=\"ui-confirm\" id=\"confirmTrustedHostChange\">
        <h2>";
        // line 234
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_TrustedHostConfirm")), "html", null, true);
        echo "</h2>
        <input role=\"yes\" type=\"button\" value=\"";
        // line 235
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
        echo "\"/>
        <input role=\"no\" type=\"button\" value=\"";
        // line 236
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
        echo "\"/>
    </div>
    <h2 id=\"trustedHostsSection\">";
        // line 238
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_TrustedHostSettings")), "html", null, true);
        echo "</h2>
    <div id='trustedHostSettings'>

        ";
        // line 241
        $this->loadTemplate("@CoreHome/_warningInvalidHost.twig", "@CoreAdminHome/generalSettings.twig", 241)->display($context);
        // line 242
        echo "
        ";
        // line 243
        if ( !(isset($context["isGeneralSettingsAdminEnabled"]) ? $context["isGeneralSettingsAdminEnabled"] : $this->getContext($context, "isGeneralSettingsAdminEnabled"))) {
            // line 244
            echo "            ";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_PiwikIsInstalledAt")), "html", null, true);
            echo ": ";
            echo twig_escape_filter($this->env, twig_join_filter((isset($context["trustedHosts"]) ? $context["trustedHosts"] : $this->getContext($context, "trustedHosts")), ", "), "html", null, true);
            echo "
        ";
        } else {
            // line 246
            echo "            <div class=\"form-group\">
                <label>";
            // line 247
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_ValidPiwikHostname")), "html", null, true);
            echo "</label>
            </div>
            <ul>
                ";
            // line 250
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["trustedHosts"]) ? $context["trustedHosts"] : $this->getContext($context, "trustedHosts")));
            foreach ($context['_seq'] as $context["hostIdx"] => $context["host"]) {
                // line 251
                echo "                    <li>
                        <input name=\"trusted_host\" type=\"text\" value=\"";
                // line 252
                echo twig_escape_filter($this->env, $context["host"], "html", null, true);
                echo "\"/>
                        <a href=\"#\" class=\"remove-trusted-host btn btn-flat btn-lg\" title=\"";
                // line 253
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Delete")), "html", null, true);
                echo "\">
                            <span class=\"icon-minus\"></span>
                        </a>
                    </li>
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['hostIdx'], $context['host'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 258
            echo "            </ul>

            <div class=\"add-trusted-host\">
                <input type=\"text\" placeholder=\"";
            // line 261
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_AddNewTrustedHost")), "html_attr");
            echo "\" readonly/>

                <a href=\"#\" class=\"btn btn-flat btn-lg\" title=\"";
            // line 263
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Add")), "html", null, true);
            echo "\">
                    <span class=\"icon-add\"></span>
                </a>

            </div>
        ";
        }
        // line 269
        echo "    </div>

    <input type=\"submit\" value=\"";
        // line 271
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Save")), "html", null, true);
        echo "\" class=\"submit generalSettingsSubmit\"/>
    <br/><br/>

    ";
        // line 274
        if ((isset($context["isDataPurgeSettingsEnabled"]) ? $context["isDataPurgeSettingsEnabled"] : $this->getContext($context, "isDataPurgeSettingsEnabled"))) {
            // line 275
            echo "        <h2>";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteDataSettings")), "html", null, true);
            echo "</h2>
        <p>";
            // line 276
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteDataDescription")), "html", null, true);
            echo " ";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteDataDescription2")), "html", null, true);
            echo "</p>
        <p>
            <a href='";
            // line 278
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('linkTo')->getCallable(), array(array("module" => "PrivacyManager", "action" => "privacySettings"))), "html", null, true);
            echo "#deleteLogsAnchor'>
                ";
            // line 279
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_ClickHereSettings", (("'" . call_user_func_array($this->env->getFilter('translate')->getCallable(), array("PrivacyManager_DeleteDataSettings"))) . "'"))), "html", null, true);
            echo "
            </a>
        </p>
    ";
        }
        // line 283
        echo "
";
    }

    public function getTemplateName()
    {
        return "@CoreAdminHome/generalSettings.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  756 => 283,  749 => 279,  745 => 278,  738 => 276,  733 => 275,  731 => 274,  725 => 271,  721 => 269,  712 => 263,  707 => 261,  702 => 258,  691 => 253,  687 => 252,  684 => 251,  680 => 250,  674 => 247,  671 => 246,  663 => 244,  661 => 243,  658 => 242,  656 => 241,  650 => 238,  645 => 236,  641 => 235,  637 => 234,  631 => 230,  625 => 227,  622 => 226,  619 => 225,  613 => 222,  612 => 221,  609 => 220,  601 => 217,  596 => 215,  592 => 214,  584 => 211,  579 => 209,  575 => 208,  572 => 207,  570 => 206,  564 => 204,  562 => 203,  553 => 197,  547 => 196,  541 => 193,  535 => 192,  528 => 189,  522 => 188,  517 => 186,  511 => 183,  506 => 181,  503 => 180,  493 => 175,  487 => 174,  481 => 173,  476 => 171,  472 => 170,  466 => 167,  461 => 165,  457 => 164,  452 => 162,  446 => 159,  442 => 158,  438 => 157,  429 => 153,  423 => 152,  417 => 151,  411 => 150,  406 => 148,  402 => 147,  396 => 144,  392 => 143,  388 => 142,  382 => 139,  378 => 138,  369 => 132,  363 => 131,  357 => 128,  351 => 127,  345 => 124,  340 => 122,  333 => 119,  331 => 118,  328 => 117,  325 => 116,  318 => 112,  314 => 111,  308 => 110,  301 => 106,  295 => 105,  288 => 101,  283 => 99,  280 => 98,  278 => 97,  274 => 95,  267 => 93,  261 => 91,  259 => 90,  255 => 89,  247 => 88,  244 => 87,  240 => 86,  235 => 84,  230 => 82,  225 => 80,  220 => 78,  213 => 75,  211 => 74,  204 => 70,  198 => 67,  190 => 66,  187 => 65,  181 => 62,  175 => 60,  169 => 57,  165 => 56,  161 => 55,  158 => 54,  156 => 53,  153 => 52,  151 => 51,  146 => 49,  141 => 46,  136 => 43,  130 => 41,  124 => 39,  122 => 38,  116 => 35,  113 => 34,  106 => 30,  102 => 29,  96 => 28,  90 => 25,  86 => 24,  80 => 23,  74 => 20,  69 => 18,  64 => 16,  61 => 15,  59 => 14,  54 => 12,  49 => 10,  45 => 9,  42 => 8,  39 => 7,  36 => 6,  33 => 5,  29 => 1,  25 => 3,  11 => 1,);
    }
}
