<?php

/* @SegmentEditor/_segmentSelector.twig */
class __TwigTemplate_4d93c570b4205a728f5ac40556f3991fbe12cdb46c4293bc206440c4f75005e6 extends Twig_Template
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
        echo "<div class=\"SegmentEditor\" style=\"display:none;\">
    <div class=\"segmentationContainer listHtml\" title=\"";
        // line 2
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_ChooseASegment")), "html_attr");
        echo "\">
        <a class=\"title\"><span class=\"icon icon-segment\"></span><span class=\"segmentationTitle\"></span></a>
        <div class=\"dropdown dropdown-body\">
            <div class=\"segmentFilterContainer\">
                <input class=\"segmentFilter\" type=\"text\" value=\"";
        // line 6
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Search")), "html", null, true);
        echo "\"/>
                <span/>
            </div>
            <ul class=\"submenu\">
                <li>";
        // line 10
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_SelectSegmentOfVisitors")), "html", null, true);
        echo "
                    <div class=\"segmentList\">
                        <ul>
                        </ul>
                    </div>
                </li>
            </ul>

            ";
        // line 18
        if ((isset($context["authorizedToCreateSegments"]) ? $context["authorizedToCreateSegments"] : $this->getContext($context, "authorizedToCreateSegments"))) {
            // line 19
            echo "                <a class=\"add_new_segment btn\">";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_AddNewSegment")), "html", null, true);
            echo "</a>
            ";
        } else {
            // line 21
            echo "                <ul class=\"submenu\">
                <li>
                    ";
            // line 23
            if ((isset($context["isUserAnonymous"]) ? $context["isUserAnonymous"] : $this->getContext($context, "isUserAnonymous"))) {
                // line 24
                echo "                        <span class='youMustBeLoggedIn'>";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_YouMustBeLoggedInToCreateSegments")), "html", null, true);
                echo "
                        <br/>&rsaquo; <a href='index.php?module=";
                // line 25
                echo twig_escape_filter($this->env, (isset($context["loginModule"]) ? $context["loginModule"] : $this->getContext($context, "loginModule")), "html", null, true);
                echo "'>";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LogIn")), "html", null, true);
                echo "</a> </span>
                    ";
            }
            // line 27
            echo "                </li>
                </ul>
            ";
        }
        // line 30
        echo "        </div>
    </div>

    <div class=\"initial-state-rows\">";
        // line 33
        echo "<div class=\"segment-add-row initial\"><div>
        <span>+ ";
        // line 34
        echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_DragDropCondition"));
        echo "</span>
    </div></div>
    <div class=\"segment-and\">";
        // line 36
        echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_OperatorAND"));
        echo "</div>
    <div class=\"segment-add-row initial\"><div>
        <span>+ ";
        // line 38
        echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_DragDropCondition"));
        echo "</span>
    </div></div>
    </div>

    <div class=\"segment-row-inputs\">
        <div class=\"segment-input metricListBlock\">
            <select title=\"";
        // line 44
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_ChooseASegment")), "html", null, true);
        echo "\" class=\"metricList\">
                ";
        // line 45
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["segmentsByCategory"]) ? $context["segmentsByCategory"] : $this->getContext($context, "segmentsByCategory")));
        foreach ($context['_seq'] as $context["category"] => $context["segmentsInCategory"]) {
            // line 46
            echo "                <optgroup label=\"";
            echo twig_escape_filter($this->env, $context["category"], "html", null, true);
            echo "\">
                    ";
            // line 47
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($context["segmentsInCategory"]);
            foreach ($context['_seq'] as $context["_key"] => $context["segmentInCategory"]) {
                // line 48
                echo "                        <option data-type=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["segmentInCategory"], "type", array()), "html", null, true);
                echo "\" value=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["segmentInCategory"], "segment", array()), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["segmentInCategory"], "name", array()), "html", null, true);
                echo "</option>
                    ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['segmentInCategory'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 50
            echo "                </optgroup>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['category'], $context['segmentsInCategory'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 52
        echo "            </select>
        </div>
        <div class=\"segment-input metricMatchBlock\">
            <select title=\"";
        // line 55
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Matches")), "html", null, true);
        echo "\">
                <option value=\"==\">";
        // line 56
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_OperationEquals")), "html", null, true);
        echo "</option>
                <option value=\"!=\">";
        // line 57
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_OperationNotEquals")), "html", null, true);
        echo "</option>
                <option value=\"<=\">";
        // line 58
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_OperationAtMost")), "html", null, true);
        echo "</option>
                <option value=\">=\">";
        // line 59
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_OperationAtLeast")), "html", null, true);
        echo "</option>
                <option value=\"<\">";
        // line 60
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_OperationLessThan")), "html", null, true);
        echo "</option>
                <option value=\">\">";
        // line 61
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_OperationGreaterThan")), "html", null, true);
        echo "</option>
                <option value=\"=@\">";
        // line 62
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_OperationContains")), "html", null, true);
        echo "</option>
                <option value=\"!@\">";
        // line 63
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_OperationDoesNotContain")), "html", null, true);
        echo "</option>
            </select>
        </div>
        <div class=\"segment-input metricValueBlock\">
            <input type=\"text\" title=\"";
        // line 67
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Value")), "html", null, true);
        echo "\">
        </div>
        <div class=\"clear\"></div>
    </div>
    <div class=\"segment-rows\">
        <div class=\"segment-row\">
            <a href=\"#\" class=\"segment-close\"></a>
            <a href=\"#\" class=\"segment-loading\"></a>
        </div>
    </div>
    <div class=\"segment-or\">";
        // line 77
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_OperatorOR")), "html", null, true);
        echo "</div>
    <div class=\"segment-add-or\"><div>
            ";
        // line 79
        ob_start();
        echo "<span>";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_OperatorOR")), "html", null, true);
        echo "</span>";
        $context["orCondition"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 80
        echo "            <a href=\"#\"> + ";
        echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_AddANDorORCondition", (isset($context["orCondition"]) ? $context["orCondition"] : $this->getContext($context, "orCondition"))));
        echo " </a>
        </div>
    </div>
    <div class=\"segment-and\">";
        // line 83
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_OperatorAND")), "html", null, true);
        echo "</div>
    <div class=\"segment-add-row\"><div>
            ";
        // line 85
        ob_start();
        echo "<span>";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_OperatorAND")), "html", null, true);
        echo "</span>";
        $context["andCondition"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 86
        echo "            <a href=\"#\">+ ";
        echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_AddANDorORCondition", (isset($context["andCondition"]) ? $context["andCondition"] : $this->getContext($context, "andCondition"))));
        echo "</a>
        </div>
    </div>
    <div class=\"segment-element borderedControl expanded\">
        <div class=\"segment-nav\">
            <h4 class=\"visits\">
                <span class=\"icon-segment\"></span><span class=\"available_segments\"><strong>
                <select class=\"available_segments_select\"></select>
            </strong></span></h4>
            <div class=\"scrollable\">
            <ul>
                ";
        // line 97
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["segmentsByCategory"]) ? $context["segmentsByCategory"] : $this->getContext($context, "segmentsByCategory")));
        foreach ($context['_seq'] as $context["category"] => $context["segmentsInCategory"]) {
            // line 98
            echo "                <li data=\"visit\">
                    <a class=\"metric_category\" href=\"#\">";
            // line 99
            echo twig_escape_filter($this->env, $context["category"], "html", null, true);
            echo "</a>
                    <ul style=\"display:none;\">
                        ";
            // line 101
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($context["segmentsInCategory"]);
            foreach ($context['_seq'] as $context["_key"] => $context["segmentInCategory"]) {
                // line 102
                echo "                            <li data-metric=\"";
                echo twig_escape_filter($this->env, $this->getAttribute($context["segmentInCategory"], "segment", array()), "html", null, true);
                echo "\"><a class=\"ddmetric\" href=\"#\">";
                echo twig_escape_filter($this->env, $this->getAttribute($context["segmentInCategory"], "name", array()), "html", null, true);
                echo "</a></li>
                        ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['segmentInCategory'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 104
            echo "                    </ul>
                </li>
                ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['category'], $context['segmentsInCategory'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 107
        echo "            </ul>
            </div>
            <div class=\"custom_select_search\">
                <a href=\"#\"></a>
                <input type=\"text\" aria-haspopup=\"true\" aria-autocomplete=\"list\" role=\"textbox\" autocomplete=\"off\" class=\"inp ui-autocomplete-input segmentSearch\" value=\"";
        // line 111
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Search")), "html", null, true);
        echo "\" length=\"15\">
            </div>
        </div>
        <div class=\"segment-content\">
            <div class=\"segment-top\" ";
        // line 115
        if ( !(isset($context["isSuperUser"]) ? $context["isSuperUser"] : $this->getContext($context, "isSuperUser"))) {
            echo "style=\"display:none\"";
        }
        echo ">
                ";
        // line 116
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_ThisSegmentIsVisibleTo")), "html", null, true);
        echo " <span class=\"enable_all_users\"><strong>
                        <select class=\"enable_all_users_select\">
                            <option selected=\"1\" value=\"0\">";
        // line 118
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_VisibleToMe")), "html", null, true);
        echo "</option>
                            <option value=\"1\">";
        // line 119
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_VisibleToAllUsers")), "html", null, true);
        echo "</option>
                        </select>
                    </strong></span>

                ";
        // line 123
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_SegmentIsDisplayedForWebsite")), "html", null, true);
        echo "<span class=\"visible_to_website\"><strong>
                        <select class=\"visible_to_website_select\">
                            <option selected=\"\" value=\"";
        // line 125
        echo twig_escape_filter($this->env, (isset($context["idSite"]) ? $context["idSite"] : $this->getContext($context, "idSite")), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_SegmentDisplayedThisWebsiteOnly")), "html", null, true);
        echo "</option>
                            ";
        // line 126
        if ((isset($context["isAddingSegmentsForAllWebsitesEnabled"]) ? $context["isAddingSegmentsForAllWebsitesEnabled"] : $this->getContext($context, "isAddingSegmentsForAllWebsitesEnabled"))) {
            echo "<option value=\"0\">";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_SegmentDisplayedAllWebsites")), "html", null, true);
            echo "</option>";
        }
        // line 127
        echo "                        </select>
                    </strong></span>
                ";
        // line 129
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_And")), "html", null, true);
        echo " <span class=\"auto_archive\"><strong>
                        <select class=\"auto_archive_select\">
                            ";
        // line 131
        if ((isset($context["createRealTimeSegmentsIsEnabled"]) ? $context["createRealTimeSegmentsIsEnabled"] : $this->getContext($context, "createRealTimeSegmentsIsEnabled"))) {
            // line 132
            echo "                            <option selected=\"1\" value=\"0\">";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_AutoArchiveRealTime")), "html", null, true);
            echo " ";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_DefaultAppended")), "html", null, true);
            echo "</option>
                            ";
        }
        // line 134
        echo "                            <option ";
        if ( !(isset($context["createRealTimeSegmentsIsEnabled"]) ? $context["createRealTimeSegmentsIsEnabled"] : $this->getContext($context, "createRealTimeSegmentsIsEnabled"))) {
            echo "selected=\"1\"";
        }
        echo " value=\"1\">";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_AutoArchivePreProcessed")), "html", null, true);
        echo " </option>
                        </select>
                    </strong></span>

            </div>
            <h3>";
        // line 139
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Name")), "html", null, true);
        echo ": <span  class=\"segmentName\"></span> <a class=\"editSegmentName\" href=\"#\">";
        echo twig_escape_filter($this->env, twig_lower_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Edit"))), "html", null, true);
        echo "</a></h3>
        </div>
        <div class=\"segment-footer\">
            <div piwik-rate-feature title=\"Segment Editor\" style=\"display:inline-block;float: left;margin-top: 2px;margin-right: 10px;\"></div>
            <span class=\"segmentFooterNote\">The Segment Editor was <a class='crowdfundingLink' href='http://crowdfunding.piwik.org/custom-segments-editor/' rel='noreferrer' target='_blank'>crowdfunded</a> with the awesome support of 80 companies and Piwik users worldwide!</span>
            <a class=\"delete\" href=\"#\">";
        // line 144
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Delete")), "html", null, true);
        echo "</a>
            <a class=\"close\" href=\"#\">";
        // line 145
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Close")), "html", null, true);
        echo "</a>
            <button class=\"saveAndApply\">";
        // line 146
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_SaveAndApply")), "html", null, true);
        echo "</button>
        </div>
    </div>
</div>
<div class=\"segmentListContainer\">
<div class=\"ui-confirm segment-delete-confirm\">
    <h2>";
        // line 152
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_AreYouSureDeleteSegment")), "html", null, true);
        echo "</h2>
    <input role=\"yes\" type=\"button\" value=\"";
        // line 153
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Yes")), "html", null, true);
        echo "\"/>
    <input role=\"no\" type=\"button\" value=\"";
        // line 154
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_No")), "html", null, true);
        echo "\"/>
</div>
<div class=\"ui-confirm pleaseChangeBrowserAchivingDisabledSetting\">
    <h2>";
        // line 157
        echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_SegmentNotApplied", (isset($context["nameOfCurrentSegment"]) ? $context["nameOfCurrentSegment"] : $this->getContext($context, "nameOfCurrentSegment"))));
        echo "</h2>
    ";
        // line 158
        ob_start();
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_AutoArchivePreProcessed")), "html", null, true);
        $context["segmentSetting"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 159
        echo "    <input role=\"yes\" type=\"button\" value=\"";
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Ok")), "html", null, true);
        echo "\"/>
    <p class=\"description\">
        ";
        // line 161
        echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_SegmentNotAppliedMessage", (isset($context["nameOfCurrentSegment"]) ? $context["nameOfCurrentSegment"] : $this->getContext($context, "nameOfCurrentSegment"))));
        echo "
        <br/>
        ";
        // line 163
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_DataAvailableAtLaterDate")), "html", null, true);
        echo "
        ";
        // line 164
        if ((isset($context["isSuperUser"]) ? $context["isSuperUser"] : $this->getContext($context, "isSuperUser"))) {
            // line 165
            echo "            <br/> <br/> ";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("SegmentEditor_YouMayChangeSetting", "browser_archiving_disabled_enforce", (isset($context["segmentSetting"]) ? $context["segmentSetting"] : $this->getContext($context, "segmentSetting")))), "html", null, true);
            echo "
        ";
        }
        // line 167
        echo "    </p>
</div>
</div>";
    }

    public function getTemplateName()
    {
        return "@SegmentEditor/_segmentSelector.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  434 => 167,  428 => 165,  426 => 164,  422 => 163,  417 => 161,  411 => 159,  407 => 158,  403 => 157,  397 => 154,  393 => 153,  389 => 152,  380 => 146,  376 => 145,  372 => 144,  362 => 139,  349 => 134,  341 => 132,  339 => 131,  334 => 129,  330 => 127,  324 => 126,  318 => 125,  313 => 123,  306 => 119,  302 => 118,  297 => 116,  291 => 115,  284 => 111,  278 => 107,  270 => 104,  259 => 102,  255 => 101,  250 => 99,  247 => 98,  243 => 97,  228 => 86,  222 => 85,  217 => 83,  210 => 80,  204 => 79,  199 => 77,  186 => 67,  179 => 63,  175 => 62,  171 => 61,  167 => 60,  163 => 59,  159 => 58,  155 => 57,  151 => 56,  147 => 55,  142 => 52,  135 => 50,  122 => 48,  118 => 47,  113 => 46,  109 => 45,  105 => 44,  96 => 38,  91 => 36,  86 => 34,  83 => 33,  78 => 30,  73 => 27,  66 => 25,  61 => 24,  59 => 23,  55 => 21,  49 => 19,  47 => 18,  36 => 10,  29 => 6,  22 => 2,  19 => 1,);
    }
}
