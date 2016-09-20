<?php

/* admin.twig */
class __TwigTemplate_69efec511e0dc7bc902a3d70808f04c0e3f3f690f0edede30ee7dd5aa22d511a extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("layout.twig", "admin.twig", 1);
        $this->blocks = array(
            'body' => array($this, 'block_body'),
            'root' => array($this, 'block_root'),
            'topcontrols' => array($this, 'block_topcontrols'),
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 3
        ob_start();
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_Administration")), "html", null, true);
        $context["categoryTitle"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 5
        $context["bodyClass"] = call_user_func_array($this->env->getFunction('postEvent')->getCallable(), array("Template.bodyClass", "admin"));
        // line 1
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 7
    public function block_body($context, array $blocks = array())
    {
        // line 8
        echo "    ";
        if ((isset($context["isSuperUser"]) ? $context["isSuperUser"] : $this->getContext($context, "isSuperUser"))) {
            // line 9
            echo "        ";
            $context["topMenuModule"] = "CoreAdminHome";
            // line 10
            echo "        ";
            $context["topMenuAction"] = "generalSettings";
            // line 11
            echo "    ";
        } else {
            // line 12
            echo "        ";
            $context["topMenuModule"] = "SitesManager";
            // line 13
            echo "        ";
            $context["topMenuAction"] = "index";
            // line 14
            echo "    ";
        }
        // line 15
        echo "    ";
        $this->displayParentBlock("body", $context, $blocks);
        echo "
";
    }

    // line 18
    public function block_root($context, array $blocks = array())
    {
        // line 19
        echo "    ";
        $this->loadTemplate("@CoreHome/_topScreen.twig", "admin.twig", 19)->display($context);
        // line 20
        echo "
    ";
        // line 21
        $context["ajax"] = $this->loadTemplate("ajaxMacros.twig", "admin.twig", 21);
        // line 22
        echo "    ";
        echo $context["ajax"]->getrequestErrorDiv(((array_key_exists("emailSuperUser", $context)) ? (_twig_default_filter((isset($context["emailSuperUser"]) ? $context["emailSuperUser"] : $this->getContext($context, "emailSuperUser")), "")) : ("")));
        echo "
    ";
        // line 23
        echo call_user_func_array($this->env->getFunction('postEvent')->getCallable(), array("Template.beforeContent", "admin", (isset($context["currentModule"]) ? $context["currentModule"] : $this->getContext($context, "currentModule"))));
        echo "

    <div class=\"page\">

        ";
        // line 27
        if (( !array_key_exists("showMenu", $context) || (isset($context["showMenu"]) ? $context["showMenu"] : $this->getContext($context, "showMenu")))) {
            // line 28
            echo "            ";
            $context["menu"] = $this->loadTemplate("@CoreHome/_menu.twig", "admin.twig", 28);
            // line 29
            echo "            ";
            echo $context["menu"]->getmenu((isset($context["adminMenu"]) ? $context["adminMenu"] : $this->getContext($context, "adminMenu")), false, "Menu--admin");
            echo "
        ";
        }
        // line 31
        echo "
        <div class=\"pageWrap\">

            <div class=\"top_controls\">
                ";
        // line 35
        $this->displayBlock('topcontrols', $context, $blocks);
        // line 37
        echo "
                ";
        // line 38
        $this->loadTemplate("@CoreHome/_headerMessage.twig", "admin.twig", 38)->display($context);
        // line 39
        echo "            </div>

            <div class=\"admin\" id=\"content\">
                ";
        // line 42
        $this->loadTemplate("@CoreHome/_notifications.twig", "admin.twig", 42)->display($context);
        // line 43
        echo "                ";
        $this->loadTemplate("@CoreHome/_warningInvalidHost.twig", "admin.twig", 43)->display($context);
        // line 44
        echo "
                <div class=\"ui-confirm\" id=\"alert\">
                    <h2></h2>
                    <input role=\"no\" type=\"button\" value=\"";
        // line 47
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Ok")), "html", null, true);
        echo "\"/>
                </div>

                ";
        // line 50
        $this->displayBlock('content', $context, $blocks);
        // line 52
        echo "
            </div>
        </div>
    </div>


";
    }

    // line 35
    public function block_topcontrols($context, array $blocks = array())
    {
        // line 36
        echo "                ";
    }

    // line 50
    public function block_content($context, array $blocks = array())
    {
        // line 51
        echo "                ";
    }

    public function getTemplateName()
    {
        return "admin.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  159 => 51,  156 => 50,  152 => 36,  149 => 35,  139 => 52,  137 => 50,  131 => 47,  126 => 44,  123 => 43,  121 => 42,  116 => 39,  114 => 38,  111 => 37,  109 => 35,  103 => 31,  97 => 29,  94 => 28,  92 => 27,  85 => 23,  80 => 22,  78 => 21,  75 => 20,  72 => 19,  69 => 18,  62 => 15,  59 => 14,  56 => 13,  53 => 12,  50 => 11,  47 => 10,  44 => 9,  41 => 8,  38 => 7,  34 => 1,  32 => 5,  28 => 3,  11 => 1,);
    }
}
