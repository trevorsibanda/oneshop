<?php

/* @CoreHome/_notifications.twig */
class __TwigTemplate_76efac22b1adbccbbdfc5c76aa733b661bff6ad99f6f23155d69f24397c94186 extends Twig_Template
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
        echo "<div id=\"notificationContainer\">
    ";
        // line 2
        if (twig_length_filter($this->env, (isset($context["notifications"]) ? $context["notifications"] : $this->getContext($context, "notifications")))) {
            // line 3
            echo "        ";
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["notifications"]) ? $context["notifications"] : $this->getContext($context, "notifications")));
            foreach ($context['_seq'] as $context["notificationId"] => $context["n"]) {
                // line 4
                echo "
            ";
                // line 5
                echo call_user_func_array($this->env->getFilter('notification')->getCallable(), array($this->getAttribute($context["n"], "message", array()), array("id" => $context["notificationId"], "type" => $this->getAttribute($context["n"], "type", array()), "title" => $this->getAttribute($context["n"], "title", array()), "noclear" => $this->getAttribute($context["n"], "hasNoClear", array()), "context" => $this->getAttribute($context["n"], "context", array()), "raw" => $this->getAttribute($context["n"], "raw", array())), false));
                echo "

        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['notificationId'], $context['n'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 8
            echo "    ";
        }
        // line 9
        echo "</div>
";
    }

    public function getTemplateName()
    {
        return "@CoreHome/_notifications.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  44 => 9,  41 => 8,  32 => 5,  29 => 4,  24 => 3,  22 => 2,  19 => 1,);
    }
}
