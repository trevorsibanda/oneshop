<?php

/* @Installation/layout.twig */
class __TwigTemplate_4feea365aa66fedd3a51a8170ebe4bbe2d86bc4c79e5577c558ec33659c780c6 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html id=\"ng-app\" ng-app=\"piwikApp\">
<head>
    <meta charset=\"utf-8\">
    <meta name=\"robots\" content=\"noindex,nofollow\">
    <title>Piwik &rsaquo; ";
        // line 6
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_Installation")), "html", null, true);
        echo "</title>

    <link rel=\"stylesheet\" type=\"text/css\" href=\"libs/jquery/themes/base/jquery-ui.min.css\"/>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"index.php?module=Installation&action=getBaseCss\"/>
    <link rel=\"stylesheet\" type=\"text/css\" href=\"plugins/Installation/stylesheets/installation.css\"/>

    <link rel=\"shortcut icon\" href=\"plugins/CoreHome/images/favicon.ico\"/>

    <script type=\"text/javascript\" src=\"libs/bower_components/jquery/dist/jquery.min.js\"></script>
    <script type=\"text/javascript\" src=\"libs/bower_components/jquery-ui/ui/minified/jquery-ui.min.js\"></script>
    <script type=\"text/javascript\" src=\"libs/bower_components/angular/angular.min.js\"></script>
    <script type=\"text/javascript\" src=\"libs/bower_components/angular-sanitize/angular-sanitize.js\"></script>
    <script type=\"text/javascript\" src=\"libs/bower_components/angular-animate/angular-animate.js\"></script>
    <script type=\"text/javascript\" src=\"libs/bower_components/angular-cookies/angular-cookies.js\"></script>
    <script type=\"text/javascript\" src=\"libs/bower_components/ngDialog/js/ngDialog.min.js\"></script>
    <script type=\"text/javascript\" src=\"plugins/CoreHome/angularjs/common/services/service.module.js\"></script>
    <script type=\"text/javascript\" src=\"plugins/CoreHome/angularjs/common/filters/filter.module.js\"></script>
    <script type=\"text/javascript\" src=\"plugins/CoreHome/angularjs/common/filters/translate.js\"></script>
    <script type=\"text/javascript\" src=\"plugins/CoreHome/angularjs/common/directives/directive.module.js\"></script>
    <script type=\"text/javascript\" src=\"plugins/CoreHome/angularjs/common/directives/focus-anywhere-but-here.js\"></script>
    <script type=\"text/javascript\" src=\"plugins/CoreHome/angularjs/piwikApp.config.js\"></script>
    <script type=\"text/javascript\" src=\"plugins/CoreHome/angularjs/piwikApp.js\"></script>
    <script type=\"text/javascript\" src=\"plugins/Installation/javascripts/installation.js\"></script>
</head>
<!--[if lt IE 9 ]>
<body ng-app=\"app\" class=\"old-ie\"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<body ng-app=\"app\"><!--<![endif]-->

