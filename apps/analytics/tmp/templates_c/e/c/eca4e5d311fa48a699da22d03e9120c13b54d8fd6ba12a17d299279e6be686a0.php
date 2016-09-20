<?php

/* @VisitsSummary/_sparklines.twig */
class __TwigTemplate_eca4e5d311fa48a699da22d03e9120c13b54d8fd6ba12a17d299279e6be686a0 extends Twig_Template
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
        if ( !(isset($context["isWidget"]) ? $context["isWidget"] : $this->getContext($context, "isWidget"))) {
            // line 2
            echo "<div class=\"row\">
    <div class=\"col-md-6\">
";
        }
        // line 5
        echo "
        <div class=\"sparkline\">
            ";
        // line 7
        echo call_user_func_array($this->env->getFunction('sparkline')->getCallable(), array((isset($context["urlSparklineNbVisits"]) ? $context["urlSparklineNbVisits"] : $this->getContext($context, "urlSparklineNbVisits"))));
        echo "
            ";
        // line 8
        echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_NVisits", (("<strong>" . call_user_func_array($this->env->getFilter('number')->getCallable(), array((isset($context["nbVisits"]) ? $context["nbVisits"] : $this->getContext($context, "nbVisits"))))) . "</strong>")));
        if ((isset($context["displayUniqueVisitors"]) ? $context["displayUniqueVisitors"] : $this->getContext($context, "displayUniqueVisitors"))) {
            echo ",
                ";
            // line 9
            echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("VisitsSummary_NbUniqueVisitors", (("<strong>" . call_user_func_array($this->env->getFilter('number')->getCallable(), array((isset($context["nbUniqVisitors"]) ? $context["nbUniqVisitors"] : $this->getContext($context, "nbUniqVisitors"))))) . "</strong>")));
        }
        // line 10
        echo "        </div>
        ";
        // line 11
        if (((isset($context["nbUsers"]) ? $context["nbUsers"] : $this->getContext($context, "nbUsers")) > 0)) {
            // line 12
            echo "            ";
            // line 13
            echo "            <div class=\"sparkline\">
                ";
            // line 14
            echo call_user_func_array($this->env->getFunction('sparkline')->getCallable(), array((isset($context["urlSparklineNbUsers"]) ? $context["urlSparklineNbUsers"] : $this->getContext($context, "urlSparklineNbUsers"))));
            echo "
                ";
            // line 15
            echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_NUsers", (("<strong>" . call_user_func_array($this->env->getFilter('number')->getCallable(), array((isset($context["nbUsers"]) ? $context["nbUsers"] : $this->getContext($context, "nbUsers"))))) . "</strong>")));
            echo "
            </div>
        ";
        }
        // line 18
        echo "        <div class=\"sparkline\">
            ";
        // line 19
        echo call_user_func_array($this->env->getFunction('sparkline')->getCallable(), array((isset($context["urlSparklineAvgVisitDuration"]) ? $context["urlSparklineAvgVisitDuration"] : $this->getContext($context, "urlSparklineAvgVisitDuration"))));
        echo "
            ";
        // line 20
        $context["averageVisitDuration"] = call_user_func_array($this->env->getFilter('sumtime')->getCallable(), array((isset($context["averageVisitDuration"]) ? $context["averageVisitDuration"] : $this->getContext($context, "averageVisitDuration"))));
        // line 21
        echo "            ";
        echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("VisitsSummary_AverageVisitDuration", (("<strong>" . (isset($context["averageVisitDuration"]) ? $context["averageVisitDuration"] : $this->getContext($context, "averageVisitDuration"))) . "</strong>")));
        echo "
        </div>
        <div class=\"sparkline\">
            ";
        // line 24
        echo call_user_func_array($this->env->getFunction('sparkline')->getCallable(), array((isset($context["urlSparklineBounceRate"]) ? $context["urlSparklineBounceRate"] : $this->getContext($context, "urlSparklineBounceRate"))));
        echo "
            ";
        // line 25
        echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("VisitsSummary_NbVisitsBounced", (("<strong>" . call_user_func_array($this->env->getFilter('percent')->getCallable(), array((isset($context["bounceRate"]) ? $context["bounceRate"] : $this->getContext($context, "bounceRate"))))) . "</strong>")));
        echo "
        </div>
        <div class=\"sparkline\">
            ";
        // line 28
        echo call_user_func_array($this->env->getFunction('sparkline')->getCallable(), array((isset($context["urlSparklineActionsPerVisit"]) ? $context["urlSparklineActionsPerVisit"] : $this->getContext($context, "urlSparklineActionsPerVisit"))));
        echo "
            ";
        // line 29
        echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("VisitsSummary_NbActionsPerVisit", (("<strong>" . call_user_func_array($this->env->getFilter('number')->getCallable(), array((isset($context["nbActionsPerVisit"]) ? $context["nbActionsPerVisit"] : $this->getContext($context, "nbActionsPerVisit")), 1))) . "</strong>")));
        echo "
        </div>
        ";
        // line 31
        if (((array_key_exists("showActionsPluginReports", $context)) ? (_twig_default_filter((isset($context["showActionsPluginReports"]) ? $context["showActionsPluginReports"] : $this->getContext($context, "showActionsPluginReports")), false)) : (false))) {
            // line 32
            echo "        <div class=\"sparkline\">
            ";
            // line 33
            echo call_user_func_array($this->env->getFunction('sparkline')->getCallable(), array((isset($context["urlSparklineAvgGenerationTime"]) ? $context["urlSparklineAvgGenerationTime"] : $this->getContext($context, "urlSparklineAvgGenerationTime"))));
            echo "
            ";
            // line 34
            $context["averageGenerationTime"] = call_user_func_array($this->env->getFilter('sumtime')->getCallable(), array((isset($context["averageGenerationTime"]) ? $context["averageGenerationTime"] : $this->getContext($context, "averageGenerationTime"))));
            // line 35
            echo "            ";
            echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("VisitsSummary_AverageGenerationTime", (("<strong>" . (isset($context["averageGenerationTime"]) ? $context["averageGenerationTime"] : $this->getContext($context, "averageGenerationTime"))) . "</strong>")));
            echo "
        </div>
        ";
        }
        // line 38
        echo "
