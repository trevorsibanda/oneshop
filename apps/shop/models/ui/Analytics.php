<?php

class Analytics extends CI_Model
{
  
    private $_use_google_analytics = False;
    private $_use_default_analytics = True;
    private $_os_ga_key ='UA-69845445-1'; //google analytics key
    private $ga_key ='UA-69845445-1'; //google analytics key
    private $_analytics_js_url = OS_BASE_SITE . ':8080/analytics.js';
    
    
    public function __construct( )
    {
        parent::__construct();
        $this->load->helper('analytics_helper');
    }
    
    public function bootstrap( $subscription , $analytics_settings )
    {

        if( $subscription['type'] != 'free' && ! empty($analytics_settings['google_analytics_key']))
        {
            $this->_use_default_analytics = false;
            $this->_use_google_analytics = true;
            $this->ga_key = $analytics_settings['google_analytics_key'];
        }     
        else
        {
            $this->_use_default_analytics = true;
        }

    }
    
    public function using_google_analytics()
    {
        return !$this->_use_default_analytics;   
    }
    
    public function using_default_analytics( )
    {
        return $this->_use_default_analytics;
    }
    
    public function generate_code( )
    {
        if(  $this->using_google_analytics() )
            return "
    <!-- GOOGLE Analytics Code -->
    <script>
        var _gaq = _gaq || [];
      _gaq.push(['_setAccount', '{$this->ga_key}']); // your ID/profile

      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', '{$this->ga_key}', 'auto');
      ga('send', 'pageview');
      
    </script>";
        else
            return 
"
<!-- Piwik -->
<script type=\"text/javascript\">
  var _paq = _paq || [];
  _paq.push(['trackPageView']);
  _paq.push(['enableLinkTracking']);
  (function() {
    var u=\"//analytics.263shop.co.zw/\";
    _paq.push(['setTrackerUrl', u+'piwik.php']);
    _paq.push(['setSiteId', 1]);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.type='text/javascript'; g.async=true; g.defer=true; g.src=u+'piwik.js'; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript><p><img src=\"//analytics.263shop.co.zw/piwik.php?idsite=1\" style=\"border:0;\" alt=\"\" /></p></noscript>
<!-- End Piwik Code -->
<!-- GOOGLE Analytics Code -->
    <script>
        var _gaq = _gaq || [];
      _gaq.push(['_setAccount', '{$this->ga_key}']); // your ID/profile

      (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
      (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
      m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
      })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

      ga('create', '{$this->ga_key}', 'auto');
      ga('send', 'pageview');
      
    </script>
";
    }
    
    
    
}
