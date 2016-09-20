<?php

/* @Installation/tablesCreation.twig */
class __TwigTemplate_c9f34194a62fd3a974901a646599888c60bf4d28d3e7752902ae5bc4bc495dee extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("@Installation/layout.twig", "@Installation/tablesCreation.twig", 1);
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
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_Tables")), "html", null, true);
        echo "</h2>

    ";
        // line 7
        if (array_key_exists("someTablesInstalled", $context)) {
            // line 8
            echo "        <div class=\"alert alert-warning\">
            ";
            // line 9
            echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_TablesWithSameNamesFound", "<span id='linkToggle'>", "</span>"));
            echo "
        </div>
        <p>
            ";
            // line 12
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_TablesFound")), "html", null, true);
            echo ":
        </p>
        <p>
            <em>";
            // line 15
            echo twig_escape_filter($this->env, (isset($context["tablesInstalled"]) ? $context["tablesInstalled"] : $this->getContext($context, "tablesInstalled")), "html", null, true);
            echo " </em>
        </p>
        ";
            // line 17
            if (array_key_exists("showReuseExistingTables", $context)) {
                // line 18
                echo "            <p>";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_TablesWarningHelp")), "html", null, true);
                echo "</p>
            <p class=\"next-step\">
                <a href=\"";
                // line 20
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('linkTo')->getCallable(), array(array("action" => "reuseTables"))), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_TablesReuse")), "html", null, true);
                echo " &raquo;</a>
            </p>
        ";
            } else {
                // line 23
                echo "            <p class=\"next-step\">
                <a href=\"";
                // line 24
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('linkTo')->getCallable(), array(array("action" => (isset($context["previousPreviousModuleName"]) ? $context["previousPreviousModuleName"] : $this->getContext($context, "previousPreviousModuleName"))))), "html", null, true);
                echo "\">&laquo; ";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_GoBackAndDefinePrefix")), "html", null, true);
                echo "</a>
            </p>
        ";
            }
            // line 27
            echo "        <p class=\"next-step\">
            <a href=\"";
            // line 28
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFunction('linkTo')->getCallable(), array(array("deleteTables" => 1))), "html", null, true);
            echo "\" id=\"eraseAllTables\">";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_TablesDelete")), "html", null, true);
            echo " &raquo;</a>
        </p>
    ";
        }
        // line 31
        echo "
    ";
        // line 32
        if (array_key_exists("existingTablesDeleted", $context)) {
            // line 33
            echo "        <div class=\"alert alert-success\">
            ";
            // line 34
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_TablesDeletedSuccess")), "html", null, true);
            echo "
        </div>
    ";
        }
        // line 37
        echo "
    ";
        // line 38
        if (array_key_exists("tablesCreated", $context)) {
            // line 39
            echo "        <div class=\"alert alert-success\">
            ";
            // line 40
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_TablesCreatedSuccess")), "html", null, true);
            echo "
        </div>
    ";
        }
        // line 43
        echo "
    <script>
        \$(document).ready(function () {
            var strConfirmEraseTables = \"";
        // line 46
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_ConfirmDeleteExistingTables", (("[" . (isset($context["tablesInstalled"]) ? $context["tablesInstalled"] : $this->getContext($context, "tablesInstalled"))) . "]"))), "html", null, true);
        echo " \";

            \$(\"#eraseAllTables\").click(function () {
                if (!confirm(strConfirmEraseTables)) {
                    return false;
                }
            });
        });
    </script>

";
    }

    public function getTemplateName()
    {
        return "@Installation/tablesCreation.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  132 => 46,  127 => 43,  121 => 40,  118 => 39,  116 => 38,  113 => 37,  107 => 34,  104 => 33,  102 => 32,  99 => 31,  91 => 28,  88 => 27,  80 => 24,  77 => 23,  69 => 20,  63 => 18,  61 => 17,  56 => 15,  50 => 12,  44 => 9,  41 => 8,  39 => 7,  34 => 5,  31 => 4,  28 => 3,  11 => 1,);
    }
}
