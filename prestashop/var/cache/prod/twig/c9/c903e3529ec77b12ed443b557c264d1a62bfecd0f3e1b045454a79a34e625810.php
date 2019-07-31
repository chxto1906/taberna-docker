<?php

/* @PrestaShop/Admin/Common/Grid/Columns/Content/bulk_action.html.twig */
class __TwigTemplate_9ba0b48d112462c8afe39d55cdd6fab50a36a9d63933fdca773669adf9d844dd extends Twig_Template
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
        // line 25
        echo "
<div class=\"md-checkbox\">
  <label>
    <input type=\"checkbox\"
           title=\"";
        // line 29
        echo twig_escape_filter($this->env, $this->getAttribute(($context["column"] ?? null), "name", array()), "html", null, true);
        echo "\"
           class=\"js-bulk-action-checkbox\"
           name=\"";
        // line 31
        echo twig_escape_filter($this->env, (($this->getAttribute(($context["grid"] ?? null), "id", array()) . "_") . $this->getAttribute(($context["column"] ?? null), "id", array())), "html", null, true);
        echo "[]\"
           value=\"";
        // line 32
        echo twig_escape_filter($this->env, $this->getAttribute(($context["record"] ?? null), $this->getAttribute($this->getAttribute(($context["column"] ?? null), "options", array()), "bulk_field", array()), array(), "array"), "html", null, true);
        echo "\"
    >
    <i class=\"md-checkbox-control\"></i>
  </label>
</div>
";
    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Common/Grid/Columns/Content/bulk_action.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  34 => 32,  30 => 31,  25 => 29,  19 => 25,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@PrestaShop/Admin/Common/Grid/Columns/Content/bulk_action.html.twig", "/html/src/PrestaShopBundle/Resources/views/Admin/Common/Grid/Columns/Content/bulk_action.html.twig");
    }
}
