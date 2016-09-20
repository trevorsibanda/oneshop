<?php

/* dashboard.twig */
class __TwigTemplate_b6a3c0b45b510e732373215cd0ba4326c8270b972d1ef5fe429157cad7257954 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "dashboard.twig", 1);
        $this->blocks = array(
            'head' => array($this, 'block_head'),
            'pageDescription' => array($this, 'block_pageDescription'),
            'body' => array($this, 'block_body'),
            'root' => array($this, 'block_root'),
            'topcontrols' => array($this, 'block_topcontrols'),
            'notification' => array($this, 'block_notification'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 11
        ob_start();
        echo (isset($context["siteName"]) ? $context["siteName"] : $this->getContext($context, "siteName"));
        echo " - ";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreHome_WebAnalyticsReports")), "html", null, true);
        $context["title"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 15
        $context["bodyClass"] = call_user_func_array($this->env->getFunction('postEvent')->getCallable(), array("Template.bodyClass", "dashboard"));
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

    <!--[if lt IE 9]>
    <script language=\"javascript\" type=\"text/javascript\" src=\"libs/jqplot/excanvas.min.js\"></script>
    <![endif]-->
";
    }

    // line 13
    public function block_pageDescription($context, array $blocks = array())
    {
        echo "Web Analytics report for ";
        echo twig_escape_filter($this->env, (isset($context["siteName"]) ? $context["siteName"] : $this->getContext($context, "siteName")), "html_attr");
        echo " - Piwik";
    }

    // line 17
    public function block_body($context, array $blocks = array())
    {
        // line 18
        echo "    ";
        $this->displayParentBlock("body", $context, $blocks);
        echo "
    ";
        // line 19
        echo call_user_func_array($this->env->getFunction('postEvent')->getCallable(), array("Template.footer"));
        echo "
";
    }

    // line 22
    public function block_root($context, array $blocks = array())
    {
        // line 23
        echo "    ";
        $this->loadTemplate("@CoreHome/_warningInvalidHost.twig", "dashboard.twig", 23)->display($context);
        // line 24
        echo "    ";
        $this->loadTemplate("@CoreHome/_topScreen.twig", "dashboard.twig", 24)->display($context);
        // line 25
        echo "
    <div class=\"ui-confirm\" id=\"alert\">
        <h2></h2>
        <input role=\"yes\" type=\"button\" value=\"";
        // line 28
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Ok")), "html", null, true);
        echo "\"/>
    </div>

    ";
        // line 31
        echo call_user_func_array($this->env->getFunction('postEvent')->getCallable(), array("Template.beforeContent", "dashboard", (isset($context["currentModule"]) ? $context["currentModule"] : $this->getContext($context, "currentModule"))));
        echo "

    <div class=\"page\">

        ";
        // line 35
        if ((array_key_exists("menu", $context) && (isset($context["menu"]) ? $context["menu"] : $this->getContext($context, "menu")))) {
            // line 36
            echo "            ";
            $context["menuMacro"] = $this->loadTemplate("@CoreHome/_menu.twig", "dashboard.twig", 36);
            // line 37
            echo "            ";
            echo $context["menuMacro"]->getmenu((isset($context["menu"]) ? $context["menu"] : $this->getContext($context, "menu")), true, "Menu--dashboard");
            echo "
        ";
        }
        // line 39
        echo "
        <div class=\"pageWrap\">

            <a name=\"main\"></a>

            <div class=\"top_controls\">
                ";
        // line 45
        $this->displayBlock('topcontrols', $context, $blocks);
        // line 47
        echo "            </div>

            ";
        // line 49
        $this->displayBlock('notification', $context, $blocks);
        // line 52
        echo "
            ";
        // line 53
        $this->displayBlock('content', $context, $blocks);
        // line 55
        echo "
            <div class=\"clear\"></div>
        </div>

    </div>
";
    }

    // line 45
    public function block_topcontrols($context, array $blocks = array())
    {
        // line 46
        echo "                ";
    }

    // line 49
    public function block_notification($context, array $blocks = array())
    {
        // line 50
        echo "                ";
        $this->loadTemplate("@CoreHome/_notifications.twig", "dashboard.twig", 50)->display($context);
        // line 51
        echo "            ";
    }

    // line 53
    public function block_content($context, array $blocks = array())
    {
        // line 54
        echo "            ";
    }

    public function getTemplateName()
    {
        return "dashboard.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  167 => 54,  164 => 53,  160 => 51,  157 => 50,  154 => 49,  150 => 46,  147 => 45,  138 => 55,  136 => 53,  133 => 52,  131 => 49,  127 => 47,  125 => 45,  117 => 39,  111 => 37,  108 => 36,  106 => 35,  99 => 31,  93 => 28,  88 => 25,  85 => 24,  82 => 23,  79 => 22,  73 => 19,  68 => 18,  65 => 17,  57 => 13,  46 => 4,  43 => 3,  39 => 1,  37 => 15,  31 => 11,  11 => 1,);
    }
}
