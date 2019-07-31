<?php

/* @Login/loginLayout.twig */
class __TwigTemplate_eafe5548c1d36adbb2c5beaec992d27072dfbc376c632c5200db9fe6edc69c2e extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 1
        $this->parent = $this->loadTemplate("@Morpheus/layout.twig", "@Login/loginLayout.twig", 1);
        $this->blocks = array(
            'meta' => array($this, 'block_meta'),
            'head' => array($this, 'block_head'),
            'pageDescription' => array($this, 'block_pageDescription'),
            'body' => array($this, 'block_body'),
            'loginContent' => array($this, 'block_loginContent'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "@Morpheus/layout.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 13
        ob_start();
        echo \Piwik\piwik_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LogIn")), "html", null, true);
        $context["title"] = ('' === $tmp = ob_get_clean()) ? '' : new Twig_Markup($tmp, $this->env->getCharset());
        // line 17
        $context["bodyId"] = "loginPage";
        // line 1
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 3
    public function block_meta($context, array $blocks = array())
    {
        // line 4
        echo "    <meta name=\"robots\" content=\"noindex,nofollow\">
";
    }

    // line 7
    public function block_head($context, array $blocks = array())
    {
        // line 8
        echo "    ";
        $this->displayParentBlock("head", $context, $blocks);
        echo "

    <script type=\"text/javascript\" src=\"libs/bower_components/jquery-placeholder/jquery.placeholder.js\"></script>
";
    }

    // line 15
    public function block_pageDescription($context, array $blocks = array())
    {
        echo \Piwik\piwik_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_OpenSourceWebAnalytics")), "html", null, true);
    }

    // line 19
    public function block_body($context, array $blocks = array())
    {
        // line 20
        echo "
    ";
        // line 21
        echo call_user_func_array($this->env->getFunction('postEvent')->getCallable(), array("Template.beforeTopBar", "login"));
        echo "
    ";
        // line 22
        echo call_user_func_array($this->env->getFunction('postEvent')->getCallable(), array("Template.beforeContent", "login"));
        echo "

    ";
        // line 24
        $this->loadTemplate("_iframeBuster.twig", "@Login/loginLayout.twig", 24)->display($context);
        // line 25
        echo "
    <div id=\"notificationContainer\">
    </div>
    <nav>
        <div class=\"nav-wrapper\">
            ";
        // line 30
        $this->loadTemplate("@CoreHome/_logo.twig", "@Login/loginLayout.twig", 30)->display(array_merge($context, array("logoLink" => "https://matomo.org", "centeredLogo" => true, "useLargeLogo" => false)));
        // line 31
        echo "        </div>
    </nav>

    <section class=\"loginSection row\">
        <div class=\"col s12 m6 push-m3 l4 push-l4\">

        ";
        // line 38
        echo "        ";
        if ((((isset($context["isValidHost"]) || array_key_exists("isValidHost", $context)) && (isset($context["invalidHostMessage"]) || array_key_exists("invalidHostMessage", $context))) && (($context["isValidHost"] ?? $this->getContext($context, "isValidHost")) == false))) {
            // line 39
            echo "            ";
            $this->loadTemplate("@CoreHome/_warningInvalidHost.twig", "@Login/loginLayout.twig", 39)->display($context);
            // line 40
            echo "        ";
        } else {
            // line 41
            echo "            ";
            $this->displayBlock('loginContent', $context, $blocks);
            // line 43
            echo "        ";
        }
        // line 44
        echo "
        </div>
    </section>

";
    }

    // line 41
    public function block_loginContent($context, array $blocks = array())
    {
        // line 42
        echo "            ";
    }

    public function getTemplateName()
    {
        return "@Login/loginLayout.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  125 => 42,  122 => 41,  114 => 44,  111 => 43,  108 => 41,  105 => 40,  102 => 39,  99 => 38,  91 => 31,  89 => 30,  82 => 25,  80 => 24,  75 => 22,  71 => 21,  68 => 20,  65 => 19,  59 => 15,  50 => 8,  47 => 7,  42 => 4,  39 => 3,  35 => 1,  33 => 17,  29 => 13,  11 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("{% extends '@Morpheus/layout.twig' %}

{% block meta %}
    <meta name=\"robots\" content=\"noindex,nofollow\">
{% endblock %}

{% block head %}
    {{ parent() }}

    <script type=\"text/javascript\" src=\"libs/bower_components/jquery-placeholder/jquery.placeholder.js\"></script>
{% endblock %}

{% set title %}{{ 'Login_LogIn'|translate }}{% endset %}

{% block pageDescription %}{{ 'General_OpenSourceWebAnalytics'|translate }}{% endblock %}

{% set bodyId = 'loginPage' %}

{% block body %}

    {{ postEvent(\"Template.beforeTopBar\", \"login\") }}
    {{ postEvent(\"Template.beforeContent\", \"login\") }}

    {% include \"_iframeBuster.twig\" %}

    <div id=\"notificationContainer\">
    </div>
    <nav>
        <div class=\"nav-wrapper\">
            {% include \"@CoreHome/_logo.twig\" with { 'logoLink': 'https://matomo.org', 'centeredLogo': true, 'useLargeLogo': false } %}
        </div>
    </nav>

    <section class=\"loginSection row\">
        <div class=\"col s12 m6 push-m3 l4 push-l4\">

        {# untrusted host warning #}
        {% if (isValidHost is defined and invalidHostMessage is defined and isValidHost == false) %}
            {% include '@CoreHome/_warningInvalidHost.twig' %}
        {% else %}
            {% block loginContent %}
            {% endblock %}
        {% endif %}

        </div>
    </section>

{% endblock %}
", "@Login/loginLayout.twig", "/html/analytics/plugins/Login/templates/loginLayout.twig");
    }
}
