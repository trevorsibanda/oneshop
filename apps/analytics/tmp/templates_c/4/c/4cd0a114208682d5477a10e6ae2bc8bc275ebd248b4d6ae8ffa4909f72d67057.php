<?php

/* @Login/login.twig */
class __TwigTemplate_4cd0a114208682d5477a10e6ae2bc8bc275ebd248b4d6ae8ffa4909f72d67057 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("@Morpheus/layout.twig", "@Login/login.twig", 1);
        $this->blocks = array(
            'head' => array($this, 'block_head'),
            'meta' => array($this, 'block_meta'),
            'pageDescription' => array($this, 'block_pageDescription'),
            'body' => array($this, 'block_body'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "@Morpheus/layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 26
        ob_start();
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LogIn")), "html", null, true);
        $context["title"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 30
        $context["bodyId"] = "loginPage";
        // line 1
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_head($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        $this->displayParentBlock("head", $context, $blocks);
        echo "

    ";
        // line 6
        $this->displayBlock('meta', $context, $blocks);
        // line 9
        echo "
    <script type=\"text/javascript\" src=\"libs/bower_components/jquery-placeholder/jquery.placeholder.js\"></script>
    <!--[if lt IE 9]>
    <script src=\"libs/bower_components/html5shiv/dist/html5shiv.min.js\"></script>
    <![endif]-->
    <script type=\"text/javascript\" src=\"libs/jquery/jquery.smartbanner.js\"></script>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"libs/jquery/stylesheets/jquery.smartbanner.css\" />

    <script type=\"text/javascript\">
        \$(function () {
            \$('#form_login').focus();
            \$('input').placeholder();
            \$.smartbanner({title: \"Piwik Mobile 2\", author: \"Piwik team\", hideOnInstall: false, layer: true, icon: \"plugins/CoreHome/images/googleplay.png\"});
        });
    </script>
";
    }

    // line 6
    public function block_meta($context, array $blocks = array())
    {
        // line 7
        echo "        <meta name=\"robots\" content=\"index,follow\">
    ";
    }

    // line 28
    public function block_pageDescription($context, array $blocks = array())
    {
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_OpenSourceWebAnalytics")), "html", null, true);
    }

    // line 32
    public function block_body($context, array $blocks = array())
    {
        // line 33
        echo "
    ";
        // line 34
        echo call_user_func_array($this->env->getFunction('postEvent')->getCallable(), array("Template.beforeTopBar", "login"));
        echo "
    ";
        // line 35
        echo call_user_func_array($this->env->getFunction('postEvent')->getCallable(), array("Template.beforeContent", "login"));
        echo "

    ";
        // line 37
        $this->loadTemplate("_iframeBuster.twig", "@Login/login.twig", 37)->display($context);
        // line 38
        echo "
    <div id=\"notificationContainer\">
    </div>

    <div id=\"logo\">
        ";
        // line 43
        if (((isset($context["isCustomLogo"]) ? $context["isCustomLogo"] : $this->getContext($context, "isCustomLogo")) == false)) {
            // line 44
            echo "            <a href=\"http://piwik.org\" title=\"";
            echo twig_escape_filter($this->env, (isset($context["linkTitle"]) ? $context["linkTitle"] : $this->getContext($context, "linkTitle")), "html", null, true);
            echo "\">
        ";
        }
        // line 46
        echo "        ";
        if ((isset($context["hasSVGLogo"]) ? $context["hasSVGLogo"] : $this->getContext($context, "hasSVGLogo"))) {
            // line 47
            echo "            <img src='";
            echo twig_escape_filter($this->env, (isset($context["logoSVG"]) ? $context["logoSVG"] : $this->getContext($context, "logoSVG")), "html", null, true);
            echo "' title=\"";
            echo twig_escape_filter($this->env, (isset($context["linkTitle"]) ? $context["linkTitle"] : $this->getContext($context, "linkTitle")), "html", null, true);
            echo "\" alt=\"Piwik\" class=\"ie-hide\"/>
            <!--[if lt IE 9]>
        ";
        }
        // line 50
        echo "        <img src='";
        echo twig_escape_filter($this->env, (isset($context["logoLarge"]) ? $context["logoLarge"] : $this->getContext($context, "logoLarge")), "html", null, true);
        echo "' title=\"";
        echo twig_escape_filter($this->env, (isset($context["linkTitle"]) ? $context["linkTitle"] : $this->getContext($context, "linkTitle")), "html", null, true);
        echo "\" alt=\"Piwik\" />
        ";
        // line 51
        if ((isset($context["hasSVGLogo"]) ? $context["hasSVGLogo"] : $this->getContext($context, "hasSVGLogo"))) {
            echo "<![endif]-->";
        }
        // line 52
        echo "
        ";
        // line 53
        if ((isset($context["isCustomLogo"]) ? $context["isCustomLogo"] : $this->getContext($context, "isCustomLogo"))) {
            // line 54
            echo "            ";
            ob_start();
            // line 55
            echo "            <i><a href=\"http://piwik.org/\" rel=\"noreferrer\"  target=\"_blank\">";
            echo twig_escape_filter($this->env, (isset($context["linkTitle"]) ? $context["linkTitle"] : $this->getContext($context, "linkTitle")), "html", null, true);
            echo "</a></i>
            ";
            $context["poweredByPiwik"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
            // line 57
            echo "        ";
        }
        // line 58
        echo "
        ";
        // line 59
        if (((isset($context["isCustomLogo"]) ? $context["isCustomLogo"] : $this->getContext($context, "isCustomLogo")) == false)) {
            // line 60
            echo "            </a>
            <div class=\"description\">
                <a href=\"http://piwik.org\" title=\"";
            // line 62
            echo twig_escape_filter($this->env, (isset($context["linkTitle"]) ? $context["linkTitle"] : $this->getContext($context, "linkTitle")), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, (isset($context["linkTitle"]) ? $context["linkTitle"] : $this->getContext($context, "linkTitle")), "html", null, true);
            echo "</a>
                <div class=\"arrow\"></div>
            </div>
        ";
        }
        // line 66
        echo "    </div>

    <section class=\"loginSection\">

        ";
        // line 71
        echo "        ";
        if (((array_key_exists("isValidHost", $context) && array_key_exists("invalidHostMessage", $context)) && ((isset($context["isValidHost"]) ? $context["isValidHost"] : $this->getContext($context, "isValidHost")) == false))) {
            // line 72
            echo "            ";
            $this->loadTemplate("@CoreHome/_warningInvalidHost.twig", "@Login/login.twig", 72)->display($context);
            // line 73
            echo "        ";
        } else {
            // line 74
            echo "            <div id=\"message_container\">

                ";
            // line 76
            echo twig_include($this->env, $context, "@Login/_formErrors.twig", array("formErrors" => $this->getAttribute((isset($context["form_data"]) ? $context["form_data"] : $this->getContext($context, "form_data")), "errors", array())));
            echo "

                ";
            // line 78
            if ((isset($context["AccessErrorString"]) ? $context["AccessErrorString"] : $this->getContext($context, "AccessErrorString"))) {
                // line 79
                echo "                    <div class=\"message_error\">
                        <strong>";
                // line 80
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Error")), "html", null, true);
                echo "</strong>: ";
                echo (isset($context["AccessErrorString"]) ? $context["AccessErrorString"] : $this->getContext($context, "AccessErrorString"));
                echo "<br/>
                    </div>
                ";
            }
            // line 83
            echo "
                ";
            // line 84
            if ((isset($context["infoMessage"]) ? $context["infoMessage"] : $this->getContext($context, "infoMessage"))) {
                // line 85
                echo "                    <p class=\"message\">";
                echo (isset($context["infoMessage"]) ? $context["infoMessage"] : $this->getContext($context, "infoMessage"));
                echo "</p>
                ";
            }
            // line 87
            echo "            </div>
            <form ";
            // line 88
            echo $this->getAttribute((isset($context["form_data"]) ? $context["form_data"] : $this->getContext($context, "form_data")), "attributes", array());
            echo " ng-non-bindable>
                <h1>";
            // line 89
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LogIn")), "html", null, true);
            echo "</h1>
                <fieldset class=\"inputs\">
                    <input type=\"text\" name=\"form_login\" id=\"login_form_login\" class=\"input\" value=\"\" size=\"20\"
                           tabindex=\"10\"
                           placeholder=\"";
            // line 93
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Username")), "html", null, true);
            echo "\" autofocus=\"autofocus\"/>
                    <input type=\"password\" name=\"form_password\" id=\"login_form_password\" class=\"input\" value=\"\" size=\"20\"
                           tabindex=\"20\"
                           placeholder=\"";
            // line 96
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Password")), "html", null, true);
            echo "\"/>
                    <input type=\"hidden\" name=\"form_nonce\" id=\"login_form_nonce\" value=\"";
            // line 97
            echo twig_escape_filter($this->env, (isset($context["nonce"]) ? $context["nonce"] : $this->getContext($context, "nonce")), "html", null, true);
            echo "\"/>
                </fieldset>

                <fieldset class=\"actions\">
                    <input name=\"form_rememberme\" type=\"checkbox\" id=\"login_form_rememberme\" value=\"1\" tabindex=\"90\"
                           ";
            // line 102
            if ($this->getAttribute($this->getAttribute((isset($context["form_data"]) ? $context["form_data"] : $this->getContext($context, "form_data")), "form_rememberme", array()), "value", array())) {
                echo "checked=\"checked\" ";
            }
            echo "/>
                    <label for=\"login_form_rememberme\">";
            // line 103
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_RememberMe")), "html", null, true);
            echo "</label>
                    <input class=\"submit\" id='login_form_submit' type=\"submit\" value=\"";
            // line 104
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LogIn")), "html", null, true);
            echo "\"
                           tabindex=\"100\"/>
                </fieldset>
            </form>
            <form id=\"reset_form\" style=\"display:none;\" ng-non-bindable>
                <fieldset class=\"inputs\">
                    <input type=\"text\" name=\"form_login\" id=\"reset_form_login\" class=\"input\" value=\"\" size=\"20\"
                           tabindex=\"10\"
                           placeholder=\"";
            // line 112
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LoginOrEmail")), "html", null, true);
            echo "\"/>
                    <input type=\"hidden\" name=\"form_nonce\" id=\"reset_form_nonce\" value=\"";
            // line 113
            echo twig_escape_filter($this->env, (isset($context["nonce"]) ? $context["nonce"] : $this->getContext($context, "nonce")), "html", null, true);
            echo "\"/>

                    <input type=\"password\" name=\"form_password\" id=\"reset_form_password\" class=\"input\" value=\"\" size=\"20\"
                           tabindex=\"20\" autocomplete=\"off\"
                           placeholder=\"";
            // line 117
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Password")), "html", null, true);
            echo "\"/>

                    <input type=\"password\" name=\"form_password_bis\" id=\"reset_form_password_bis\" class=\"input\" value=\"\"
                           size=\"20\" tabindex=\"30\" autocomplete=\"off\"
                           placeholder=\"";
            // line 121
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_PasswordRepeat")), "html", null, true);
            echo "\"/>
                </fieldset>

                <fieldset class=\"actions\">
                    <span class=\"loadingPiwik\" style=\"display:none;\">
                        <img alt=\"Loading\" src=\"plugins/Morpheus/images/loading-blue.gif\"/>
                    </span>
                    <input class=\"submit\" id='reset_form_submit' type=\"submit\"
                           value=\"";
            // line 129
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_ChangePassword")), "html", null, true);
            echo "\" tabindex=\"100\"/>
                </fieldset>

                <input type=\"hidden\" name=\"module\" value=\"";
            // line 132
            echo twig_escape_filter($this->env, (isset($context["loginModule"]) ? $context["loginModule"] : $this->getContext($context, "loginModule")), "html", null, true);
            echo "\"/>
                <input type=\"hidden\" name=\"action\" value=\"resetPassword\"/>
            </form>
            <p id=\"nav\">
                ";
            // line 136
            echo call_user_func_array($this->env->getFunction('postEvent')->getCallable(), array("Template.loginNav", "top"));
            echo "
                <a id=\"login_form_nav\" href=\"#\"
                   title=\"";
            // line 138
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LostYourPassword")), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LostYourPassword")), "html", null, true);
            echo "</a>
                <a id=\"alternate_reset_nav\" href=\"#\" style=\"display:none;\"
                   title=\"";
            // line 140
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LogIn")), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LogIn")), "html", null, true);
            echo "</a>
                <a id=\"reset_form_nav\" href=\"#\" style=\"display:none;\"
                   title=\"";
            // line 142
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Mobile_NavigationBack")), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Cancel")), "html", null, true);
            echo "</a>
                ";
            // line 143
            echo call_user_func_array($this->env->getFunction('postEvent')->getCallable(), array("Template.loginNav", "bottom"));
            echo "
            </p>
            ";
            // line 145
            if (array_key_exists("poweredByPiwik", $context)) {
                // line 146
                echo "                <p id=\"piwik\">
                    ";
                // line 147
                echo twig_escape_filter($this->env, (isset($context["poweredByPiwik"]) ? $context["poweredByPiwik"] : $this->getContext($context, "poweredByPiwik")), "html", null, true);
                echo "
                </p>
            ";
            }
            // line 150
            echo "            <div id=\"lost_password_instructions\" style=\"display:none;\">
                <p class=\"message\">";
            // line 151
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_ResetPasswordInstructions")), "html", null, true);
            echo "</p>
            </div>
        ";
        }
        // line 154
        echo "    </section>

