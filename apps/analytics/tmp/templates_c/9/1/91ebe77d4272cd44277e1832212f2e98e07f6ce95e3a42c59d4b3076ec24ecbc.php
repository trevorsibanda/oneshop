<?php

/* @MultiSites/getSitesInfo.twig */
class __TwigTemplate_91ebe77d4272cd44277e1832212f2e98e07f6ce95e3a42c59d4b3076ec24ecbc extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->blocks = array(
            'topcontrols' => array($this, 'block_topcontrols'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        // line 1
        return $this->loadTemplate((((isset($context["isWidgetized"]) ? $context["isWidgetized"] : $this->getContext($context, "isWidgetized"))) ? ("empty.twig") : ("dashboard.twig")), "@MultiSites/getSitesInfo.twig", 1);
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->getParent($context)->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_topcontrols($context, array $blocks = array())
    {
        // line 4
        echo "    ";
        if ( !(isset($context["isWidgetized"]) ? $context["isWidgetized"] : $this->getContext($context, "isWidgetized"))) {
            // line 5
            echo "        <div class=\"top_controls\">
            ";
            // line 6
            $this->loadTemplate("@CoreHome/_periodSelect.twig", "@MultiSites/getSitesInfo.twig", 6)->display($context);
            // line 7
            echo "            ";
            $this->loadTemplate("@CoreHome/_headerMessage.twig", "@MultiSites/getSitesInfo.twig", 7)->display($context);
            // line 8
            echo "        </div>
    ";
        }
    }

    // line 12
    public function block_content($context, array $blocks = array())
    {
        // line 13
        echo "<div class=\"container\" id=\"multisites\">

    <div id=\"main\">
        <div piwik-multisites-dashboard
             display-revenue-column=\"";
        // line 17
        if ((isset($context["displayRevenueColumn"]) ? $context["displayRevenueColumn"] : $this->getContext($context, "displayRevenueColumn"))) {
            echo "true";
        } else {
            echo "false";
        }
        echo "\"
             page-size=\"";
        // line 18
        echo twig_escape_filter($this->env, (isset($context["limit"]) ? $context["limit"] : $this->getContext($context, "limit")), "html", null, true);
        echo "\"
             show-sparklines=\"";
        // line 19
        if ((isset($context["show_sparklines"]) ? $context["show_sparklines"] : $this->getContext($context, "show_sparklines"))) {
            echo "true";
        } else {
            echo "false";
        }
        echo "\"
             date-sparkline=\"";
        // line 20
        echo twig_escape_filter($this->env, (isset($context["dateSparkline"]) ? $context["dateSparkline"] : $this->getContext($context, "dateSparkline")), "html", null, true);
        echo "\"
             auto-refresh-today-report=\"";
        // line 21
        echo twig_escape_filter($this->env, (isset($context["autoRefreshTodayReport"]) ? $context["autoRefreshTodayReport"] : $this->getContext($context, "autoRefreshTodayReport")), "html", null, true);
        echo "\">
        </div>
    </div>
</div>
";
    }

    public function getTemplateName()
    {
        return "@MultiSites/getSitesInfo.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  81 => 21,  77 => 20,  69 => 19,  65 => 18,  57 => 17,  51 => 13,  48 => 12,  42 => 8,  39 => 7,  37 => 6,  34 => 5,  31 => 4,  28 => 3,  19 => 1,);
    }
}
