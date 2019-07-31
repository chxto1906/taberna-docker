<?php

/* @PrestaShop/Admin/Common/Grid/Actions/Grid/simple.html.twig */
class __TwigTemplate_ba38c727de7033c6e0d3dde4cb1ee5e7d412777ed960d972bfb8be1e2d425748 extends Twig_Template
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
<button type=\"button\"
        id=\"";
        // line 27
        echo twig_escape_filter($this->env, $this->getAttribute(($context["grid"] ?? null), "id", array()), "html", null, true);
        echo "_grid_action_";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["action"] ?? null), "id", array()), "html", null, true);
        echo "\"
        class=\"dropdown-item js-";
        // line 28
        echo twig_escape_filter($this->env, $this->getAttribute(($context["action"] ?? null), "id", array()), "html", null, true);
        echo "-grid-action\"
>
  ";
        // line 30
        if ( !twig_test_empty($this->getAttribute(($context["action"] ?? null), "icon", array()))) {
            // line 31
            echo "    <i class=\"material-icons\">";
            echo twig_escape_filter($this->env, $this->getAttribute(($context["action"] ?? null), "icon", array()), "html", null, true);
            echo "</i>
  ";
        }
        // line 33
        echo "  ";
        echo twig_escape_filter($this->env, $this->getAttribute(($context["action"] ?? null), "name", array()), "html", null, true);
        echo "
</button>
";
    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Common/Grid/Actions/Grid/simple.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  42 => 33,  36 => 31,  34 => 30,  29 => 28,  23 => 27,  19 => 25,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@PrestaShop/Admin/Common/Grid/Actions/Grid/simple.html.twig", "/html/src/PrestaShopBundle/Resources/views/Admin/Common/Grid/Actions/Grid/simple.html.twig");
    }
}