";
        // line 39
        if ( !(isset($context["isWidget"]) ? $context["isWidget"] : $this->getContext($context, "isWidget"))) {
            // line 40
            echo "    </div>
    <div class=\"col-md-6\">
";
        }
        // line 43
        echo "
        ";
        // line 44
        if (((array_key_exists("showActionsPluginReports", $context)) ? (_twig_default_filter((isset($context["showActionsPluginReports"]) ? $context["showActionsPluginReports"] : $this->getContext($context, "showActionsPluginReports")), false)) : (false))) {
            // line 45
            echo "            ";
            if ((isset($context["showOnlyActions"]) ? $context["showOnlyActions"] : $this->getContext($context, "showOnlyActions"))) {
                // line 46
                echo "                <div class=\"sparkline\">
                    ";
                // line 47
                echo call_user_func_array($this->env->getFunction('sparkline')->getCallable(), array((isset($context["urlSparklineNbActions"]) ? $context["urlSparklineNbActions"] : $this->getContext($context, "urlSparklineNbActions"))));
                echo "
                    ";
                // line 48
                echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("VisitsSummary_NbActionsDescription", (("<strong>" . call_user_func_array($this->env->getFilter('number')->getCallable(), array((isset($context["nbActions"]) ? $context["nbActions"] : $this->getContext($context, "nbActions"))))) . "</strong>")));
                echo "
                </div>
            ";
            } else {
                // line 51
                echo "                <div class=\"sparkline\">
                    ";
                // line 52
                echo call_user_func_array($this->env->getFunction('sparkline')->getCallable(), array((isset($context["urlSparklineNbPageviews"]) ? $context["urlSparklineNbPageviews"] : $this->getContext($context, "urlSparklineNbPageviews"))));
                echo "
                    ";
                // line 53
                echo trim(call_user_func_array($this->env->getFilter('translate')->getCallable(), array("VisitsSummary_NbPageviewsDescription", (("<strong>" . call_user_func_array($this->env->getFilter('number')->getCallable(), array((isset($context["nbPageviews"]) ? $context["nbPageviews"] : $this->getContext($context, "nbPageviews"))))) . "</strong>"))));
                echo ",
                    ";
                // line 54
                echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("VisitsSummary_NbUniquePageviewsDescription", (("<strong>" . call_user_func_array($this->env->getFilter('number')->getCallable(), array((isset($context["nbUniquePageviews"]) ? $context["nbUniquePageviews"] : $this->getContext($context, "nbUniquePageviews"))))) . "</strong>")));
                echo "
                </div>
                ";
                // line 56
                if ((isset($context["displaySiteSearch"]) ? $context["displaySiteSearch"] : $this->getContext($context, "displaySiteSearch"))) {
                    // line 57
                    echo "                    <div class=\"sparkline\">
                        ";
                    // line 58
                    echo call_user_func_array($this->env->getFunction('sparkline')->getCallable(), array((isset($context["urlSparklineNbSearches"]) ? $context["urlSparklineNbSearches"] : $this->getContext($context, "urlSparklineNbSearches"))));
                    echo "
                        ";
                    // line 59
                    echo trim(call_user_func_array($this->env->getFilter('translate')->getCallable(), array("VisitsSummary_NbSearchesDescription", (("<strong>" . call_user_func_array($this->env->getFilter('number')->getCallable(), array((isset($context["nbSearches"]) ? $context["nbSearches"] : $this->getContext($context, "nbSearches"))))) . "</strong>"))));
                    echo ",
                        ";
                    // line 60
                    echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("VisitsSummary_NbKeywordsDescription", (("<strong>" . call_user_func_array($this->env->getFilter('number')->getCallable(), array((isset($context["nbKeywords"]) ? $context["nbKeywords"] : $this->getContext($context, "nbKeywords"))))) . "</strong>")));
                    echo "
                    </div>
                ";
                }
                // line 63
                echo "                <div class=\"sparkline\">
                        ";
                // line 64
                echo call_user_func_array($this->env->getFunction('sparkline')->getCallable(), array((isset($context["urlSparklineNbDownloads"]) ? $context["urlSparklineNbDownloads"] : $this->getContext($context, "urlSparklineNbDownloads"))));
                echo "
                        ";
                // line 65
                echo trim(call_user_func_array($this->env->getFilter('translate')->getCallable(), array("VisitsSummary_NbDownloadsDescription", (("<strong>" . call_user_func_array($this->env->getFilter('number')->getCallable(), array((isset($context["nbDownloads"]) ? $context["nbDownloads"] : $this->getContext($context, "nbDownloads"))))) . "</strong>"))));
                echo ",
                        ";
                // line 66
                echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("VisitsSummary_NbUniqueDownloadsDescription", (("<strong>" . call_user_func_array($this->env->getFilter('number')->getCallable(), array((isset($context["nbUniqueDownloads"]) ? $context["nbUniqueDownloads"] : $this->getContext($context, "nbUniqueDownloads"))))) . "</strong>")));
                echo "
                </div>
                <div class=\"sparkline\">
                        ";
                // line 69
                echo call_user_func_array($this->env->getFunction('sparkline')->getCallable(), array((isset($context["urlSparklineNbOutlinks"]) ? $context["urlSparklineNbOutlinks"] : $this->getContext($context, "urlSparklineNbOutlinks"))));
                echo "
                        ";
                // line 70
                echo trim(call_user_func_array($this->env->getFilter('translate')->getCallable(), array("VisitsSummary_NbOutlinksDescription", (("<strong>" . call_user_func_array($this->env->getFilter('number')->getCallable(), array((isset($context["nbOutlinks"]) ? $context["nbOutlinks"] : $this->getContext($context, "nbOutlinks"))))) . "</strong>"))));
                echo ",
                        ";
                // line 71
                echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("VisitsSummary_NbUniqueOutlinksDescription", (("<strong>" . call_user_func_array($this->env->getFilter('number')->getCallable(), array((isset($context["nbUniqueOutlinks"]) ? $context["nbUniqueOutlinks"] : $this->getContext($context, "nbUniqueOutlinks"))))) . "</strong>")));
                echo "
                </div>
                ";
            }
            // line 74
            echo "        ";
        }
        // line 75
        echo "        <div class=\"sparkline\">
                ";
        // line 76
        echo call_user_func_array($this->env->getFunction('sparkline')->getCallable(), array((isset($context["urlSparklineMaxActions"]) ? $context["urlSparklineMaxActions"] : $this->getContext($context, "urlSparklineMaxActions"))));
        echo "
                ";
        // line 77
        echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("VisitsSummary_MaxNbActions", (("<strong>" . call_user_func_array($this->env->getFilter('number')->getCallable(), array((isset($context["maxActions"]) ? $context["maxActions"] : $this->getContext($context, "maxActions"))))) . "</strong>")));
        echo "
        </div>

        ";
        // line 80
        echo call_user_func_array($this->env->getFunction('postEvent')->getCallable(), array("Template.VisitsSummaryOverviewSparklines"));
        echo "

