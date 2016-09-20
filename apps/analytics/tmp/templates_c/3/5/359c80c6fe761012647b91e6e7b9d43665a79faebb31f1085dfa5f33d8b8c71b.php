<?php

/* @Morpheus/layout.twig */
class __TwigTemplate_359c80c6fe761012647b91e6e7b9d43665a79faebb31f1085dfa5f33d8b8c71b extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'head' => array($this, 'block_head'),
            'pageTitle' => array($this, 'block_pageTitle'),
            'pageDescription' => array($this, 'block_pageDescription'),
            'meta' => array($this, 'block_meta'),
            'body' => array($this, 'block_body'),
            'root' => array($this, 'block_root'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html id=\"ng-app\" ";
        // line 2
        if (array_key_exists("language", $context)) {
            echo "lang=\"";
            echo twig_escape_filter($this->env, (isset($context["language"]) ? $context["language"] : $this->getContext($context, "language")), "html", null, true);
            echo "\"";
        }
        echo " ng-app=\"piwikApp\">
    <head>
        ";
        // line 4
        $this->displayBlock('head', $context, $blocks);
        // line 31
        echo "    </head>
    <!--[if lt IE 9 ]>
    <body id=\"";
        // line 33
        echo twig_escape_filter($this->env, ((array_key_exists("bodyId", $context)) ? (_twig_default_filter((isset($context["bodyId"]) ? $context["bodyId"] : $this->getContext($context, "bodyId")), "")) : ("")), "html", null, true);
        echo "\" ng-app=\"app\" class=\"old-ie ";
        echo twig_escape_filter($this->env, ((array_key_exists("bodyClass", $context)) ? (_twig_default_filter((isset($context["bodyClass"]) ? $context["bodyClass"] : $this->getContext($context, "bodyClass")), "")) : ("")), "html", null, true);
        echo "\">
    <![endif]-->
    <!--[if (gte IE 9)|!(IE)]><!-->
    <body id=\"";
        // line 36
        echo twig_escape_filter($this->env, ((array_key_exists("bodyId", $context)) ? (_twig_default_filter((isset($context["bodyId"]) ? $context["bodyId"] : $this->getContext($context, "bodyId")), "")) : ("")), "html", null, true);
        echo "\" ng-app=\"app\" class=\"";
        echo twig_escape_filter($this->env, ((array_key_exists("bodyClass", $context)) ? (_twig_default_filter((isset($context["bodyClass"]) ? $context["bodyClass"] : $this->getContext($context, "bodyClass")), "")) : ("")), "html", null, true);
        echo "\">
    <!--<![endif]-->

    ";
        // line 39
        $this->displayBlock('body', $context, $blocks);
        // line 50
        echo "
        ";
        // line 51
        $this->loadTemplate("@CoreHome/_adblockDetect.twig", "@Morpheus/layout.twig", 51)->display($context);
        // line 52
        echo "    </body>
</html>
";
    }

    // line 4
    public function block_head($context, array $blocks = array())
    {
        // line 5
        echo "            <meta charset=\"utf-8\">
            <title>";
        // line 7
        $this->displayBlock('pageTitle', $context, $blocks);
        // line 12
        echo "</title>
            <meta http-equiv=\"X-UA-Compatible\" content=\"IE=EDGE,chrome=1\"/>
            <meta name=\"viewport\" content=\"initial-scale=1.0\"/>
            <meta name=\"generator\" content=\"Piwik - free/libre analytics platform\"/>
            <meta name=\"description\" content=\"";
        // line 16
        $this->displayBlock('pageDescription', $context, $blocks);
        echo "\"/>
            <meta name=\"apple-itunes-app\" content=\"app-id=737216887\" />
            <meta name=\"google-play-app\" content=\"app-id=org.piwik.mobile2\">
            ";
        // line 19
        $this->displayBlock('meta', $context, $blocks);
        // line 22
        echo "
            ";
        // line 23
        $this->loadTemplate("@CoreHome/_favicon.twig", "@Morpheus/layout.twig", 23)->display($context);
        // line 24
        echo "            ";
        $this->loadTemplate("_jsGlobalVariables.twig", "@Morpheus/layout.twig", 24)->display($context);
        // line 25
        echo "            ";
        $this->loadTemplate("_jsCssIncludes.twig", "@Morpheus/layout.twig", 25)->display($context);
        // line 26
        echo "
            <!--[if IE]>
            <link rel=\"stylesheet\" type=\"text/css\" href=\"plugins/Morpheus/stylesheets/ieonly.css\"/>
            <![endif]-->
        ";
    }

    // line 7
    public function block_pageTitle($context, array $blocks = array())
    {
        // line 8
        if (array_key_exists("title", $context)) {
            echo twig_escape_filter($this->env, (isset($context["title"]) ? $context["title"] : $this->getContext($context, "title")), "html", null, true);
            echo " - ";
        }
        // line 9
        if (array_key_exists("categoryTitle", $context)) {
            echo twig_escape_filter($this->env, (isset($context["categoryTitle"]) ? $context["categoryTitle"] : $this->getContext($context, "categoryTitle")), "html", null, true);
            echo " - ";
        }
        // line 10
        if ( !(isset($context["isCustomLogo"]) ? $context["isCustomLogo"] : $this->getContext($context, "isCustomLogo"))) {
            echo "Piwik";
        }
    }

    // line 16
    public function block_pageDescription($context, array $blocks = array())
    {
    }

    // line 19
    public function block_meta($context, array $blocks = array())
    {
        // line 20
        echo "                <meta name=\"robots\" content=\"noindex,nofollow\">
            ";
    }

    // line 39
    public function block_body($context, array $blocks = array())
    {
        // line 40
        echo "
        ";
        // line 41
        $this->loadTemplate("_iframeBuster.twig", "@Morpheus/layout.twig", 41)->display($context);
        // line 42
        echo "        ";
        $this->loadTemplate("@CoreHome/_javaScriptDisabled.twig", "@Morpheus/layout.twig", 42)->display($context);
        // line 43
        echo "
        <div id=\"root\">
            ";
        // line 45
        $this->displayBlock('root', $context, $blocks);
        // line 47
        echo "        </div>

    ";
    }

    // line 45
    public function block_root($context, array $blocks = array())
    {
        // line 46
        echo "            ";
    }

    public function getTemplateName()
    {
        return "@Morpheus/layout.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  171 => 46,  168 => 45,  162 => 47,  160 => 45,  156 => 43,  153 => 42,  151 => 41,  148 => 40,  145 => 39,  140 => 20,  137 => 19,  132 => 16,  126 => 10,  121 => 9,  116 => 8,  113 => 7,  105 => 26,  102 => 25,  99 => 24,  97 => 23,  94 => 22,  92 => 19,  86 => 16,  80 => 12,  78 => 7,  75 => 5,  72 => 4,  66 => 52,  64 => 51,  61 => 50,  59 => 39,  51 => 36,  43 => 33,  39 => 31,  37 => 4,  28 => 2,  25 => 1,);
    }
}
