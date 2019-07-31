<?php

/* @PrestaShop/Admin/Common/Grid/Blocks/Table/headers_row.html.twig */
class __TwigTemplate_016e73dff1fba4a65edabb59b197c698aaf9429b108f99ed683a534bd3ebebe0 extends Twig_Template
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
<tr class=\"column-headers\">
  ";
        // line 27
        $context['_parent'] = $context;
        $context['_seq'] = twig_ensure_traversable($this->getAttribute(($context["grid"] ?? null), "columns", array()));
        foreach ($context['_seq'] as $context["_key"] => $context["column"]) {
            // line 28
            echo "    <th scope=\"col\">
      ";
            // line 29
            echo $this->env->getExtension('PrestaShopBundle\Twig\Extension\GridExtension')->renderColumnHeader($context["column"], ($context["grid"] ?? null));
            echo "
    </th>
  ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['column'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 32
        echo "</tr>
";
    }

    public function getTemplateName()
    {
        return "@PrestaShop/Admin/Common/Grid/Blocks/Table/headers_row.html.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  39 => 32,  30 => 29,  27 => 28,  23 => 27,  19 => 25,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("", "@PrestaShop/Admin/Common/Grid/Blocks/Table/headers_row.html.twig", "/html/src/PrestaShopBundle/Resources/views/Admin/Common/Grid/Blocks/Table/headers_row.html.twig");
    }
}
