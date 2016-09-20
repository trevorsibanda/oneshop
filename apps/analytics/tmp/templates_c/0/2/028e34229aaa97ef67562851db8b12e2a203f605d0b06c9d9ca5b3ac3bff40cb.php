<?php

/* @UsersManager/index.twig */
class __TwigTemplate_028e34229aaa97ef67562851db8b12e2a203f605d0b06c9d9ca5b3ac3bff40cb extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("admin.twig", "@UsersManager/index.twig", 1);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
            'websiteAccessTable' => array($this, 'block_websiteAccessTable'),
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
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_ManageAccess")), "html", null, true);
        $context["title"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 1
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "
<h2 piwik-enriched-headline
    help-url=\"http://piwik.org/docs/manage-users/\">";
        // line 8
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : $this->getContext($context, "title")), "html", null, true);
        echo "</h2>
<div id=\"sites\" class=\"usersManager\">
    <section class=\"sites_selector_container\">
        <p>";
        // line 11
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_MainDescription")), "html", null, true);
        echo "</p>

        ";
        // line 13
        ob_start();
        // line 14
        echo "            <strong>";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_ApplyToAllWebsites")), "html", null, true);
        echo "</strong>
        ";
        $context["applyAllSitesText"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 16
        echo "
        <div piwik-siteselector
             class=\"sites_autocomplete\"
             siteid=\"";
        // line 19
        echo twig_escape_filter($this->env, (isset($context["idSiteSelected"]) ? $context["idSiteSelected"] : $this->getContext($context, "idSiteSelected")), "html", null, true);
        echo "\"
             sitename=\"";
        // line 20
        echo twig_escape_filter($this->env, (isset($context["defaultReportSiteName"]) ? $context["defaultReportSiteName"] : $this->getContext($context, "defaultReportSiteName")), "html", null, true);
        echo "\"
             all-sites-text=\"";
        // line 21
        echo (isset($context["applyAllSitesText"]) ? $context["applyAllSitesText"] : $this->getContext($context, "applyAllSitesText"));
        echo "\"
             all-sites-location=\"top\"
             id=\"usersManagerSiteSelect\"
             switch-site-on-select=\"false\"></div>
    </section>
</div>

";
        // line 28
        $this->displayBlock('websiteAccessTable', $context, $blocks);
        // line 217
        echo "
";
    }

    // line 28
    public function block_websiteAccessTable($context, array $blocks = array())
    {
        // line 29
        echo "
";
        // line 30
        $context["ajax"] = $this->loadTemplate("ajaxMacros.twig", "@UsersManager/index.twig", 30);
        // line 31
        echo $context["ajax"]->geterrorDiv();
        echo "
";
        // line 32
        echo $context["ajax"]->getloadingDiv();
        echo "

<div class=\"entityContainer\" style=\"width:600px;margin-top:16px;\">
    ";
        // line 35
        if ((isset($context["anonymousHasViewAccess"]) ? $context["anonymousHasViewAccess"] : $this->getContext($context, "anonymousHasViewAccess"))) {
            // line 36
            echo "        <br/>
        <div class=\"alert alert-warning\">
            ";
            // line 38
            echo twig_escape_filter($this->env, twig_join_filter(array(0 => call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_AnonymousUserHasViewAccess", "'anonymous'", "'view'")), 1 => call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_AnonymousUserHasViewAccess2"))), " "), "html", null, true);
            echo "
        </div>
    ";
        }
        // line 41
        echo "    <table class=\"entityTable dataTable\" id=\"access\" style=\"display:inline-table;width:550px;\">
        <thead>
        <tr>
            <th class='first'>";
        // line 44
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_User")), "html", null, true);
        echo "</th>
            <th>";
        // line 45
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_Alias")), "html", null, true);
        echo "</th>
            <th>";
        // line 46
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_PrivNone")), "html", null, true);
        echo "</th>
            <th>";
        // line 47
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_PrivView")), "html", null, true);
        echo "</th>
            <th>";
        // line 48
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_PrivAdmin")), "html", null, true);
        echo "</th>
        </tr>
        </thead>

        <tbody>
        ";
        // line 53
        $context["accesValid"] = ('' === $tmp = "<img src='plugins/UsersManager/images/ok.png' class='accessGranted' />") ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 54
        echo "        ";
        $context["accesInvalid"] = ('' === $tmp = "<img src='plugins/UsersManager/images/no-access.png' class='updateAccess' />") ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 55
        echo "        ";
        ob_start();
        echo "<span title=\"";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_ExceptionSuperUserAccess")), "html", null, true);
        echo "\">N/A</span>";
        $context["superUserAccess"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 56
        echo "        ";
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["usersAccessByWebsite"]) ? $context["usersAccessByWebsite"] : $this->getContext($context, "usersAccessByWebsite")));
        foreach ($context['_seq'] as $context["login"] => $context["access"]) {
            // line 57
            echo "            <tr>
                <td id='login'>";
            // line 58
            echo twig_escape_filter($this->env, $context["login"], "html", null, true);
            echo "</td>
                <td>";
            // line 59
            echo $this->getAttribute((isset($context["usersAliasByLogin"]) ? $context["usersAliasByLogin"] : $this->getContext($context, "usersAliasByLogin")), $context["login"], array(), "array");
            echo "</td>
                <td id='noaccess'>
                    ";
            // line 61
            if (twig_in_filter($context["login"], (isset($context["superUserLogins"]) ? $context["superUserLogins"] : $this->getContext($context, "superUserLogins")))) {
                // line 62
                echo "                        ";
                echo twig_escape_filter($this->env, (isset($context["superUserAccess"]) ? $context["superUserAccess"] : $this->getContext($context, "superUserAccess")), "html", null, true);
                echo "
                    ";
            } elseif (((            // line 63
$context["access"] == "noaccess") && ((isset($context["idSiteSelected"]) ? $context["idSiteSelected"] : $this->getContext($context, "idSiteSelected")) != "all"))) {
                // line 64
                echo "                        ";
                echo twig_escape_filter($this->env, (isset($context["accesValid"]) ? $context["accesValid"] : $this->getContext($context, "accesValid")), "html", null, true);
                echo "
                    ";
            } else {
                // line 66
                echo "                        ";
                echo twig_escape_filter($this->env, (isset($context["accesInvalid"]) ? $context["accesInvalid"] : $this->getContext($context, "accesInvalid")), "html", null, true);
                echo "
                    ";
            }
            // line 67
            echo "&nbsp;</td>
                <td id='view'>
                    ";
            // line 69
            if (twig_in_filter($context["login"], (isset($context["superUserLogins"]) ? $context["superUserLogins"] : $this->getContext($context, "superUserLogins")))) {
                // line 70
                echo "                        ";
                echo twig_escape_filter($this->env, (isset($context["superUserAccess"]) ? $context["superUserAccess"] : $this->getContext($context, "superUserAccess")), "html", null, true);
                echo "
                    ";
            } elseif (((            // line 71
$context["access"] == "view") && ((isset($context["idSiteSelected"]) ? $context["idSiteSelected"] : $this->getContext($context, "idSiteSelected")) != "all"))) {
                // line 72
                echo "                        ";
                echo twig_escape_filter($this->env, (isset($context["accesValid"]) ? $context["accesValid"] : $this->getContext($context, "accesValid")), "html", null, true);
                echo "
                    ";
            } else {
                // line 74
                echo "                        ";
                echo twig_escape_filter($this->env, (isset($context["accesInvalid"]) ? $context["accesInvalid"] : $this->getContext($context, "accesInvalid")), "html", null, true);
                echo "
                    ";
            }
            // line 75
            echo "&nbsp;</td>
                <td id='admin'>
                    ";
            // line 77
            if (twig_in_filter($context["login"], (isset($context["superUserLogins"]) ? $context["superUserLogins"] : $this->getContext($context, "superUserLogins")))) {
                // line 78
                echo "                        ";
                echo twig_escape_filter($this->env, (isset($context["superUserAccess"]) ? $context["superUserAccess"] : $this->getContext($context, "superUserAccess")), "html", null, true);
                echo "
                    ";
            } elseif ((            // line 79
$context["login"] == "anonymous")) {
                // line 80
                echo "                        N/A
                    ";
            } else {
                // line 82
                echo "                        ";
                if ((($context["access"] == "admin") && ((isset($context["idSiteSelected"]) ? $context["idSiteSelected"] : $this->getContext($context, "idSiteSelected")) != "all"))) {
                    echo twig_escape_filter($this->env, (isset($context["accesValid"]) ? $context["accesValid"] : $this->getContext($context, "accesValid")), "html", null, true);
                } else {
                    echo twig_escape_filter($this->env, (isset($context["accesInvalid"]) ? $context["accesInvalid"] : $this->getContext($context, "accesInvalid")), "html", null, true);
                }
                echo "&nbsp;
                    ";
            }
            // line 84
            echo "                </td>
            </tr>
        ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['login'], $context['access'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 87
        echo "        </tbody>
    </table>
    <div id=\"accessUpdated\" style=\"vertical-align:top;\"></div>
</div>

<div class=\"ui-confirm\" id=\"confirm\">
    <h2>";
        // line 93
        echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_ChangeAllConfirm", "<span id='login'></span>"));
        echo "</h2>
    <input role=\"yes\" type=\"button\" value=\"";
        // line 94
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
        echo "\"/>
    <input role=\"no\" type=\"button\" value=\"";
        // line 95
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
        echo "\"/>
</div>

";
        // line 98
        if ((isset($context["userIsSuperUser"]) ? $context["userIsSuperUser"] : $this->getContext($context, "userIsSuperUser"))) {
            // line 99
            echo "    <div class=\"ui-confirm\" id=\"confirmUserRemove\">
        <h2></h2>
        <input role=\"yes\" type=\"button\" value=\"";
            // line 101
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
            echo "\"/>
        <input role=\"no\" type=\"button\" value=\"";
            // line 102
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
            echo "\"/>
    </div>
    <div class=\"ui-confirm\" id=\"confirmPasswordChange\">
        <h2>";
            // line 105
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_ChangePasswordConfirm")), "html", null, true);
            echo "</h2>
        <input role=\"yes\" type=\"button\" value=\"";
            // line 106
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
            echo "\"/>
        <input role=\"no\" type=\"button\" value=\"";
            // line 107
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
            echo "\"/>
    </div>
    <br/>
    <h2>";
            // line 110
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_UsersManagement")), "html", null, true);
            echo "</h2>
    <p>";
            // line 111
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_UsersManagementMainDescription")), "html", null, true);
            echo "
        ";
            // line 112
            echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_ThereAreCurrentlyNRegisteredUsers", (("<b>" . (isset($context["usersCount"]) ? $context["usersCount"] : $this->getContext($context, "usersCount"))) . "</b>")));
            echo "</p>
    ";
            // line 113
            $context["ajax"] = $this->loadTemplate("ajaxMacros.twig", "@UsersManager/index.twig", 113);
            // line 114
            echo "    ";
            echo $context["ajax"]->geterrorDiv("ajaxErrorUsersManagement");
            echo "
    ";
            // line 115
            echo $context["ajax"]->getloadingDiv("ajaxLoadingUsersManagement");
            echo "
    <div class=\"user entityContainer\" style=\"margin-bottom:50px;\">
        <table class=\"entityTable dataTable\" id=\"users\">
            <thead>
            <tr>
                <th>";
            // line 120
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Username")), "html", null, true);
            echo "</th>
                <th>";
            // line 121
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Password")), "html", null, true);
            echo "</th>
                <th>";
            // line 122
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_Email")), "html", null, true);
            echo "</th>
                <th>";
            // line 123
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_Alias")), "html", null, true);
            echo "</th>
                <th>token_auth</th>
                ";
            // line 125
            if ((array_key_exists("showLastSeen", $context) && (isset($context["showLastSeen"]) ? $context["showLastSeen"] : $this->getContext($context, "showLastSeen")))) {
                // line 126
                echo "                <th>";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_LastSeen")), "html", null, true);
                echo "</th>
                ";
            }
            // line 128
            echo "                <th>";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Edit")), "html", null, true);
            echo "</th>
                <th>";
            // line 129
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Delete")), "html", null, true);
            echo "</th>
            </tr>
            </thead>

            <tbody>
            ";
            // line 134
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["users"]) ? $context["users"] : $this->getContext($context, "users")));
            foreach ($context['_seq'] as $context["i"] => $context["user"]) {
                // line 135
                echo "                ";
                if (($this->getAttribute($context["user"], "login", array()) != "anonymous")) {
                    // line 136
                    echo "                    <tr class=\"editable\" id=\"row";
                    echo twig_escape_filter($this->env, $context["i"], "html", null, true);
                    echo "\">
                        <td id=\"userLogin\" class=\"editable\">";
                    // line 137
                    echo twig_escape_filter($this->env, $this->getAttribute($context["user"], "login", array()), "html", null, true);
                    echo "</td>
                        <td id=\"password\" class=\"editable\">-</td>
                        <td id=\"email\" class=\"editable\">";
                    // line 139
                    echo twig_escape_filter($this->env, $this->getAttribute($context["user"], "email", array()), "html", null, true);
                    echo "</td>
                        <td id=\"alias\" class=\"editable\">";
                    // line 140
                    echo $this->getAttribute($context["user"], "alias", array());
                    echo "</td>
                        <td id=\"token_auth\" class=\"token_auth\" data-token=\"";
                    // line 141
                    echo twig_escape_filter($this->env, $this->getAttribute($context["user"], "token_auth", array()), "html", null, true);
                    echo "\">";
                    echo twig_escape_filter($this->env, twig_slice($this->env, $this->getAttribute($context["user"], "token_auth", array()), 0, 8), "html", null, true);
                    echo "…</td>
                        ";
                    // line 142
                    if ($this->getAttribute($context["user"], "last_seen", array(), "any", true, true)) {
                        // line 143
                        echo "                        <td id=\"last_seen\">";
                        if (twig_test_empty($this->getAttribute($context["user"], "last_seen", array()))) {
                            echo "-";
                        } else {
                            echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_TimeAgo", $this->getAttribute($context["user"], "last_seen", array())));
                        }
                        echo "</td>
                        ";
                    }
                    // line 145
                    echo "                        <td class=\"text-center\">
                            <button class=\"edituser btn btn-flat\" id=\"row";
                    // line 146
                    echo twig_escape_filter($this->env, $context["i"], "html", null, true);
                    echo "\" title=\"";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Edit")), "html", null, true);
                    echo "\">
                                <span class=\"icon-edit\"></span>
                            </button>
                        </td>
                        <td class=\"text-center\">
                            <button class=\"deleteuser btn btn-flat\" id=\"row";
                    // line 151
                    echo twig_escape_filter($this->env, $context["i"], "html", null, true);
                    echo "\" title=\"";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Delete")), "html", null, true);
                    echo "\">
                                <span class=\"icon-delete\"></span>
                            </button>
                        </td>
                    </tr>
                ";
                }
                // line 157
                echo "            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['i'], $context['user'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 158
            echo "            </tbody>
        </table>
        <p>
            <button class=\"add-user btn btn-lg btn-flat\">
                <span class=\"icon-add\"></span>
                ";
            // line 163
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_AddUser")), "html", null, true);
            echo "
            </button>
        </p>
    </div>

    <h2 id=\"super_user_access\">";
            // line 168
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_SuperUserAccessManagement")), "html", null, true);
            echo "</h2>
    <p>";
            // line 169
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_SuperUserAccessManagementMainDescription")), "html", null, true);
            echo " <br/>
    ";
            // line 170
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_SuperUserAccessManagementGrantMore")), "html", null, true);
            echo "</p>

    ";
            // line 172
            echo $context["ajax"]->geterrorDiv("ajaxErrorSuperUsersManagement");
            echo "
    ";
            // line 173
            echo $context["ajax"]->getloadingDiv("ajaxLoadingSuperUsersManagement");
            echo "

    <table class=\"entityTable dataTable\" id=\"superUserAccess\" style=\"display:inline-table;width:400px;\">
        <thead>
        <tr>
            <th class='first'>";
            // line 178
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_User")), "html", null, true);
            echo "</th>
            <th>";
            // line 179
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_Alias")), "html", null, true);
            echo "</th>
            <th>";
            // line 180
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_SuperUser")), "html", null, true);
            echo "</th>
        </tr>
        </thead>

        <tbody>
        ";
            // line 185
            if ((twig_length_filter($this->env, (isset($context["users"]) ? $context["users"] : $this->getContext($context, "users"))) > 1)) {
                // line 186
                echo "            ";
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable((isset($context["usersAliasByLogin"]) ? $context["usersAliasByLogin"] : $this->getContext($context, "usersAliasByLogin")));
                foreach ($context['_seq'] as $context["login"] => $context["alias"]) {
                    if (($context["login"] != "anonymous")) {
                        // line 187
                        echo "                <tr>
                    <td id='login'>";
                        // line 188
                        echo twig_escape_filter($this->env, $context["login"], "html", null, true);
                        echo "</td>
                    <td>";
                        // line 189
                        echo $context["alias"];
                        echo "</td>
                    <td id='superuser' data-login=\"";
                        // line 190
                        echo twig_escape_filter($this->env, $context["login"], "html_attr");
                        echo "\">
                        <img src='plugins/UsersManager/images/ok.png' class='accessGranted' data-hasaccess=\"1\" ";
                        // line 191
                        if ( !twig_in_filter($context["login"], (isset($context["superUserLogins"]) ? $context["superUserLogins"] : $this->getContext($context, "superUserLogins")))) {
                            echo "style=\"display:none\"";
                        }
                        echo " />
                        <img src='plugins/UsersManager/images/no-access.png' class='updateAccess' data-hasaccess=\"0\" ";
                        // line 192
                        if (twig_in_filter($context["login"], (isset($context["superUserLogins"]) ? $context["superUserLogins"] : $this->getContext($context, "superUserLogins")))) {
                            echo "style=\"display:none\"";
                        }
                        echo " />
                        &nbsp;
                    </td>
                </tr>
            ";
                    }
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['login'], $context['alias'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 197
                echo "        ";
            } else {
                // line 198
                echo "            <tr>
                <td colspan=\"3\">
                    ";
                // line 200
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UsersManager_NoUsersExist")), "html", null, true);
                echo "
                </td>
            </tr>
        ";
            }
            // line 204
            echo "        </tbody>
    </table>

    <div id=\"superUserAccessUpdated\" style=\"vertical-align:top;\"></div>

    <div class=\"ui-confirm\" id=\"superUserAccessConfirm\">
        <h2> </h2>
        <input role=\"yes\" type=\"button\" value=\"";
            // line 211
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
            echo "\"/>
        <input role=\"no\" type=\"button\" value=\"";
            // line 212
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
            echo "\"/>
    </div>

";
        }
    }

    public function getTemplateName()
    {
        return "@UsersManager/index.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  575 => 212,  571 => 211,  562 => 204,  555 => 200,  551 => 198,  548 => 197,  534 => 192,  528 => 191,  524 => 190,  520 => 189,  516 => 188,  513 => 187,  507 => 186,  505 => 185,  497 => 180,  493 => 179,  489 => 178,  481 => 173,  477 => 172,  472 => 170,  468 => 169,  464 => 168,  456 => 163,  449 => 158,  443 => 157,  432 => 151,  422 => 146,  419 => 145,  409 => 143,  407 => 142,  401 => 141,  397 => 140,  393 => 139,  388 => 137,  383 => 136,  380 => 135,  376 => 134,  368 => 129,  363 => 128,  357 => 126,  355 => 125,  350 => 123,  346 => 122,  342 => 121,  338 => 120,  330 => 115,  325 => 114,  323 => 113,  319 => 112,  315 => 111,  311 => 110,  305 => 107,  301 => 106,  297 => 105,  291 => 102,  287 => 101,  283 => 99,  281 => 98,  275 => 95,  271 => 94,  267 => 93,  259 => 87,  251 => 84,  241 => 82,  237 => 80,  235 => 79,  230 => 78,  228 => 77,  224 => 75,  218 => 74,  212 => 72,  210 => 71,  205 => 70,  203 => 69,  199 => 67,  193 => 66,  187 => 64,  185 => 63,  180 => 62,  178 => 61,  173 => 59,  169 => 58,  166 => 57,  161 => 56,  154 => 55,  151 => 54,  149 => 53,  141 => 48,  137 => 47,  133 => 46,  129 => 45,  125 => 44,  120 => 41,  114 => 38,  110 => 36,  108 => 35,  102 => 32,  98 => 31,  96 => 30,  93 => 29,  90 => 28,  85 => 217,  83 => 28,  73 => 21,  69 => 20,  65 => 19,  60 => 16,  54 => 14,  52 => 13,  47 => 11,  41 => 8,  37 => 6,  34 => 5,  30 => 1,  26 => 3,  11 => 1,);
    }
}
