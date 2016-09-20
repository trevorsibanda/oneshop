<?php

/* genericForm.twig */
class __TwigTemplate_7fa2697cdf30b7f37857df4aec6bc320c5686f9cdba528f390014ffd5f50ed31 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        if ($this->getAttribute((isset($context["form_data"]) ? $context["form_data"] : $this->getContext($context, "form_data")), "errors", array())) {
            // line 2
            echo "\t<div class=\"alert alert-warning\">
\t\t<strong>";
            // line 3
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Installation_PleaseFixTheFollowingErrors")), "html", null, true);
            echo ":</strong>
\t\t<ul>
            ";
            // line 5
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["form_data"]) ? $context["form_data"] : $this->getContext($context, "form_data")), "errors", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["data"]) {
                // line 6
                echo "\t\t\t\t<li>";
                echo $context["data"];
                echo "</li>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['data'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 8
            echo "\t\t</ul>
\t</div>
";
        }
        // line 11
        echo "
<form ";
        // line 12
        echo $this->getAttribute((isset($context["form_data"]) ? $context["form_data"] : $this->getContext($context, "form_data")), "attributes", array());
        echo ">
    ";
        // line 14
        echo "    ";
        echo twig_join_filter($this->getAttribute((isset($context["form_data"]) ? $context["form_data"] : $this->getContext($context, "form_data")), "hidden", array()));
        echo "

    ";
        // line 16
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["element_list"]) ? $context["element_list"] : $this->getContext($context, "element_list")));
        foreach ($context['_seq'] as $context["_key"] => $context["fieldname"]) {
            // line 17
            echo "    ";
            if ($this->getAttribute((isset($context["form_data"]) ? $context["form_data"] : null), $context["fieldname"], array(), "array", true, true)) {
                // line 18
                echo "            <div class=\"form-group\">
                ";
                // line 19
                if (($this->getAttribute($this->getAttribute((isset($context["form_data"]) ? $context["form_data"] : $this->getContext($context, "form_data")), $context["fieldname"], array(), "array"), "type", array()) == "checkbox")) {
                    // line 20
                    echo "                    <label class=\"checkbox\">
                        ";
                    // line 21
                    echo $this->getAttribute($this->getAttribute((isset($context["form_data"]) ? $context["form_data"] : $this->getContext($context, "form_data")), $context["fieldname"], array(), "array"), "html", array());
                    echo "
                    </label>
                ";
                } elseif ($this->getAttribute($this->getAttribute(                // line 23
(isset($context["form_data"]) ? $context["form_data"] : $this->getContext($context, "form_data")), $context["fieldname"], array(), "array"), "label", array())) {
                    // line 24
                    echo "                    <label>
                        ";
                    // line 25
                    echo $this->getAttribute($this->getAttribute((isset($context["form_data"]) ? $context["form_data"] : $this->getContext($context, "form_data")), $context["fieldname"], array(), "array"), "label", array());
                    echo "
                    </label>
                    ";
                    // line 27
                    echo $this->getAttribute($this->getAttribute((isset($context["form_data"]) ? $context["form_data"] : $this->getContext($context, "form_data")), $context["fieldname"], array(), "array"), "html", array());
                    echo "
                ";
                } elseif (($this->getAttribute($this->getAttribute(                // line 28
(isset($context["form_data"]) ? $context["form_data"] : $this->getContext($context, "form_data")), $context["fieldname"], array(), "array"), "type", array()) == "hidden")) {
                    // line 29
                    echo "                    ";
                    echo $this->getAttribute($this->getAttribute((isset($context["form_data"]) ? $context["form_data"] : $this->getContext($context, "form_data")), $context["fieldname"], array(), "array"), "html", array());
                    echo "
                ";
                }
                // line 31
                echo "\t\t</div>
    ";
            }
            // line 33
            echo "    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['fieldname'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 34
        echo "
\t";
        // line 35
        echo $this->getAttribute($this->getAttribute((isset($context["form_data"]) ? $context["form_data"] : $this->getContext($context, "form_data")), "submit", array()), "html", array());
        echo "
</form>
";
    }

    public function getTemplateName()
    {
        return "genericForm.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  115 => 35,  112 => 34,  106 => 33,  102 => 31,  96 => 29,  94 => 28,  90 => 27,  85 => 25,  82 => 24,  80 => 23,  75 => 21,  72 => 20,  70 => 19,  67 => 18,  64 => 17,  60 => 16,  54 => 14,  50 => 12,  47 => 11,  42 => 8,  33 => 6,  29 => 5,  24 => 3,  21 => 2,  19 => 1,);
    }
}
