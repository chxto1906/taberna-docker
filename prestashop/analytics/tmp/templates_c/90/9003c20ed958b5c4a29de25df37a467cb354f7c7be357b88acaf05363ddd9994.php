<?php

/* @CoreAdminHome/getTrackingFailures.twig */
class __TwigTemplate_c2a57e99c32c98d54ea8aeb5769ac816db49c948e1aaea2b64d97c9c09486c46 extends Twig_Template
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
        echo "<div class=\"widgetBody system-check\">
    ";
        // line 2
        if ((($context["numFailures"] ?? $this->getContext($context, "numFailures")) == 0)) {
            // line 3
            echo "        <p class=\"system-success\"><span class=\"icon-ok\"></span> ";
            echo \Piwik\piwik_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_NoKnownFailures")), "html", null, true);
            echo "</p>
    ";
        } else {
            // line 5
            echo "        <p class=\"system-errors\">
            <span style=\"font-size: 16px;\"><span class=\"icon-error\"></span> ";
            // line 6
            echo \Piwik\piwik_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_NTrackingFailures", ($context["numFailures"] ?? $this->getContext($context, "numFailures")))), "html", null, true);
            echo "</span>
        </p>
        <p>
            <a href=\"";
            // line 9
            echo \Piwik\piwik_escape_filter($this->env, call_user_func_array($this->env->getFunction('linkTo')->getCallable(), array(array("module" => "CoreAdminHome", "action" => "trackingFailures"))), "html", null, true);
            echo "\"
            >";
            // line 10
            echo \Piwik\piwik_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("CoreAdminHome_ViewAllTrackingFailures")), "html", null, true);
            echo "</a>
        </p>
    ";
        }
        // line 13
        echo "</div>";
    }

    public function getTemplateName()
    {
        return "@CoreAdminHome/getTrackingFailures.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  49 => 13,  43 => 10,  39 => 9,  33 => 6,  30 => 5,  24 => 3,  22 => 2,  19 => 1,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("<div class=\"widgetBody system-check\">
    {% if numFailures == 0 %}
        <p class=\"system-success\"><span class=\"icon-ok\"></span> {{ 'CoreAdminHome_NoKnownFailures'|translate }}</p>
    {% else %}
        <p class=\"system-errors\">
            <span style=\"font-size: 16px;\"><span class=\"icon-error\"></span> {{ 'CoreAdminHome_NTrackingFailures'|translate(numFailures) }}</span>
        </p>
        <p>
            <a href=\"{{ linkTo({'module': 'CoreAdminHome', 'action': 'trackingFailures'}) }}\"
            >{{ 'CoreAdminHome_ViewAllTrackingFailures'|translate }}</a>
        </p>
    {% endif %}
</div>", "@CoreAdminHome/getTrackingFailures.twig", "/html/analytics/plugins/CoreAdminHome/templates/getTrackingFailures.twig");
    }
}
