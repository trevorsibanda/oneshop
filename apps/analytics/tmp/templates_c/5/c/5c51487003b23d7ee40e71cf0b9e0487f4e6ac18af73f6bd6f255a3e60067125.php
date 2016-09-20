<?php

/* @Installation/finished.twig */
class __TwigTemplate_5c51487003b23d7ee40e71cf0b9e0487f4e6ac18af73f6bd6f255a3e60067125 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("@Installation/layout.twig", "@Installation/finished.twig", 1);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "@Installation/layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_content($context, array $blocks = array())
    {
        // line 4
        echo "
    <h2>";
        // line 5
        echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_Congratulations"));
        echo "</h2>

    ";
        // line 7
        echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_CongratulationsHelp"));
        echo "

    <h3>";
        // line 9
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_WelcomeToCommunity")), "html", null, true);
        echo "</h3>
    <p>
        ";
        // line 11
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_CollaborativeProject")), "html", null, true);
        echo "
    </p>
    <p>
        ";
        // line 14
        echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_GetInvolved", "<a  rel=\"noreferrer\"  target=\"_blank\" href=\"http://piwik.org/get-involved/\">", "</a>"));
        echo "
        ";
        // line 15
        echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_HelpTranslatePiwik", "<a rel='noreferrer' target='_blank' href='http://piwik.org/translations/'>", "</a>"));
        echo "
    </p>
    <p>";
        // line 17
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_WeHopeYouWillEnjoyPiwik")), "html", null, true);
        echo "</p>
    <p><i>";
        // line 18
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_HappyAnalysing")), "html", null, true);
        echo "</i></p>

    <h3>";
        // line 20
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_DefaultSettings")), "html", null, true);
        echo "</h3>
    <p>";
        // line 21
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_DefaultSettingsHelp")), "html", null, true);
        echo "</p>

    ";
        // line 23
        if (array_key_exists("errorMessage", $context)) {
            // line 24
            echo "        <div class=\"alert alert-danger\">
            ";
            // line 25
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Error")), "html", null, true);
            echo ":
            <br/>- ";
            // line 26
            echo (isset($context["errorMessage"]) ? $context["errorMessage"] : $this->getContext($context, "errorMessage"));
            echo "
        </div>
    ";
        }
        // line 29
        echo "
    <div class=\"installation-finished\">
        ";
        // line 31
        if (array_key_exists("form_data", $context)) {
            // line 32
            echo "            ";
            $this->loadTemplate("genericForm.twig", "@Installation/finished.twig", 32)->display($context);
            // line 33
            echo "        ";
        }
        // line 34
        echo "    </div>

";
    }

    public function getTemplateName()
    {
        return "@Installation/finished.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  109 => 34,  106 => 33,  103 => 32,  101 => 31,  97 => 29,  91 => 26,  87 => 25,  84 => 24,  82 => 23,  77 => 21,  73 => 20,  68 => 18,  64 => 17,  59 => 15,  55 => 14,  49 => 11,  44 => 9,  39 => 7,  34 => 5,  31 => 4,  28 => 3,  11 => 1,);
    }
}