";
        // line 82
        if ( !(isset($context["isWidget"]) ? $context["isWidget"] : $this->getContext($context, "isWidget"))) {
            // line 83
            echo "    </div>
</div>
";
        }
        // line 86
        echo "
";
        // line 87
        $this->loadTemplate("_sparklineFooter.twig", "@VisitsSummary/_sparklines.twig", 87)->display($context);
        // line 88
        echo "
";
    }

    public function getTemplateName()
    {
        return "@VisitsSummary/_sparklines.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  245 => 88,  243 => 87,  240 => 86,  235 => 83,  233 => 82,  228 => 80,  222 => 77,  218 => 76,  215 => 75,  212 => 74,  206 => 71,  202 => 70,  198 => 69,  192 => 66,  188 => 65,  184 => 64,  181 => 63,  175 => 60,  171 => 59,  167 => 58,  164 => 57,  162 => 56,  157 => 54,  153 => 53,  149 => 52,  146 => 51,  140 => 48,  136 => 47,  133 => 46,  130 => 45,  128 => 44,  125 => 43,  120 => 40,  118 => 39,  115 => 38,  108 => 35,  106 => 34,  102 => 33,  99 => 32,  97 => 31,  92 => 29,  88 => 28,  82 => 25,  78 => 24,  71 => 21,  69 => 20,  65 => 19,  62 => 18,  56 => 15,  52 => 14,  49 => 13,  47 => 12,  45 => 11,  42 => 10,  39 => 9,  34 => 8,  30 => 7,  26 => 5,  21 => 2,  19 => 1,);
    }
}
