<?php

/* @CoreHome/_dataTableFooter.twig */
class __TwigTemplate_45d1281da27f1e18446332f022b28c2ea4759f935e99a10d53cced34fcf836d4 extends Twig_Template
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
        echo "<div class=\"dataTableFeatures\">

    <div class=\"dataTableFooterNavigation\">
        ";
        // line 4
        if ($this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "show_offset_information", array())) {
            // line 5
            echo "            <span>
                <span class=\"dataTablePages\"></span>
            </span>
        ";
        }
        // line 9
        echo "
        ";
        // line 10
        if ($this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "show_pagination_control", array())) {
            // line 11
            echo "            <span>
                <span class=\"dataTablePrevious\">&lsaquo; ";
            // line 12
            if ($this->getAttribute((isset($context["clientSideParameters"]) ? $context["clientSideParameters"] : null), "dataTablePreviousIsFirst", array(), "any", true, true)) {
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_First")), "html", null, true);
            } else {
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Previous")), "html", null, true);
            }
            echo " </span>
                <span class=\"dataTableNext\">";
            // line 13
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Next")), "html", null, true);
            echo " &rsaquo;</span>
            </span>
        ";
        }
        // line 16
        echo "
        ";
        // line 17
        if ($this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "show_search", array())) {
            // line 18
            echo "            <span class=\"dataTableSearchPattern\">
        <label for=\"widgetSearch_";
            // line 19
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "report_id", array()), "html", null, true);
            echo "\" style=\"display:none;\"> ";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Search")), "html", null, true);
            echo " ";
            echo twig_escape_filter($this->env, (($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["visualization"]) ? $context["visualization"] : null), "config", array(), "any", false, true), "translations", array(), "any", false, true), "label", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute($this->getAttribute($this->getAttribute((isset($context["visualization"]) ? $context["visualization"] : null), "config", array(), "any", false, true), "translations", array(), "any", false, true), "label", array()), "")) : ("")), "html", null, true);
            echo "</label>
                <input id=\"widgetSearch_";
            // line 20
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "report_id", array()), "html", null, true);
            echo "\" type=\"text\" class=\"searchInput\" length=\"15\" />
                <input type=\"submit\" value=\"";
            // line 21
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Search")), "html", null, true);
            echo "\" />
            </span>
        ";
        }
        // line 24
        echo "    </div>

    <span class=\"loadingPiwik\" style=\"display:none;\"><img src=\"plugins/Morpheus/images/loading-blue.gif\"/> ";
        // line 26
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_LoadingData")), "html", null, true);
        echo "</span>

    ";
        // line 28
        if ($this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "show_footer_icons", array())) {
            // line 29
            echo "        <div class=\"dataTableFooterIcons\">
            <div class=\"dataTableFooterWrap\">
                ";
            // line 31
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["footerIcons"]) ? $context["footerIcons"] : $this->getContext($context, "footerIcons")));
            foreach ($context['_seq'] as $context["_key"] => $context["footerIconGroup"]) {
                // line 32
                echo "                    <div class=\"tableIconsGroup\">
                    <span class=\"";
                // line 33
                echo twig_escape_filter($this->env, $this->getAttribute($context["footerIconGroup"], "class", array()), "html", null, true);
                echo "\">
                    ";
                // line 34
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($this->getAttribute($context["footerIconGroup"], "buttons", array()));
                foreach ($context['_seq'] as $context["_key"] => $context["footerIcon"]) {
                    if ($this->getAttribute($context["footerIcon"], "icon", array())) {
                        // line 35
                        echo "                        ";
                        $context["isActiveEcommerceView"] = ($this->getAttribute((isset($context["clientSideParameters"]) ? $context["clientSideParameters"] : null), "abandonedCarts", array(), "any", true, true) && ((($this->getAttribute(                        // line 36
$context["footerIcon"], "id", array()) == "ecommerceOrder") && ($this->getAttribute((isset($context["clientSideParameters"]) ? $context["clientSideParameters"] : $this->getContext($context, "clientSideParameters")), "abandonedCarts", array()) == 0)) || (($this->getAttribute(                        // line 37
$context["footerIcon"], "id", array()) == "ecommerceAbandonedCart") && ($this->getAttribute((isset($context["clientSideParameters"]) ? $context["clientSideParameters"] : $this->getContext($context, "clientSideParameters")), "abandonedCarts", array()) == 1))));
                        // line 38
                        echo "                        <span>
                            ";
                        // line 39
                        if (($this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "show_active_view_icon", array()) && (($this->getAttribute((isset($context["clientSideParameters"]) ? $context["clientSideParameters"] : $this->getContext($context, "clientSideParameters")), "viewDataTable", array()) == $this->getAttribute($context["footerIcon"], "id", array())) || (isset($context["isActiveEcommerceView"]) ? $context["isActiveEcommerceView"] : $this->getContext($context, "isActiveEcommerceView"))))) {
                            // line 40
                            echo "                                <img src=\"plugins/Morpheus/images/data_table_footer_active_item.png\" class=\"dataTableFooterActiveItem\"/>
                            ";
                        }
                        // line 42
                        echo "                            <a class=\"tableIcon ";
                        if ((($this->getAttribute((isset($context["clientSideParameters"]) ? $context["clientSideParameters"] : $this->getContext($context, "clientSideParameters")), "viewDataTable", array()) == $this->getAttribute($context["footerIcon"], "id", array())) || (isset($context["isActiveEcommerceView"]) ? $context["isActiveEcommerceView"] : $this->getContext($context, "isActiveEcommerceView")))) {
                            echo "activeIcon";
                        }
                        echo "\" data-footer-icon-id=\"";
                        echo twig_escape_filter($this->env, $this->getAttribute($context["footerIcon"], "id", array()), "html", null, true);
                        echo "\">
                                <img width=\"16\" height=\"16\" title=\"";
                        // line 43
                        echo twig_escape_filter($this->env, $this->getAttribute($context["footerIcon"], "title", array()), "html", null, true);
                        echo "\" src=\"";
                        echo twig_escape_filter($this->env, $this->getAttribute($context["footerIcon"], "icon", array()), "html", null, true);
                        echo "\"/>
                                ";
                        // line 44
                        if ($this->getAttribute($context["footerIcon"], "text", array(), "any", true, true)) {
                            echo "<span>";
                            echo twig_escape_filter($this->env, $this->getAttribute($context["footerIcon"], "text", array()), "html", null, true);
                            echo "</span>";
                        }
                        // line 45
                        echo "                            </a>
                        </span>
                    ";
                    }
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['_key'], $context['footerIcon'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 48
                echo "                    </span>
                    </div>
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['footerIconGroup'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 51
            echo "                <div class=\"tableIconsGroup\">
                    ";
            // line 52
            if (twig_test_empty((isset($context["footerIcons"]) ? $context["footerIcons"] : $this->getContext($context, "footerIcons")))) {
                // line 53
                echo "                        <img src=\"plugins/Morpheus/images/data_table_footer_active_item.png\" class=\"dataTableFooterActiveItem\"/>
                    ";
            }
            // line 55
            echo "                    <span class=\"exportToFormatIcons\">
                        <a class=\"tableIcon\" var=\"export\">
                            <img width=\"16\" height=\"16\" src=\"plugins/Morpheus/images/export.png\" title=\"";
            // line 57
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_ExportThisReport")), "html", null, true);
            echo "\"/>
                        </a>
                    </span>
                    <span class=\"exportToFormatItems\" style=\"display:none;\">
                    ";
            // line 61
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Export")), "html", null, true);
            echo ":
                    ";
            // line 62
            $context["requestParams"] = twig_jsonencode_filter($this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "request_parameters_to_modify", array()));
            // line 63
            echo "                    <a target=\"_blank\" requestParams=\"";
            echo twig_escape_filter($this->env, (isset($context["requestParams"]) ? $context["requestParams"] : $this->getContext($context, "requestParams")), "html_attr");
            echo "\" methodToCall=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "apiMethodToRequestDataTable", array()), "html", null, true);
            echo "\" format=\"CSV\" filter_limit=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "export_limit", array()), "html", null, true);
            echo "\">CSV</a> |
                    <a target=\"_blank\" requestParams=\"";
            // line 64
            echo twig_escape_filter($this->env, (isset($context["requestParams"]) ? $context["requestParams"] : $this->getContext($context, "requestParams")), "html_attr");
            echo "\" methodToCall=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "apiMethodToRequestDataTable", array()), "html", null, true);
            echo "\" format=\"TSV\" filter_limit=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "export_limit", array()), "html", null, true);
            echo "\">TSV (Excel)</a> |
                    <a target=\"_blank\" requestParams=\"";
            // line 65
            echo twig_escape_filter($this->env, (isset($context["requestParams"]) ? $context["requestParams"] : $this->getContext($context, "requestParams")), "html_attr");
            echo "\" methodToCall=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "apiMethodToRequestDataTable", array()), "html", null, true);
            echo "\" format=\"XML\" filter_limit=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "export_limit", array()), "html", null, true);
            echo "\">XML</a> |
                    <a target=\"_blank\" requestParams=\"";
            // line 66
            echo twig_escape_filter($this->env, (isset($context["requestParams"]) ? $context["requestParams"] : $this->getContext($context, "requestParams")), "html_attr");
            echo "\" methodToCall=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "apiMethodToRequestDataTable", array()), "html", null, true);
            echo "\" format=\"JSON\" filter_limit=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "export_limit", array()), "html", null, true);
            echo "\">Json</a> |
                    <a target=\"_blank\" requestParams=\"";
            // line 67
            echo twig_escape_filter($this->env, (isset($context["requestParams"]) ? $context["requestParams"] : $this->getContext($context, "requestParams")), "html_attr");
            echo "\" methodToCall=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "apiMethodToRequestDataTable", array()), "html", null, true);
            echo "\" format=\"PHP\" filter_limit=\"";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "export_limit", array()), "html", null, true);
            echo "\">Php</a>
                        ";
            // line 68
            if ($this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "show_export_as_rss_feed", array())) {
                // line 69
                echo "                            |
                            <a target=\"_blank\" requestParams=\"";
                // line 70
                echo twig_escape_filter($this->env, (isset($context["requestParams"]) ? $context["requestParams"] : $this->getContext($context, "requestParams")), "html_attr");
                echo "\" methodToCall=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "apiMethodToRequestDataTable", array()), "html", null, true);
                echo "\" format=\"RSS\" filter_limit=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "export_limit", array()), "html", null, true);
                echo "\" date=\"last10\">
                                <img border=\"0\" src=\"plugins/Morpheus/images/feed.png\"/>
                            </a>
                        ";
            }
            // line 74
            echo "                    </span>
                    ";
            // line 75
            if ($this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "show_export_as_image_icon", array())) {
                // line 76
                echo "                        <span id=\"dataTableFooterExportAsImageIcon\">
                            <a class=\"tableIcon\" href=\"#\" onclick=\"\$(this).closest('.dataTable').find('div.jqplot-target').trigger('piwikExportAsImage'); return false;\">
                                <img title=\"";
                // line 78
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_ExportAsImage")), "html", null, true);
                echo "\" src=\"plugins/Morpheus/images/image.png\"/>
                            </a>
                        </span>
                    ";
            }
            // line 82
            echo "                </div>

            </div>
            <div class=\"limitSelection ";
            // line 85
            if (( !$this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "show_pagination_control", array()) &&  !$this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "show_limit_control", array()))) {
                echo " hidden";
            }
            echo "\"
                 title=\"";
            // line 86
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_RowsToDisplay")), "html", null, true);
            echo "\" alt=\"";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_RowsToDisplay")), "html", null, true);
            echo "\"></div>
            <div class=\"tableConfiguration\">
                <a class=\"tableConfigurationIcon\" href=\"#\"></a>
                <ul>
                    ";
            // line 90
            if ($this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "show_flatten_table", array())) {
                // line 91
                echo "                        ";
                if (($this->getAttribute((isset($context["clientSideParameters"]) ? $context["clientSideParameters"] : null), "flat", array(), "any", true, true) && ($this->getAttribute((isset($context["clientSideParameters"]) ? $context["clientSideParameters"] : $this->getContext($context, "clientSideParameters")), "flat", array()) == 1))) {
                    // line 92
                    echo "                            <li>
                                <div class=\"configItem dataTableIncludeAggregateRows\"></div>
                            </li>
                        ";
                }
                // line 96
                echo "                        <li>
                            <div class=\"configItem dataTableFlatten\"></div>
                        </li>
                    ";
            }
            // line 100
            echo "                    ";
            if ($this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "show_exclude_low_population", array())) {
                // line 101
                echo "                        <li>
                            <div class=\"configItem dataTableExcludeLowPopulation\"></div>
                        </li>
                    ";
            }
            // line 105
            echo "                    ";
            if ( !twig_test_empty((($this->getAttribute((isset($context["properties"]) ? $context["properties"] : null), "show_pivot_by_subtable", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute((isset($context["properties"]) ? $context["properties"] : null), "show_pivot_by_subtable", array()))) : ("")))) {
                // line 106
                echo "                        <li>
                            <div class=\"configItem dataTablePivotBySubtable\"></div>
                        </li>
                    ";
            }
            // line 110
            echo "                </ul>
            </div>
            ";
            // line 112
            if ((call_user_func_array($this->env->getFunction('isPluginLoaded')->getCallable(), array("Annotations")) &&  !$this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "hide_annotations_view", array()))) {
                // line 113
                echo "                <div class=\"annotationView\" title=\"";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Annotations_IconDesc")), "html", null, true);
                echo "\">
                    <a class=\"tableIcon\">
                        <img width=\"16\" height=\"16\" src=\"plugins/Morpheus/images/annotations.png\"/>
                    </a>
                    <span>";
                // line 117
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Annotations_Annotations")), "html", null, true);
                echo "</span>
                </div>
            ";
            }
            // line 120
            echo "
            <div class=\"foldDataTableFooterDrawer\" title=\"";
            // line 121
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Close")), "html_attr");
            echo "\"
                    ><img width=\"7\" height=\"4\" src=\"plugins/Morpheus/images/sortasc_dark.png\"></div>

        </div>
        <div class=\"expandDataTableFooterDrawer\" title=\"";
            // line 125
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_ExpandDataTableFooter")), "html_attr");
            echo "\"
                ><img width=\"7\" height=\"4\" src=\"plugins/Morpheus/images/sortdesc_dark.png\" style=\"\"></div>
    ";
        }
        // line 128
        echo "
    <div class=\"datatableRelatedReports\">
        ";
        // line 130
        if (( !twig_test_empty($this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "related_reports", array())) && $this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "show_related_reports", array()))) {
            // line 131
            echo "            ";
            echo $this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "related_reports_title", array());
            echo "
            <ul style=\"list-style:none;";
            // line 132
            if ((twig_length_filter($this->env, $this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "related_reports", array())) == 1)) {
                echo "display:inline-block;";
            }
            echo "}\">
                <li><span href=\"";
            // line 133
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "self_url", array()), "html", null, true);
            echo "\" style=\"display:none;\">";
            echo twig_escape_filter($this->env, $this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "title", array()), "html", null, true);
            echo "</span></li>

                ";
            // line 135
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "related_reports", array()));
            foreach ($context['_seq'] as $context["reportUrl"] => $context["reportTitle"]) {
                // line 136
                echo "                    <li><span href=\"";
                echo twig_escape_filter($this->env, $context["reportUrl"], "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $context["reportTitle"], "html", null, true);
                echo "</span></li>
                ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['reportUrl'], $context['reportTitle'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 138
            echo "            </ul>
        ";
        }
        // line 140
        echo "    </div>

    ";
        // line 142
        if (($this->getAttribute((isset($context["properties"]) ? $context["properties"] : null), "show_footer_message", array(), "any", true, true) &&  !twig_test_empty($this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "show_footer_message", array())))) {
            // line 143
            echo "        <div class='datatableFooterMessage'>";
            echo $this->getAttribute((isset($context["properties"]) ? $context["properties"] : $this->getContext($context, "properties")), "show_footer_message", array());
            echo "</div>
    ";
        }
        // line 145
        echo "
</div>

<span class=\"loadingPiwikBelow\" style=\"display:none;\"><img src=\"plugins/Morpheus/images/loading-blue.gif\"/> ";
        // line 148
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_LoadingData")), "html", null, true);
        echo "</span>

<div class=\"dataTableSpacer\"></div>
";
    }

    public function getTemplateName()
    {
        return "@CoreHome/_dataTableFooter.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  413 => 148,  408 => 145,  402 => 143,  400 => 142,  396 => 140,  392 => 138,  381 => 136,  377 => 135,  370 => 133,  364 => 132,  359 => 131,  357 => 130,  353 => 128,  347 => 125,  340 => 121,  337 => 120,  331 => 117,  323 => 113,  321 => 112,  317 => 110,  311 => 106,  308 => 105,  302 => 101,  299 => 100,  293 => 96,  287 => 92,  284 => 91,  282 => 90,  273 => 86,  267 => 85,  262 => 82,  255 => 78,  251 => 76,  249 => 75,  246 => 74,  235 => 70,  232 => 69,  230 => 68,  222 => 67,  214 => 66,  206 => 65,  198 => 64,  189 => 63,  187 => 62,  183 => 61,  176 => 57,  172 => 55,  168 => 53,  166 => 52,  163 => 51,  155 => 48,  146 => 45,  140 => 44,  134 => 43,  125 => 42,  121 => 40,  119 => 39,  116 => 38,  114 => 37,  113 => 36,  111 => 35,  106 => 34,  102 => 33,  99 => 32,  95 => 31,  91 => 29,  89 => 28,  84 => 26,  80 => 24,  74 => 21,  70 => 20,  62 => 19,  59 => 18,  57 => 17,  54 => 16,  48 => 13,  40 => 12,  37 => 11,  35 => 10,  32 => 9,  26 => 5,  24 => 4,  19 => 1,);
    }
}
