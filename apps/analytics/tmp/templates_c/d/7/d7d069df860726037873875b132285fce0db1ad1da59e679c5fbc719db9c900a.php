<?php

/* @UserCountry/adminIndex.twig */
class __TwigTemplate_d7d069df860726037873875b132285fce0db1ad1da59e679c5fbc719db9c900a extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("admin.twig", "@UserCountry/adminIndex.twig", 1);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "admin.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 3
        ob_start();
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_Geolocation")), "html", null, true);
        $context["title"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 1
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 5
    public function block_content($context, array $blocks = array())
    {
        // line 6
        $context["piwik"] = $this->loadTemplate("macros.twig", "@UserCountry/adminIndex.twig", 6);
        // line 7
        echo "
<h2 piwik-enriched-headline
    help-url=\"http://piwik.org/docs/geo-locate/\"
    id=\"location-providers\">";
        // line 10
        echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : $this->getContext($context, "title")), "html", null, true);
        echo "</h2>

<div style=\"width:900px;\">

    <p>";
        // line 14
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_GeolocationPageDesc")), "html", null, true);
        echo "</p>

    ";
        // line 16
        if ( !(isset($context["isThereWorkingProvider"]) ? $context["isThereWorkingProvider"] : $this->getContext($context, "isThereWorkingProvider"))) {
            // line 17
            echo "        <h3 style=\"margin-top:0;\">";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_HowToSetupGeoIP")), "html", null, true);
            echo "</h3>
        <p>";
            // line 18
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_HowToSetupGeoIPIntro")), "html", null, true);
            echo "</p>
        <ul style=\"list-style:disc;margin-left:2em;\">
            <li>";
            // line 20
            echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_HowToSetupGeoIP_Step1", "<a href=\"http://geolite.maxmind.com/download/geoip/database/GeoLiteCity.dat.gz\">", "</a>", "<a rel=\"noreferrer\"  target=\"_blank\" href=\"http://www.maxmind.com/?rId=piwik\">", "</a>"));
            echo "</li>
            <li>";
            // line 21
            echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_HowToSetupGeoIP_Step2", "'GeoLiteCity.dat'", "<strong>", "</strong>"));
            echo "</li>
            <li>";
            // line 22
            echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_HowToSetupGeoIP_Step3", "<strong>", "</strong>", "<span style=\"color:green\"><strong>", "</strong></span>"));
            echo "</li>
            <li>";
            // line 23
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_HowToSetupGeoIP_Step4")), "html", null, true);
            echo "</li>
        </ul>
        <p>&nbsp;</p>
    ";
        }
        // line 27
        echo "
    <table class=\"adminTable locationProviderTable\">
        <tr>
            <th>";
        // line 30
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_LocationProvider")), "html", null, true);
        echo "</th>
            <th>";
        // line 31
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Description")), "html", null, true);
        echo "</th>
            <th>";
        // line 32
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_InfoFor", (isset($context["thisIP"]) ? $context["thisIP"] : $this->getContext($context, "thisIP")))), "html", null, true);
        echo "</th>
        </tr>
        ";
        // line 34
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["locationProviders"]) ? $context["locationProviders"] : $this->getContext($context, "locationProviders")));
        foreach ($context['_seq'] as $context["id"] => $context["provider"]) {
            // line 35
            echo "        <tr>
            <td width=\"150\">
                <p>
                    <input class=\"location-provider\" name=\"location-provider\" value=\"";
            // line 38
            echo twig_escape_filter($this->env, $context["id"], "html", null, true);
            echo "\" type=\"radio\" ";
            if (((isset($context["currentProviderId"]) ? $context["currentProviderId"] : $this->getContext($context, "currentProviderId")) == $context["id"])) {
                echo "checked=\"checked\"";
            }
            // line 39
            echo "                           id=\"provider_input_";
            echo twig_escape_filter($this->env, $context["id"], "html", null, true);
            echo "\" ";
            if (($this->getAttribute($context["provider"], "status", array()) != 1)) {
                echo "disabled=\"disabled\"";
            }
            echo "/>
                    <label for=\"provider_input_";
            // line 40
            echo twig_escape_filter($this->env, $context["id"], "html", null, true);
            echo "\">";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array($this->getAttribute($context["provider"], "title", array()))), "html", null, true);
            echo "</label><br/>
                    <span class=\"loadingPiwik\" style=\"display:none;\"><img src=\"./plugins/Morpheus/images/loading-blue.gif\"/></span>
                    <span class=\"success\" ></span>
                </p>

                <p class=\"loc-provider-status\">
                    <strong><em>
                            ";
            // line 47
            if (($this->getAttribute($context["provider"], "status", array()) == 0)) {
                // line 48
                echo "                                <span class=\"is-not-installed\">";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_NotInstalled")), "html", null, true);
                echo "</span>
                            ";
            } elseif (($this->getAttribute(            // line 49
$context["provider"], "status", array()) == 1)) {
                // line 50
                echo "                                <span class=\"is-installed\">";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Installed")), "html", null, true);
                echo "</span>
                            ";
            } elseif (($this->getAttribute(            // line 51
$context["provider"], "status", array()) == 2)) {
                // line 52
                echo "                                <span class=\"is-broken\">";
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Broken")), "html", null, true);
                echo "</span>
                            ";
            }
            // line 54
            echo "                        </em></strong>
                </p>
            </td>
            <td>
                <p>";
            // line 58
            echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array($this->getAttribute($context["provider"], "description", array())));
            echo "</p>
                ";
            // line 59
            if ((($this->getAttribute($context["provider"], "status", array()) != 1) && $this->getAttribute($context["provider"], "install_docs", array(), "any", true, true))) {
                // line 60
                echo "                    <p>";
                echo $this->getAttribute($context["provider"], "install_docs", array());
                echo "</p>
                ";
            }
            // line 62
            echo "            </td>
            <td width=\"164\">
                ";
            // line 64
            if (($this->getAttribute($context["provider"], "status", array()) == 1)) {
                // line 65
                echo "                    <div class=\"form-help\">
                        ";
                // line 66
                if (((isset($context["thisIP"]) ? $context["thisIP"] : $this->getContext($context, "thisIP")) != "127.0.0.1")) {
                    // line 67
                    echo "                            ";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_CurrentLocationIntro")), "html", null, true);
                    echo ":
                            <div>
                                <br/>
                                <span class=\"loadingPiwik\" style=\"display:none;position:absolute;\">
                                    <img src=\"./plugins/Morpheus/images/loading-blue.gif\"/> ";
                    // line 71
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Loading")), "html", null, true);
                    echo "</span>
                                <span class=\"location\"><strong>";
                    // line 72
                    echo $this->getAttribute($context["provider"], "location", array());
                    echo "</strong></span>
                            </div>
                            <div class=\"text-right\">
                                <a href=\"#\" class=\"refresh-loc\" data-impl-id=\"";
                    // line 75
                    echo twig_escape_filter($this->env, $context["id"], "html", null, true);
                    echo "\"><em>";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Refresh")), "html", null, true);
                    echo "</em></a>
                            </div>
                        ";
                } else {
                    // line 78
                    echo "                            ";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_CannotLocalizeLocalIP", (isset($context["thisIP"]) ? $context["thisIP"] : $this->getContext($context, "thisIP")))), "html", null, true);
                    echo "
                        ";
                }
                // line 80
                echo "                    </div>
                ";
            }
            // line 82
            echo "                ";
            if (($this->getAttribute($context["provider"], "statusMessage", array(), "any", true, true) && $this->getAttribute($context["provider"], "statusMessage", array()))) {
                // line 83
                echo "                    <div class=\"form-help\">
                        ";
                // line 84
                if (($this->getAttribute($context["provider"], "status", array()) == 2)) {
                    echo "<strong>";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Error")), "html", null, true);
                    echo ":</strong> ";
                }
                echo $this->getAttribute($context["provider"], "statusMessage", array());
                echo "
                    </div>
                ";
            }
            // line 87
            echo "                ";
            if (($this->getAttribute($context["provider"], "extra_message", array(), "any", true, true) && $this->getAttribute($context["provider"], "extra_message", array()))) {
                // line 88
                echo "                    <div class=\"form-help\">
                        ";
                // line 89
                echo $this->getAttribute($context["provider"], "extra_message", array());
                echo "
                    </div>
                ";
            }
            // line 92
            echo "            </td>
            ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['id'], $context['provider'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 94
        echo "    </table>

</div>

";
        // line 98
        if ( !(isset($context["geoIPDatabasesInstalled"]) ? $context["geoIPDatabasesInstalled"] : $this->getContext($context, "geoIPDatabasesInstalled"))) {
            // line 99
            echo "    <h2 id=\"geoip-db-mangement\">";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_GeoIPDatabases")), "html", null, true);
            echo "</h2>
";
        } else {
            // line 101
            echo "    <h2 id=\"geoip-db-mangement\">";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_SetupAutomaticUpdatesOfGeoIP")), "html", null, true);
            echo "</h2>
";
        }
        // line 103
        echo "
";
        // line 104
        if ((isset($context["showGeoIPUpdateSection"]) ? $context["showGeoIPUpdateSection"] : $this->getContext($context, "showGeoIPUpdateSection"))) {
            // line 105
            echo "    <div id=\"manage-geoip-dbs\" style=\"width:900px;\" class=\"adminTable\">

    ";
            // line 107
            if ( !(isset($context["geoIPDatabasesInstalled"]) ? $context["geoIPDatabasesInstalled"] : $this->getContext($context, "geoIPDatabasesInstalled"))) {
                // line 108
                echo "        <div id=\"geoipdb-screen1\">
            <p>";
                // line 109
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_PiwikNotManagingGeoIPDBs")), "html", null, true);
                echo "</p>

            <div class=\"geoipdb-column-1\">
                <p>";
                // line 112
                echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_IWantToDownloadFreeGeoIP"));
                echo "</p>
                <input type=\"button\" class=\"submit\" value=\"";
                // line 113
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_GetStarted")), "html", null, true);
                echo "...\" id=\"start-download-free-geoip\"/>
            </div>
            <div class=\"geoipdb-column-2\">
                <p>";
                // line 116
                echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_IPurchasedGeoIPDBs", "<a href=\"http://www.maxmind.com/en/geolocation_landing?rId=piwik\">", "</a>"));
                echo "</p>
                <input type=\"button\" class=\"submit\" value=\"";
                // line 117
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_GetStarted")), "html", null, true);
                echo "...\" id=\"start-automatic-update-geoip\"/>
            </div>
        </div>
        <div id=\"geoipdb-screen2-download\" style=\"display:none;\">
            <p class='loadingPiwik'><img src='./plugins/Morpheus/images/loading-blue.gif'/>
            ";
                // line 122
                echo call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_DownloadingDb", (("<a href=\"" . (isset($context["geoLiteUrl"]) ? $context["geoLiteUrl"] : $this->getContext($context, "geoLiteUrl"))) . "\">GeoLiteCity.dat</a>")));
                echo "...</p>
