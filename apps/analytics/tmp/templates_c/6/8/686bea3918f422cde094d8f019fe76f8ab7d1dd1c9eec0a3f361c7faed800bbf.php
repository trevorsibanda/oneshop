<?php

/* @UserCountryMap/visitorMap.twig */
class __TwigTemplate_686bea3918f422cde094d8f019fe76f8ab7d1dd1c9eec0a3f361c7faed800bbf extends Twig_Template
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
        echo "<section>
<div class=\"UserCountryMap\" style=\"position:relative; overflow:hidden;\">
    <div class=\"UserCountryMap_container\">
        <div class=\"UserCountryMap_map\" style=\"overflow:hidden;\"></div>
        <div class=\"UserCountryMap-overlay UserCountryMap-title\">
            <div class=\"content\">
                <!--<div class=\"map-title\" style=\"font-weight:bold; color:#9A9386;\"></div>-->
                <div class=\"map-stats\" style=\"color:#565656;\"></div>
            </div>
        </div>
        <div class=\"UserCountryMap-overlay UserCountryMap-legend\">
            <div class=\"content\">
            </div>
        </div>
        <div class=\"UserCountryMap-tooltip UserCountryMap-info\">
            <div class=\"content unlocated-stats\" data-tpl=\"";
        // line 16
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountryMap_Unlocated")), "html", null, true);
        echo "\">
            </div>
        </div>
        <div class=\"UserCountryMap-info-btn\" data-tooltip-target=\".UserCountryMap-tooltip\"></div>
    </div>
    <div class=\"mapWidgetStatus\">
        ";
        // line 22
        if ((isset($context["noData"]) ? $context["noData"] : $this->getContext($context, "noData"))) {
            // line 23
            echo "            <div class=\"pk-emptyDataTable\">";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreHome_ThereIsNoDataForThisReport")), "html", null, true);
            echo "</div>
        ";
        } else {
            // line 25
            echo "            <span class=\"loadingPiwik\">
                <img src=\"plugins/Morpheus/images/loading-blue.gif\" />
                ";
            // line 27
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_LoadingData")), "html", null, true);
            echo "...
            </span>
        ";
        }
        // line 30
        echo "    </div>
    <div class=\"dataTableFeatures\" style=\"padding-top:0;\">
        <div class=\"dataTableFooterIcons\">
            <div class=\"dataTableFooterWrap\" var=\"graphVerticalBar\">
                <img class=\"UserCountryMap-activeItem dataTableFooterActiveItem\" src=\"plugins/Morpheus/images/data_table_footer_active_item.png\" style=\"left: 25px;\" />

                <div class=\"tableIconsGroup\">
                    <span class=\"tableAllColumnsSwitch\">
                        <a class=\"UserCountryMap-btn-zoom tableIcon\" format=\"table\">
                            <img src=\"plugins/Morpheus/images/zoom-out.png\" title=\"Zoom to world\" />
                        </a>
                    </span>
                </div>
                <div class=\"tableIconsGroup UserCountryMap-view-mode-buttons\">
                    <span class=\"tableAllColumnsSwitch\">
                        <a var=\"tableAllColumns\" class=\"UserCountryMap-btn-region tableIcon activeIcon\" format=\"tableAllColumns\"
                           data-region=\"";
        // line 46
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountryMap_Regions")), "html", null, true);
        echo "\" data-country=\"";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountryMap_Countries")), "html", null, true);
        echo "\">
                            <img src=\"plugins/UserCountryMap/images/regions.png\" title=\"Show visitors per region/country\" />
                            <span style=\"margin:0;\">";
        // line 48
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountryMap_Countries")), "html", null, true);
        echo "</span>&nbsp;
                        </a>
                        <a var=\"tableGoals\" class=\"UserCountryMap-btn-city tableIcon inactiveIco\" format=\"tableGoals\">
                            <img src=\"plugins/UserCountryMap/images/cities.png\" title=\"Show visitors per city\" />
                            <span style=\"margin:0;\">";
        // line 52
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountryMap_Cities")), "html", null, true);
        echo "</span>&nbsp;
                        </a>
                    </span>
                </div>
            </div>

            <select class=\"userCountryMapSelectMetrics\" style=\"float:right;margin-right:0;margin-bottom:5px;max-width: 10em;font-size:10px;\">
                ";
        // line 59
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["metrics"]) ? $context["metrics"] : $this->getContext($context, "metrics")));
        foreach ($context['_seq'] as $context["_key"] => $context["metric"]) {
            // line 60
            echo "                    <option value=\"";
            echo twig_escape_filter($this->env, $this->getAttribute($context["metric"], 0, array(), "array"), "html", null, true);
            echo "\" ";
            if (($this->getAttribute($context["metric"], 0, array(), "array") == (isset($context["defaultMetric"]) ? $context["defaultMetric"] : $this->getContext($context, "defaultMetric")))) {
                echo "selected=\"selected\"";
            }
            echo "}>";
            echo twig_escape_filter($this->env, $this->getAttribute($context["metric"], 1, array(), "array"), "html", null, true);
            echo "</option>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['metric'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 62
        echo "            </select>
            <select class=\"userCountryMapSelectCountry\">
                <option value=\"world\">";
        // line 64
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountryMap_WorldWide")), "html", null, true);
        echo "</option>
                <option disabled=\"disabled\">––––––</option>
                ";
        // line 66
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["continents"]) ? $context["continents"] : $this->getContext($context, "continents")));
        foreach ($context['_seq'] as $context["code"] => $context["continent"]) {
            // line 67
            echo "                    <option value=\"";
            echo twig_escape_filter($this->env, $context["code"], "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, $context["continent"], "html", null, true);
            echo "</option>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['code'], $context['continent'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 69
        echo "                <option disabled=\"disabled\">––––––</option>
            </select>
        </div>
    </div>
</div>
</section>

";
        // line 76
        if ( !(isset($context["noData"]) ? $context["noData"] : $this->getContext($context, "noData"))) {
            // line 77
            echo "<!-- configure some piwik vars -->
<script type=\"text/javascript\">
    var visitorMap,
    config = JSON.parse('";
            // line 80
            echo twig_escape_filter($this->env, twig_escape_filter($this->env, (isset($context["config"]) ? $context["config"] : $this->getContext($context, "config")), "js"), "html", null, true);
            echo "');
    config._ = JSON.parse('";
            // line 81
            echo twig_escape_filter($this->env, twig_escape_filter($this->env, (isset($context["localeJSON"]) ? $context["localeJSON"] : $this->getContext($context, "localeJSON")), "js"), "html", null, true);
            echo "');
    config.reqParams = JSON.parse('";
            // line 82
            echo twig_escape_filter($this->env, twig_escape_filter($this->env, (isset($context["reqParamsJSON"]) ? $context["reqParamsJSON"] : $this->getContext($context, "reqParamsJSON")), "js"), "html", null, true);
            echo "');
    config.countryNames = JSON.parse('";
            // line 83
            echo twig_escape_filter($this->env, twig_escape_filter($this->env, twig_jsonencode_filter((isset($context["countriesByIso"]) ? $context["countriesByIso"] : $this->getContext($context, "countriesByIso"))), "js"), "html", null, true);
            echo "');

    \$('.UserCountryMap').addClass('dataTable');

    if (\$('#dashboardWidgetsArea').length) {
        // dashboard mode
        var \$widgetContent = \$('.UserCountryMap').parents('.widgetContent');

        \$widgetContent.on('widget:create',function (evt, widget) {
            visitorMap = new UserCountryMap.VisitorMap(config, widget);
        }).on('widget:maximise',function (evt) {
                    visitorMap.resize();
                }).on('widget:minimise',function (evt) {
                    visitorMap.resize();
                }).on('widget:destroy', function (evt) {
                    visitorMap.destroy();
                });
    } else {
        // stand-alone mode
        visitorMap = new UserCountryMap.VisitorMap(config);
    }

</script>
";
        }
    }

    public function getTemplateName()
    {
        return "@UserCountryMap/visitorMap.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  176 => 83,  172 => 82,  168 => 81,  164 => 80,  159 => 77,  157 => 76,  148 => 69,  137 => 67,  133 => 66,  128 => 64,  124 => 62,  109 => 60,  105 => 59,  95 => 52,  88 => 48,  81 => 46,  63 => 30,  57 => 27,  53 => 25,  47 => 23,  45 => 22,  36 => 16,  19 => 1,);
    }
}
