<?php

/* @CoreHome/_dataTableCell.twig */
class __TwigTemplate_658da0be120029122d61d70b23d28bcc9f3161a565c964a9b5b19d4d408d78a7 extends Twig_Template
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
        ob_start();
        // line 2
        $context["tooltipIndex"] = ((isset($context["column"]) ? $context["column"] : $this->getContext($context, "column")) . "_tooltip");
        // line 3
        if ($this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getMetadata", array(0 => (isset($context["tooltipIndex"]) ? $context["tooltipIndex"] : $this->getContext($context, "tooltipIndex"))), "method")) {
            echo "<span class=\"cell-tooltip\" data-tooltip=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getMetadata", array(0 => (isset($context["tooltipIndex"]) ? $context["tooltipIndex"] : $this->getContext($context, "tooltipIndex"))), "method"), "html", null, true);
            echo "\">";
        }
        // line 4
        if ((( !$this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getIdSubDataTable", array(), "method") && ((isset($context["column"]) ? $context["column"] : $this->getContext($context, "column")) == "label")) && $this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getMetadata", array(0 => "url"), "method"))) {
            // line 5
            echo "    <a rel=\"noreferrer\" target=\"_blank\" href='";
            if (!twig_in_filter(twig_slice($this->env, $this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getMetadata", array(0 => "url"), "method"), 0, 4), array(0 => "http", 1 => "ftp:"))) {
                echo "http://";
            }
            echo call_user_func_array($this->env->getFilter('rawSafeDecoded')->getCallable(), array($this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getMetadata", array(0 => "url"), "method")));
            echo "'>
    ";
            // line 6
            if ( !$this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getMetadata", array(0 => "logo"), "method")) {
                // line 7
                echo "        <img class=\"link\" width=\"10\" height=\"9\" src=\"plugins/Morpheus/images/link.gif\"/>
    ";
            }
        }
        // line 10
        echo "
";
        // line 11
        $context["totals"] = $this->getAttribute((isset($context["dataTable"]) ? $context["dataTable"] : $this->getContext($context, "dataTable")), "getMetadata", array(0 => "totals"), "method");
        // line 12
        if (twig_in_filter((isset($context["column"]) ? $context["column"] : $this->getContext($context, "column")), twig_get_array_keys_filter((isset($context["totals"]) ? $context["totals"] : $this->getContext($context, "totals"))))) {
            // line 13
            $context["labelColumn"] = twig_first($this->env, (isset($context["columns_to_display"]) ? $context["columns_to_display"] : $this->getContext($context, "columns_to_display")));
            // line 14
            echo "    ";
            $context["reportTotal"] = $this->getAttribute((isset($context["totals"]) ? $context["totals"] : $this->getContext($context, "totals")), (isset($context["column"]) ? $context["column"] : $this->getContext($context, "column")), array(), "array");
            // line 15
            echo "    ";
            if (((array_key_exists("siteSummary", $context) &&  !twig_test_empty((isset($context["siteSummary"]) ? $context["siteSummary"] : $this->getContext($context, "siteSummary")))) && $this->getAttribute((isset($context["siteSummary"]) ? $context["siteSummary"] : $this->getContext($context, "siteSummary")), "getFirstRow", array()))) {
                // line 16
                echo "        ";
                $context["siteTotal"] = $this->getAttribute($this->getAttribute((isset($context["siteSummary"]) ? $context["siteSummary"] : $this->getContext($context, "siteSummary")), "getFirstRow", array()), "getColumn", array(0 => (isset($context["column"]) ? $context["column"] : $this->getContext($context, "column"))), "method");
                // line 17
                echo "    ";
            } else {
                // line 18
                echo "        ";
                $context["siteTotal"] = 0;
                // line 19
                echo "    ";
            }
            // line 20
            echo "    ";
            $context["rowPercentage"] = call_user_func_array($this->env->getFilter('percentage')->getCallable(), array($this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getColumn", array(0 => (isset($context["column"]) ? $context["column"] : $this->getContext($context, "column"))), "method"), (isset($context["reportTotal"]) ? $context["reportTotal"] : $this->getContext($context, "reportTotal")), 1));
            // line 21
            echo "    ";
            $context["metricTitle"] = (($this->getAttribute((isset($context["translations"]) ? $context["translations"] : null), (isset($context["column"]) ? $context["column"] : $this->getContext($context, "column")), array(), "array", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["translations"]) ? $context["translations"] : null), (isset($context["column"]) ? $context["column"] : $this->getContext($context, "column")), array(), "array"), (isset($context["column"]) ? $context["column"] : $this->getContext($context, "column")))) : ((isset($context["column"]) ? $context["column"] : $this->getContext($context, "column"))));
            // line 22
            echo "    ";
            $context["reportLabel"] = call_user_func_array($this->env->getFilter('truncate')->getCallable(), array($this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getColumn", array(0 => (isset($context["labelColumn"]) ? $context["labelColumn"] : $this->getContext($context, "labelColumn"))), "method"), 40));
            // line 23
            echo "
    ";
            // line 24
            $context["reportRatioTooltip"] = call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_ReportRatioTooltip", (isset($context["reportLabel"]) ? $context["reportLabel"] : $this->getContext($context, "reportLabel")), twig_escape_filter($this->env, (isset($context["rowPercentage"]) ? $context["rowPercentage"] : $this->getContext($context, "rowPercentage")), "html_attr"), twig_escape_filter($this->env, (isset($context["reportTotal"]) ? $context["reportTotal"] : $this->getContext($context, "reportTotal")), "html_attr"), twig_escape_filter($this->env, (isset($context["metricTitle"]) ? $context["metricTitle"] : $this->getContext($context, "metricTitle")), "html_attr"), twig_escape_filter($this->env, (($this->getAttribute((isset($context["translations"]) ? $context["translations"] : null), (isset($context["labelColumn"]) ? $context["labelColumn"] : $this->getContext($context, "labelColumn")), array(), "array", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["translations"]) ? $context["translations"] : null), (isset($context["labelColumn"]) ? $context["labelColumn"] : $this->getContext($context, "labelColumn")), array(), "array"), (isset($context["labelColumn"]) ? $context["labelColumn"] : $this->getContext($context, "labelColumn")))) : ((isset($context["labelColumn"]) ? $context["labelColumn"] : $this->getContext($context, "labelColumn")))), "html_attr")));
            // line 25
            echo "
    ";
            // line 26
            if (((isset($context["siteTotal"]) ? $context["siteTotal"] : $this->getContext($context, "siteTotal")) && ((isset($context["siteTotal"]) ? $context["siteTotal"] : $this->getContext($context, "siteTotal")) > (isset($context["reportTotal"]) ? $context["reportTotal"] : $this->getContext($context, "reportTotal"))))) {
                // line 27
                echo "        ";
                $context["totalPercentage"] = call_user_func_array($this->env->getFilter('percentage')->getCallable(), array($this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getColumn", array(0 => (isset($context["column"]) ? $context["column"] : $this->getContext($context, "column"))), "method"), (isset($context["siteTotal"]) ? $context["siteTotal"] : $this->getContext($context, "siteTotal")), 1));
                // line 28
                echo "        ";
                $context["totalRatioTooltip"] = call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_TotalRatioTooltip", (isset($context["totalPercentage"]) ? $context["totalPercentage"] : $this->getContext($context, "totalPercentage")), call_user_func_array($this->env->getFilter('number')->getCallable(), array((isset($context["siteTotal"]) ? $context["siteTotal"] : $this->getContext($context, "siteTotal")), 2, 0)), (isset($context["metricTitle"]) ? $context["metricTitle"] : $this->getContext($context, "metricTitle"))));
                // line 29
                echo "    ";
            } else {
                // line 30
                echo "        ";
                $context["totalRatioTooltip"] = "";
                // line 31
                echo "    ";
            }
            // line 32
            echo "
    <span class=\"ratio\" title=\"";
            // line 33
            echo (isset($context["reportRatioTooltip"]) ? $context["reportRatioTooltip"] : $this->getContext($context, "reportRatioTooltip"));
            echo " ";
            echo twig_escape_filter($this->env, (isset($context["totalRatioTooltip"]) ? $context["totalRatioTooltip"] : $this->getContext($context, "totalRatioTooltip")), "html_attr");
            echo "\">&nbsp;";
            echo twig_escape_filter($this->env, (isset($context["rowPercentage"]) ? $context["rowPercentage"] : $this->getContext($context, "rowPercentage")), "html", null, true);
            echo "</span>";
        }
        // line 35
        echo "
";
        // line 36
        if (((isset($context["column"]) ? $context["column"] : $this->getContext($context, "column")) == "label")) {
            // line 37
            echo "    ";
            $context["piwik"] = $this->loadTemplate("macros.twig", "@CoreHome/_dataTableCell.twig", 37);
            // line 38
            echo "
    <span class='label";
            // line 39
            if ($this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getMetadata", array(0 => "is_aggregate"), "method")) {
                echo " highlighted";
            }
            echo "'
    ";
            // line 40
            if ((array_key_exists("properties", $context) &&  !twig_test_empty($this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "tooltip_metadata_name", array())))) {
                echo "title=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getMetadata", array(0 => $this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "tooltip_metadata_name", array())), "method"), "html", null, true);
                echo "\"";
            }
            echo ">
        ";
            // line 41
            echo $context["piwik"]->getlogoHtml($this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getMetadata", array(), "method"), $this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getColumn", array(0 => "label"), "method"));
            echo "
        ";
            // line 42
            if ($this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getMetadata", array(0 => "html_label_prefix"), "method")) {
                echo "<span class='label-prefix'>";
                echo $this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getMetadata", array(0 => "html_label_prefix"), "method");
                echo "&nbsp;</span>";
            }
            // line 43
            if ($this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getMetadata", array(0 => "html_label_suffix"), "method")) {
                echo "<span class='label-suffix'>";
                echo $this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getMetadata", array(0 => "html_label_suffix"), "method");
                echo "</span>";
            }
        }
        // line 44
        echo "<span class=\"value\">";
        // line 45
        if ($this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getColumn", array(0 => (isset($context["column"]) ? $context["column"] : $this->getContext($context, "column"))), "method")) {
            if (((isset($context["column"]) ? $context["column"] : $this->getContext($context, "column")) == "label")) {
                echo $this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getColumn", array(0 => (isset($context["column"]) ? $context["column"] : $this->getContext($context, "column"))), "method");
            } else {
                echo call_user_func_array($this->env->getFilter('number')->getCallable(), array($this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getColumn", array(0 => (isset($context["column"]) ? $context["column"] : $this->getContext($context, "column"))), "method"), 2, 0));
            }
        } else {
            // line 46
            echo "-";
        }
        // line 47
        echo "</span>
";
        // line 48
        if (((isset($context["column"]) ? $context["column"] : $this->getContext($context, "column")) == "label")) {
            echo "</span>";
        }
        // line 49
        if ((( !$this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getIdSubDataTable", array(), "method") && ((isset($context["column"]) ? $context["column"] : $this->getContext($context, "column")) == "label")) && $this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getMetadata", array(0 => "url"), "method"))) {
            // line 50
            echo "    </a>
";
        }
        // line 52
        if ($this->getAttribute((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")), "getMetadata", array(0 => (isset($context["tooltipIndex"]) ? $context["tooltipIndex"] : $this->getContext($context, "tooltipIndex"))), "method")) {
            echo "</span>";
        }
        // line 53
        echo "
";
        echo trim(preg_replace('/>\s+</', '><', ob_get_clean()));
    }

    public function getTemplateName()
    {
        return "@CoreHome/_dataTableCell.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  190 => 53,  186 => 52,  182 => 50,  180 => 49,  176 => 48,  173 => 47,  170 => 46,  162 => 45,  160 => 44,  153 => 43,  147 => 42,  143 => 41,  135 => 40,  129 => 39,  126 => 38,  123 => 37,  121 => 36,  118 => 35,  110 => 33,  107 => 32,  104 => 31,  101 => 30,  98 => 29,  95 => 28,  92 => 27,  90 => 26,  87 => 25,  85 => 24,  82 => 23,  79 => 22,  76 => 21,  73 => 20,  70 => 19,  67 => 18,  64 => 17,  61 => 16,  58 => 15,  55 => 14,  53 => 13,  51 => 12,  49 => 11,  46 => 10,  41 => 7,  39 => 6,  31 => 5,  29 => 4,  23 => 3,  21 => 2,  19 => 1,);
    }
}