\t        <div id=\"geoip-download-progress\"></div>
        </div>
    ";
            }
            // line 126
            echo "    ";
            $this->loadTemplate("@UserCountry/_updaterManage.twig", "@UserCountry/adminIndex.twig", 126)->display($context);
        } else {
            // line 128
            echo "<p style=\"width:900px;\" class=\"form-description\">";
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("UserCountry_CannotSetupGeoIPAutoUpdating")), "html", null, true);
            echo "</p>
";
        }
        // line 130
        echo "</div>

";
    }

    public function getTemplateName()
    {
        return "@UserCountry/adminIndex.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  342 => 130,  336 => 128,  332 => 126,  325 => 122,  317 => 117,  313 => 116,  307 => 113,  303 => 112,  297 => 109,  294 => 108,  292 => 107,  288 => 105,  286 => 104,  283 => 103,  277 => 101,  271 => 99,  269 => 98,  263 => 94,  256 => 92,  250 => 89,  247 => 88,  244 => 87,  233 => 84,  230 => 83,  227 => 82,  223 => 80,  217 => 78,  209 => 75,  203 => 72,  199 => 71,  191 => 67,  189 => 66,  186 => 65,  184 => 64,  180 => 62,  174 => 60,  172 => 59,  168 => 58,  162 => 54,  156 => 52,  154 => 51,  149 => 50,  147 => 49,  142 => 48,  140 => 47,  128 => 40,  119 => 39,  113 => 38,  108 => 35,  104 => 34,  99 => 32,  95 => 31,  91 => 30,  86 => 27,  79 => 23,  75 => 22,  71 => 21,  67 => 20,  62 => 18,  57 => 17,  55 => 16,  50 => 14,  43 => 10,  38 => 7,  36 => 6,  33 => 5,  29 => 1,  25 => 3,  11 => 1,);
    }
}
