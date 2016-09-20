<?php

/* @CoreHome/_menu.twig */
class __TwigTemplate_579a8531202239df011cf4ded4775f806af0a20061ea3dec695ca0f118be2347 extends Twig_Template
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
        // line 10
        echo "
";
        // line 24
        echo "
";
        // line 30
        echo "
";
    }

    // line 1
    public function getsubmenuItem($__name__ = null, $__url__ = null, $__anchorlink__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals(array(
            "name" => $__name__,
            "url" => $__url__,
            "anchorlink" => $__anchorlink__,
            "varargs" => $__varargs__,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 2
            echo "    ";
            if ((twig_slice($this->env, (isset($context["name"]) ? $context["name"] : $this->getContext($context, "name")), 0, 1) != "_")) {
                // line 3
                echo "        <li>
            <a class=\"item\" href=\"";
                // line 4
                if ((isset($context["anchorlink"]) ? $context["anchorlink"] : $this->getContext($context, "anchorlink"))) {
                    echo "#";
                } else {
                    echo "index.php?";
                }
                echo twig_escape_filter($this->env, twig_slice($this->env, call_user_func_array($this->env->getFilter('urlRewriteWithParameters')->getCallable(), array((isset($context["url"]) ? $context["url"] : $this->getContext($context, "url")))), 1), "html", null, true);
                echo "\">
                ";
                // line 5
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array((isset($context["name"]) ? $context["name"] : $this->getContext($context, "name")))), "html", null, true);
                echo "
            </a>
        </li>
    ";
            }
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    // line 11
    public function getgroupedItem($__name__ = null, $__group__ = null, $__anchorlink__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals(array(
            "name" => $__name__,
            "group" => $__group__,
            "anchorlink" => $__anchorlink__,
            "varargs" => $__varargs__,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 12
            echo "    <li>
        <div piwik-menudropdown show-search=\"true\" menu-title=\"";
            // line 13
            echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array((isset($context["name"]) ? $context["name"] : $this->getContext($context, "name")))), "html_attr");
            echo "\">
            ";
            // line 14
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["group"]) ? $context["group"] : $this->getContext($context, "group")), "getItems", array()));
            foreach ($context['_seq'] as $context["_key"] => $context["item"]) {
                // line 15
                echo "                <a class=\"item menuItem\"
                   href='";
                // line 16
                if ((isset($context["anchorlink"]) ? $context["anchorlink"] : $this->getContext($context, "anchorlink"))) {
                    echo "#?";
                } else {
                    echo "index.php?";
                }
                echo twig_escape_filter($this->env, twig_slice($this->env, call_user_func_array($this->env->getFilter('urlRewriteWithParameters')->getCallable(), array($this->getAttribute($context["item"], "url", array()))), 1), "html", null, true);
                echo "'
                   ";
                // line 17
                if ($this->getAttribute($context["item"], "tooltip", array())) {
                    echo "title=\"";
                    echo twig_escape_filter($this->env, $this->getAttribute($context["item"], "tooltip", array()), "html_attr");
                    echo "\"";
                }
                echo ">
                    ";
                // line 18
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array($this->getAttribute($context["item"], "name", array()))), "html", null, true);
                echo "
                </a>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['item'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 21
            echo "        </div>
    </li>
";
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    // line 25
    public function getgetId($__urlParameters__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals(array(
            "urlParameters" => $__urlParameters__,
            "varargs" => $__varargs__,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 26
            if (twig_test_iterable((isset($context["urlParameters"]) ? $context["urlParameters"] : $this->getContext($context, "urlParameters")))) {
                // line 27
                echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('urlRewriteWithParameters')->getCallable(), array((isset($context["urlParameters"]) ? $context["urlParameters"] : $this->getContext($context, "urlParameters")))), "html", null, true);
            }
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    // line 31
    public function getmenu($__menu__ = null, $__anchorlink__ = null, $__cssClass__ = null, ...$__varargs__)
    {
        $context = $this->env->mergeGlobals(array(
            "menu" => $__menu__,
            "anchorlink" => $__anchorlink__,
            "cssClass" => $__cssClass__,
            "varargs" => $__varargs__,
        ));

        $blocks = array();

        ob_start();
        try {
            // line 32
            echo "    <div id=\"secondNavBar\" class=\"";
            echo twig_escape_filter($this->env, (isset($context["cssClass"]) ? $context["cssClass"] : $this->getContext($context, "cssClass")), "html", null, true);
            echo "\">
        <div id=\"search\" ng-cloak>
            <div piwik-quick-access class=\"borderedControl\"></div>
        </div>
        <ul class=\"navbar\">
            ";
            // line 37
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["menu"]) ? $context["menu"] : $this->getContext($context, "menu")));
            foreach ($context['_seq'] as $context["level1"] => $context["level2"]) {
                // line 38
                echo "                ";
                $context["hasSubmenuItem"] = false;
                // line 39
                echo "                ";
                $context['_parent'] = (array) $context;
                $context['_seq'] = twig_ensure_traversable($context["level2"]);
                foreach ($context['_seq'] as $context["name"] => $context["urlParameters"]) {
                    // line 40
                    echo "                    ";
                    if (($this->getAttribute($context["urlParameters"], "_url", array(), "any", true, true) &&  !twig_test_iterable($this->getAttribute($context["urlParameters"], "_url", array())))) {
                        // line 41
                        echo "                        ";
                        $context["hasSubmenuItem"] = true;
                        // line 42
                        echo "                    ";
                    } elseif ((twig_slice($this->env, $context["name"], 0, 1) != "_")) {
                        // line 43
                        echo "                        ";
                        $context["hasSubmenuItem"] = true;
                        // line 44
                        echo "                    ";
                    }
                    // line 45
                    echo "                ";
                }
                $_parent = $context['_parent'];
                unset($context['_seq'], $context['_iterated'], $context['name'], $context['urlParameters'], $context['_parent'], $context['loop']);
                $context = array_intersect_key($context, $_parent) + $_parent;
                // line 46
                echo "
                ";
                // line 47
                if ((isset($context["hasSubmenuItem"]) ? $context["hasSubmenuItem"] : $this->getContext($context, "hasSubmenuItem"))) {
                    // line 48
                    echo "                    <li id=\"";
                    if (($this->getAttribute($context["level2"], "_url", array(), "any", true, true) &&  !twig_test_empty($this->getAttribute($context["level2"], "_url", array())))) {
                        echo $this->getAttribute($this, "getId", array(0 => $this->getAttribute($context["level2"], "_url", array())), "method");
                    }
                    echo "\" class=\"menuTab\">

                        <a class=\"item\" href=\"\">
                            <span class=\"menu-icon ";
                    // line 51
                    echo twig_escape_filter($this->env, (($this->getAttribute($context["level2"], "_icon", array(), "any", true, true)) ? (_twig_default_filter($this->getAttribute($context["level2"], "_icon", array()), "icon-arrow-right")) : ("icon-arrow-right")), "html", null, true);
                    echo "\"></span>";
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array($context["level1"])), "html", null, true);
                    echo "
                            <span class=\"hidden\">
                             ";
                    // line 53
                    echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreHome_Menu")), "html", null, true);
                    echo "
                           </span>
                        </a>
                        <ul>
                            ";
                    // line 57
                    $context['_parent'] = (array) $context;
                    $context['_seq'] = twig_ensure_traversable($context["level2"]);
                    foreach ($context['_seq'] as $context["name"] => $context["urlParameters"]) {
                        // line 58
                        echo "                                ";
                        if (($this->getAttribute($context["urlParameters"], "_url", array(), "any", true, true) &&  !twig_test_iterable($this->getAttribute($context["urlParameters"], "_url", array())))) {
                            // line 59
                            echo "                                    ";
                            echo $this->getAttribute($this, "groupedItem", array(0 => $context["name"], 1 => $this->getAttribute($context["urlParameters"], "_url", array()), 2 => (isset($context["anchorlink"]) ? $context["anchorlink"] : $this->getContext($context, "anchorlink"))), "method");
                            echo "
                                ";
                        } elseif ((twig_slice($this->env,                         // line 60
$context["name"], 0, 1) != "_")) {
                            // line 61
                            echo "                                    ";
                            echo $this->getAttribute($this, "submenuItem", array(0 => $context["name"], 1 => $this->getAttribute($context["urlParameters"], "_url", array()), 2 => (isset($context["anchorlink"]) ? $context["anchorlink"] : $this->getContext($context, "anchorlink"))), "method");
                            echo "
                                ";
                        }
                        // line 63
                        echo "                            ";
                    }
                    $_parent = $context['_parent'];
                    unset($context['_seq'], $context['_iterated'], $context['name'], $context['urlParameters'], $context['_parent'], $context['loop']);
                    $context = array_intersect_key($context, $_parent) + $_parent;
                    // line 64
                    echo "                        </ul>
                    </li>
                ";
                }
                // line 67
                echo "            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['level1'], $context['level2'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 68
            echo "        </ul>
    </div>
";
        } catch (Exception $e) {
            ob_end_clean();

            throw $e;
        }

        return ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
    }

    public function getTemplateName()
    {
        return "@CoreHome/_menu.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  289 => 68,  283 => 67,  278 => 64,  272 => 63,  266 => 61,  264 => 60,  259 => 59,  256 => 58,  252 => 57,  245 => 53,  238 => 51,  229 => 48,  227 => 47,  224 => 46,  218 => 45,  215 => 44,  212 => 43,  209 => 42,  206 => 41,  203 => 40,  198 => 39,  195 => 38,  191 => 37,  182 => 32,  168 => 31,  156 => 27,  154 => 26,  142 => 25,  129 => 21,  120 => 18,  112 => 17,  103 => 16,  100 => 15,  96 => 14,  92 => 13,  89 => 12,  75 => 11,  59 => 5,  50 => 4,  47 => 3,  44 => 2,  30 => 1,  25 => 30,  22 => 24,  19 => 10,);
    }
}