";
    }

    public function getTemplateName()
    {
        return "@Login/login.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  365 => 154,  359 => 151,  356 => 150,  350 => 147,  347 => 146,  345 => 145,  340 => 143,  334 => 142,  327 => 140,  320 => 138,  315 => 136,  308 => 132,  302 => 129,  291 => 121,  284 => 117,  277 => 113,  273 => 112,  262 => 104,  258 => 103,  252 => 102,  244 => 97,  240 => 96,  234 => 93,  227 => 89,  223 => 88,  220 => 87,  214 => 85,  212 => 84,  209 => 83,  201 => 80,  198 => 79,  196 => 78,  191 => 76,  187 => 74,  184 => 73,  181 => 72,  178 => 71,  172 => 66,  163 => 62,  159 => 60,  157 => 59,  154 => 58,  151 => 57,  145 => 55,  142 => 54,  140 => 53,  137 => 52,  133 => 51,  126 => 50,  117 => 47,  114 => 46,  108 => 44,  106 => 43,  99 => 38,  97 => 37,  92 => 35,  88 => 34,  85 => 33,  82 => 32,  76 => 28,  71 => 7,  68 => 6,  49 => 9,  47 => 6,  41 => 4,  38 => 3,  34 => 1,  32 => 30,  28 => 26,  11 => 1,);
    }
}
