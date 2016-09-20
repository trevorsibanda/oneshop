<?php

/* @Installation/systemCheckPage.twig */
class __TwigTemplate_fc3c4494be8e4ec14230832296d4327f624b1cfeec85cf9e81017a9d04fce017 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("admin.twig", "@Installation/systemCheckPage.twig", 1);
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
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_SystemCheck")), "html", null, true);
        $context["title"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 1
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        echo "
    <h2 piwik-enriched-headline>";
        // line 7
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : $this->getContext($context, "title")), "html", null, true);
        echo "</h2>

    ";
        // line 9
        if ($this->getAttribute((isset($context["diagnosticReport"]) ? $context["diagnosticReport"] : $this->getContext($context, "diagnosticReport")), "hasErrors", array(), "method")) {
            // line 10
            echo "        <div class=\"alert alert-danger\">
            ";
            // line 11
            echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_SystemCheckSummaryThereWereErrors", "<strong>", "</strong>", "<strong><em>", "</em></strong>"));
            echo "
            ";
            // line 12
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_SeeBelowForMoreInfo")), "html", null, true);
            echo "
        </div>
    ";
        } elseif ($this->getAttribute(        // line 14
(isset($context["diagnosticReport"]) ? $context["diagnosticReport"] : $this->getContext($context, "diagnosticReport")), "hasWarnings", array(), "method")) {
            // line 15
            echo "        <div class=\"alert alert-warning\">
            ";
            // line 16
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_SystemCheckSummaryThereWereWarnings")), "html", null, true);
            echo "
            ";
            // line 17
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_SeeBelowForMoreInfo")), "html", null, true);
            echo "
        </div>
    ";
        } else {
            // line 20
            echo "        <div class=\"alert alert-success\">
            ";
            // line 21
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_SystemCheckSummaryNoProblems")), "html", null, true);
            echo "
        </div>
    ";
        }
        // line 24
        echo "
    ";
        // line 25
        $this->loadTemplate("@Installation/_systemCheckSection.twig", "@Installation/systemCheckPage.twig", 25)->display($context);
        // line 26
        echo "
";
    }

    public function getTemplateName()
    {
        return "@Installation/systemCheckPage.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  87 => 26,  85 => 25,  82 => 24,  76 => 21,  73 => 20,  67 => 17,  63 => 16,  60 => 15,  58 => 14,  53 => 12,  49 => 11,  46 => 10,  44 => 9,  39 => 7,  36 => 6,  33 => 5,  29 => 1,  25 => 3,  11 => 1,);
    }
}