<div class=\"container\">

    <div class=\"header\">
        <div class=\"logo\">
            <img src=\"plugins/Morpheus/images/logo.png\"/>
            <p>";
        // line 40
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_OpenSourceWebAnalytics")), "html", null, true);
        echo "</p>
        </div>
        <div class=\"language-selector\">
            ";
        // line 43
        echo call_user_func_array($this->env->getFunction('postEvent')->getCallable(), array("Template.topBar"));
        echo "
        </div>

        <div class=\"installation-progress\">
            <h4>
                ";
        // line 48
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_InstallationStatus")), "html", null, true);
        echo "
                <small>";
        // line 49
        echo twig_escape_filter($this->env, (isset($context["percentDone"]) ? $context["percentDone"] : $this->getContext($context, "percentDone")), "html", null, true);
        echo "%</small>
            </h4>
            <div class=\"progress\">
                <div class=\"progress-bar\" role=\"progressbar\" aria-valuenow=\"60\" aria-valuemin=\"0\" aria-valuemax=\"100\" style=\"width: ";
        // line 52
        echo twig_escape_filter($this->env, (isset($context["percentDone"]) ? $context["percentDone"] : $this->getContext($context, "percentDone")), "html", null, true);
        echo "%;\"></div>
            </div>
        </div>

        <div class=\"clearfix\"></div>
    </div>

    <div class=\"row\">
        <div class=\"col-sm-3\">
            <ul class=\"list-group\">
                ";
        // line 62
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["allStepsTitle"]) ? $context["allStepsTitle"] : $this->getContext($context, "allStepsTitle")));
        foreach ($context['_seq'] as $context["stepId"] => $context["stepName"]) {
            // line 63
            echo "                    ";
            if (((isset($context["currentStepId"]) ? $context["currentStepId"] : $this->getContext($context, "currentStepId")) > $context["stepId"])) {
                // line 64
                echo "                        ";
                $context["stepClass"] = "disabled";
                // line 65
                echo "                    ";
            } elseif (((isset($context["currentStepId"]) ? $context["currentStepId"] : $this->getContext($context, "currentStepId")) == $context["stepId"])) {
                // line 66
                echo "                        ";
                $context["stepClass"] = "active";
                // line 67
                echo "                    ";
            } else {
                // line 68
                echo "                        ";
                $context["stepClass"] = "";
                // line 69
                echo "                    ";
            }
            // line 70
            echo "                    <li class=\"list-group-item ";
            echo twig_escape_filter($this->env, (isset($context["stepClass"]) ? $context["stepClass"] : $this->getContext($context, "stepClass")), "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, ($context["stepId"] + 1), "html", null, true);
            echo ". ";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array($context["stepName"])), "html", null, true);
            echo "</li>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['stepId'], $context['stepName'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 72
        echo "            </ul>
        </div>
        <div class=\"col-sm-9 content\">
            ";
        // line 75
        ob_start();
        // line 76
        echo "                <p class=\"next-step\">
                    <a class=\"btn btn-lg\" href=\"";
        // line 77
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('linkTo')->getCallable(), array(array("action" => (isset($context["nextModuleName"]) ? $context["nextModuleName"] : $this->getContext($context, "nextModuleName")), "token_auth" => null, "method" => null))), "html", null, true);
        echo "\">
                        ";
        // line 78
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Next")), "html", null, true);
        echo " &raquo;</a>
                </p>
            ";
        $context["nextButton"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 81
        echo "            ";
        if ((array_key_exists("showNextStepAtTop", $context) && (isset($context["showNextStepAtTop"]) ? $context["showNextStepAtTop"] : $this->getContext($context, "showNextStepAtTop")))) {
            // line 82
            echo "                ";
            echo twig_escape_filter($this->env, (isset($context["nextButton"]) ? $context["nextButton"] : $this->getContext($context, "nextButton")), "html", null, true);
            echo "
            ";
        }
        // line 84
        echo "
            ";
        // line 85
        $this->displayBlock('content', $context, $blocks);
        // line 86
        echo "
            ";
        // line 87
        if ((isset($context["showNextStep"]) ? $context["showNextStep"] : $this->getContext($context, "showNextStep"))) {
            // line 88
            echo "                ";
            echo twig_escape_filter($this->env, (isset($context["nextButton"]) ? $context["nextButton"] : $this->getContext($context, "nextButton")), "html", null, true);
            echo "
            ";
        }
        // line 90
        echo "        </div>
    </div>

</div>

</body>
</html>
";
    }

    // line 85
    public function block_content($context, array $blocks = array())
    {
    }

    public function getTemplateName()
    {
        return "@Installation/layout.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  195 => 85,  184 => 90,  178 => 88,  176 => 87,  173 => 86,  171 => 85,  168 => 84,  162 => 82,  159 => 81,  153 => 78,  149 => 77,  146 => 76,  144 => 75,  139 => 72,  126 => 70,  123 => 69,  120 => 68,  117 => 67,  114 => 66,  111 => 65,  108 => 64,  105 => 63,  101 => 62,  88 => 52,  82 => 49,  78 => 48,  70 => 43,  64 => 40,  27 => 6,  20 => 1,);
    }
}
