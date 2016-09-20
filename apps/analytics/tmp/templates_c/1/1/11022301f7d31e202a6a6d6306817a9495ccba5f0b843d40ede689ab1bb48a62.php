<?php

/* @CoreHome/getPromoVideo.twig */
class __TwigTemplate_11022301f7d31e202a6a6d6306817a9495ccba5f0b843d40ede689ab1bb48a62 extends Twig_Template
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
        echo "<div id=\"piwik-promo\">
    <div id=\"piwik-promo-video\">
        <div id=\"piwik-promo-thumbnail\">
            <img src=\"plugins/Morpheus/images/video_play.png\"/>
        </div>

        <div id=\"piwik-promo-embed\" style=\"display:none;\">
        </div>
    </div>

    <a id=\"piwik-promo-videos-link\" href=\"http://piwik.org/blog/2012/12/piwik-how-to-videos/\" rel=\"noreferrer\"  target=\"_blank\">
        ";
        // line 12
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreHome_ViewAllPiwikVideoTutorials")), "html", null, true);
        echo "
    </a>

    <div id=\"piwik-promo-share\">
        <span>";
        // line 16
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreHome_ShareThis")), "html", null, true);
        echo ":</span>

        ";
        // line 19
        echo "        <a href=\"http://www.facebook.com/sharer.php?u=";
        echo twig_escape_filter($this->env, twig_urlencode_filter((isset($context["promoVideoUrl"]) ? $context["promoVideoUrl"] : $this->getContext($context, "promoVideoUrl"))), "html", null, true);
        echo "\" rel=\"noreferrer\"  target=\"_blank\">
            <img src=\"plugins/Referrers/images/socials/facebook.com.png\" />
        </a>

        ";
        // line 24
        echo "        <a href=\"http://twitter.com/share?text=";
        echo twig_escape_filter($this->env, twig_urlencode_filter((isset($context["shareText"]) ? $context["shareText"] : $this->getContext($context, "shareText"))), "html", null, true);
        echo "&url=";
        echo twig_escape_filter($this->env, twig_urlencode_filter((isset($context["promoVideoUrl"]) ? $context["promoVideoUrl"] : $this->getContext($context, "promoVideoUrl"))), "html", null, true);
        echo "\" rel=\"noreferrer\"  target=\"_blank\">
            <img src=\"plugins/Referrers/images/socials/twitter.com.png\" />
        </a>

        ";
        // line 29
        echo "        <a href=\"mailto:?body=";
        echo twig_escape_filter($this->env, twig_urlencode_filter((isset($context["shareTextLong"]) ? $context["shareTextLong"] : $this->getContext($context, "shareTextLong")), true), "html", null, true);
        echo "&subject=";
        echo twig_escape_filter($this->env, twig_urlencode_filter((isset($context["shareText"]) ? $context["shareText"] : $this->getContext($context, "shareText")), true), "html", null, true);
        echo "\" target=\"_blank\">
            <img src=\"plugins/Morpheus/images/email.png\" />
        </a>
    </div>

    <div style=\"clear:both;\"></div>

    <div id=\"piwik-widget-footer\" style=\"color:#666;\">";
        // line 36
        echo twig_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreHome_CloseWidgetDirections")), "html", null, true);
        echo "</div>
</div>

<script type=\"application/javascript\">
    \$(function () {
        \$('#piwik-promo-thumbnail').click(function () {
            var promoEmbed = \$('#piwik-promo-embed'),
                    widgetWidth = \$(this).closest('.widgetContent').width(),
                    height = (266 * widgetWidth) / 421,
                    embedHtml = '<iframe width=\"100%\" height=\"' + height + '\" src=\"https://www.youtube-nocookie.com/embed/OslfF_EH81g?autoplay=1&vq=hd720&wmode=transparent\" frameborder=\"0\" wmode=\"Opaque\"></iframe>';

            \$(this).hide();
            promoEmbed.height(height).html(embedHtml);
            promoEmbed.show();
        });
    });
</script>
";
    }

    public function getTemplateName()
    {
        return "@CoreHome/getPromoVideo.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  75 => 36,  62 => 29,  52 => 24,  44 => 19,  39 => 16,  32 => 12,  19 => 1,);
    }
}
