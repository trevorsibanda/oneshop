<?php

/* @CoreHome/_dataTableHead.twig */
class __TwigTemplate_70b4ec42804f9f64ad2d5bebd8cebef3b8a2151d9233b388949bb4b5b5af0f98 extends Twig_Template
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
        echo "<thead>
   <tr>
       ";
        // line 3
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "columns_to_display", array()));
        $context['loop'] = array(
          'parent' => $context['_parent'],
          'index0' => 0,
          'index'  => 1,
          'first'  => true,
        );
        if (is_array($context['_seq']) || (is_object($context['_seq']) && $context['_seq'] instanceof Countable)) {
            $length = count($context['_seq']);
            $context['loop']['revindex0'] = $length - 1;
            $context['loop']['revindex'] = $length;
            $context['loop']['length'] = $length;
            $context['loop']['last'] = 1 === $length;
        }
        foreach ($context['_seq'] as $context["_key"] => $context["column"]) {
            // line 4
            echo "           <th class=\"";
            if ($this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "enable_sort", array())) {
                echo "sortable";
            }
            echo " ";
            if ($this->getAttribute($context["loop"], "first", array())) {
                echo "first";
            } elseif ($this->getAttribute($context["loop"], "last", array())) {
                echo "last";
            }
            echo "\" id=\"";
            echo twig_escape_filter($this->env, $context["column"], "html", null, true);
            echo "\">
               ";
            // line 5
            if ( !twig_test_empty((($this->getAttribute($this->getAttribute((isset($context["properties"]) ? $context["properties"] : null), "metrics_documentation", array(), "any", false, true), $context["column"], array(), "array", true, true)) ? (_twig_default_filter($this->getAttribute($this->getAttribute((isset($context["properties"]) ? $context["properties"] : null), "metrics_documentation", array(), "any", false, true), $context["column"], array(), "array"))) : ("")))) {
                // line 6
                echo "                   <div class=\"columnDocumentation\">
                       <div class=\"columnDocumentationTitle\">
                           <span class=\"icon-help\"></span>
                           ";
                // line 9
                echo (($this->getAttribute($this->getAttribute((isset($context["properties"]) ? $context["properties"] : null), "translations", array(), "any", false, true), $context["column"], array(), "array", true, true)) ? (_twig_default_filter($this->getAttribute($this->getAttribute((isset($context["properties"]) ? $context["properties"] : null), "translations", array(), "any", false, true), $context["column"], array(), "array"), $context["column"])) : ($context["column"]));
                echo "
                       </div>
                       ";
                // line 11
                echo $this->getAttribute($this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "metrics_documentation", array()), $context["column"], array(), "array");
                echo "
                   </div>
               ";
            }
            // line 14
            echo "               <div id=\"thDIV\" class=\"thDIV\">";
            echo (($this->getAttribute($this->getAttribute((isset($context["properties"]) ? $context["properties"] : null), "translations", array(), "any", false, true), $context["column"], array(), "array", true, true)) ? (_twig_default_filter($this->getAttribute($this->getAttribute((isset($context["properties"]) ? $context["properties"] : null), "translations", array(), "any", false, true), $context["column"], array(), "array"), $context["column"])) : ($context["column"]));
            echo "</div>
           </th>
       ";
            ++$context['loop']['index0'];
            ++$context['loop']['index'];
            $context['loop']['first'] = false;
            if (isset($context['loop']['length'])) {
                --$context['loop']['revindex0'];
                --$context['loop']['revindex'];
                $context['loop']['last'] = 0 === $context['loop']['revindex0'];
            }
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['column'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 17
        echo "   </tr>
</thead>
";
    }

    public function getTemplateName()
    {
        return "@CoreHome/_dataTableHead.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  91 => 17,  73 => 14,  67 => 11,  62 => 9,  57 => 6,  55 => 5,  40 => 4,  23 => 3,  19 => 1,);
    }
}
