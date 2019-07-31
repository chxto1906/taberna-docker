<?php

/* @Login/login.twig */
class __TwigTemplate_af8a498923b0cedda33a725b95544a4cf49cf0fbb05579b3ad72d0ab34ebfa91 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 2
        $this->parent = $this->loadTemplate("@Login/loginLayout.twig", "@Login/login.twig", 2);
        $this->blocks = array(
            'loginContent' => array($this, 'block_loginContent'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "@Login/loginLayout.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 4
    public function block_loginContent($context, array $blocks = array())
    {
        // line 5
        echo "    <div class=\"contentForm loginForm\">
        ";
        // line 6
        $this->loadTemplate("@Login/login.twig", "@Login/login.twig", 6, "1086270761")->display(array_merge($context, array("title" => call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LogIn")))));
        // line 72
        echo "    </div>
    <div class=\"contentForm resetForm\" style=\"display:none;\">
        ";
        // line 74
        $this->loadTemplate("@Login/login.twig", "@Login/login.twig", 74, "1364865922")->display(array_merge($context, array("title" => call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_ChangeYourPassword")))));
        // line 129
        echo "    </div>

";
    }

    public function getTemplateName()
    {
        return "@Login/login.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  42 => 129,  40 => 74,  36 => 72,  34 => 6,  31 => 5,  28 => 4,  11 => 2,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("
{% extends '@Login/loginLayout.twig' %}

{% block loginContent %}
    <div class=\"contentForm loginForm\">
        {% embed 'contentBlock.twig' with {'title': 'Login_LogIn'|translate} %}
            {% block content %}
                <div class=\"message_container\">

                    {{ include('@Login/_formErrors.twig', {formErrors: form_data.errors } )  }}

                    {% if AccessErrorString %}
                        <div piwik-notification
                             noclear=\"true\"
                             context=\"error\">
                            <strong>{{ 'General_Error'|translate }}</strong>: {{ AccessErrorString|raw }}<br/>
                        </div>
                    {% endif %}

                    {% if infoMessage %}
                        <p class=\"message\">{{ infoMessage|raw }}</p>
                    {% endif %}
                </div>

                <form {{ form_data.attributes|raw }} ng-non-bindable>
                    <div class=\"row\">
                        <div class=\"col s12 input-field\">
                            <input type=\"text\" name=\"form_login\" placeholder=\"\" id=\"login_form_login\" class=\"input\" value=\"\" size=\"20\"
                                   autocorrect=\"off\" autocapitalize=\"none\"
                                   tabindex=\"10\" autofocus=\"autofocus\"/>
                            <label for=\"login_form_login\"><i class=\"icon-user icon\"></i> {{ 'Login_LoginOrEmail'|translate }}</label>
                        </div>
                    </div>

                    <div class=\"row\">
                        <div class=\"col s12 input-field\">
                            <input type=\"hidden\" name=\"form_nonce\" id=\"login_form_nonce\" value=\"{{ nonce }}\"/>
                            <input type=\"hidden\" name=\"form_redirect\" id=\"login_form_redirect\" value=\"\"/>
                            <input type=\"password\" placeholder=\"\" name=\"form_password\" id=\"login_form_password\" class=\"input\" value=\"\" size=\"20\"
                                   autocorrect=\"off\" autocapitalize=\"none\"
                                   tabindex=\"20\" />
                            <label for=\"login_form_password\"><i class=\"icon-locked icon\"></i> {{ 'General_Password'|translate }}</label>
                        </div>
                    </div>

                    <div class=\"row actions\">
                        <div class=\"col s12\">
                            <input name=\"form_rememberme\" type=\"checkbox\" id=\"login_form_rememberme\" value=\"1\" tabindex=\"90\"
                                   {% if form_data.form_rememberme.value %}checked=\"checked\" {% endif %}/>
                            <label for=\"login_form_rememberme\">{{ 'Login_RememberMe'|translate }}</label>
                            <input class=\"submit btn\" id='login_form_submit' type=\"submit\" value=\"{{ 'Login_LogIn'|translate }}\"
                                   tabindex=\"100\"/>
                        </div>
                    </div>

                </form>
                <p id=\"nav\">
                    {{ postEvent(\"Template.loginNav\", \"top\") }}
                    <a id=\"login_form_nav\" href=\"#\"
                       title=\"{{ 'Login_LostYourPassword'|translate }}\">{{ 'Login_LostYourPassword'|translate }}</a>
                    {{ postEvent(\"Template.loginNav\", \"bottom\") }}
                </p>

                {% if isCustomLogo %}
                    <p id=\"piwik\">
                        <i><a href=\"https://matomo.org/\" rel=\"noreferrer noopener\" target=\"_blank\">{{ linkTitle }}</a></i>
                    </p>
                {% endif %}

            {% endblock %}
        {% endembed %}
    </div>
    <div class=\"contentForm resetForm\" style=\"display:none;\">
        {% embed 'contentBlock.twig' with {'title': 'Login_ChangeYourPassword'|translate} %}
            {% block content %}

                <div class=\"message_container\">
                </div>

                <form id=\"reset_form\" method=\"post\" ng-non-bindable>
                    <div class=\"row\">
                        <div class=\"col s12 input-field\">
                            <input type=\"hidden\" name=\"form_nonce\" id=\"reset_form_nonce\" value=\"{{ nonce }}\"/>
                            <input type=\"text\" placeholder=\"\" name=\"form_login\" id=\"reset_form_login\" class=\"input\" value=\"\" size=\"20\"
                                   autocorrect=\"off\" autocapitalize=\"none\"
                                   tabindex=\"10\"/>
                            <label for=\"reset_form_login\"><i class=\"icon-user icon\"></i> {{ 'Login_LoginOrEmail'|translate }}</label>
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"col s12 input-field\">
                            <input type=\"password\" placeholder=\"\" name=\"form_password\" id=\"reset_form_password\" class=\"input\" value=\"\" size=\"20\"
                                   autocorrect=\"off\" autocapitalize=\"none\"
                                   tabindex=\"20\" autocomplete=\"off\"/>
                            <label for=\"reset_form_password\"><i class=\"icon-locked icon\"></i> {{ 'Login_NewPassword'|translate }}</label>
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"col s12 input-field\">
                            <input type=\"password\" placeholder=\"\" name=\"form_password_bis\" id=\"reset_form_password_bis\" class=\"input\" value=\"\"
                                   autocorrect=\"off\" autocapitalize=\"none\"
                                   size=\"20\" tabindex=\"30\" autocomplete=\"off\"/>
                            <label for=\"reset_form_password_bis\"><i class=\"icon-locked icon\"></i> {{ 'Login_NewPasswordRepeat'|translate }}</label>
                        </div>
                    </div>

                    <div class=\"row actions\">
                        <div class=\"col s12\">
                            <input class=\"submit btn\" id='reset_form_submit' type=\"submit\"
                                   value=\"{{ 'General_ChangePassword'|translate }}\" tabindex=\"100\"/>

                            <span class=\"loadingPiwik\" style=\"display:none;\">
                            <img alt=\"Loading\" src=\"plugins/Morpheus/images/loading-blue.gif\"/>
                        </span>
                        </div>
                    </div>

                    <input type=\"hidden\" name=\"module\" value=\"{{ loginModule }}\"/>
                    <input type=\"hidden\" name=\"action\" value=\"resetPassword\"/>
                </form>
                <p id=\"nav\">
                    <a id=\"reset_form_nav\" href=\"#\"
                       title=\"{{ 'Mobile_NavigationBack'|translate }}\">{{ 'General_Cancel'|translate }}</a>
                    <a id=\"alternate_reset_nav\" href=\"#\" style=\"display:none;\"
                       title=\"{{'Login_LogIn'|translate}}\">{{ 'Login_LogIn'|translate }}</a>
                </p>
            {% endblock %}
        {% endembed %}
    </div>

{% endblock %}", "@Login/login.twig", "/html/analytics/plugins/Login/templates/login.twig");
    }
}


/* @Login/login.twig */
class __TwigTemplate_af8a498923b0cedda33a725b95544a4cf49cf0fbb05579b3ad72d0ab34ebfa91_1086270761 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 6
        $this->parent = $this->loadTemplate("contentBlock.twig", "@Login/login.twig", 6);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "contentBlock.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 7
    public function block_content($context, array $blocks = array())
    {
        // line 8
        echo "                <div class=\"message_container\">

                    ";
        // line 10
        echo twig_include($this->env, $context, "@Login/_formErrors.twig", array("formErrors" => $this->getAttribute(($context["form_data"] ?? $this->getContext($context, "form_data")), "errors", array())));
        echo "

                    ";
        // line 12
        if (($context["AccessErrorString"] ?? $this->getContext($context, "AccessErrorString"))) {
            // line 13
            echo "                        <div piwik-notification
                             noclear=\"true\"
                             context=\"error\">
                            <strong>";
            // line 16
            echo \Piwik\piwik_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Error")), "html", null, true);
            echo "</strong>: ";
            echo ($context["AccessErrorString"] ?? $this->getContext($context, "AccessErrorString"));
            echo "<br/>
                        </div>
                    ";
        }
        // line 19
        echo "
                    ";
        // line 20
        if (($context["infoMessage"] ?? $this->getContext($context, "infoMessage"))) {
            // line 21
            echo "                        <p class=\"message\">";
            echo ($context["infoMessage"] ?? $this->getContext($context, "infoMessage"));
            echo "</p>
                    ";
        }
        // line 23
        echo "                </div>

                <form ";
        // line 25
        echo $this->getAttribute(($context["form_data"] ?? $this->getContext($context, "form_data")), "attributes", array());
        echo " ng-non-bindable>
                    <div class=\"row\">
                        <div class=\"col s12 input-field\">
                            <input type=\"text\" name=\"form_login\" placeholder=\"\" id=\"login_form_login\" class=\"input\" value=\"\" size=\"20\"
                                   autocorrect=\"off\" autocapitalize=\"none\"
                                   tabindex=\"10\" autofocus=\"autofocus\"/>
                            <label for=\"login_form_login\"><i class=\"icon-user icon\"></i> ";
        // line 31
        echo \Piwik\piwik_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LoginOrEmail")), "html", null, true);
        echo "</label>
                        </div>
                    </div>

                    <div class=\"row\">
                        <div class=\"col s12 input-field\">
                            <input type=\"hidden\" name=\"form_nonce\" id=\"login_form_nonce\" value=\"";
        // line 37
        echo \Piwik\piwik_escape_filter($this->env, ($context["nonce"] ?? $this->getContext($context, "nonce")), "html", null, true);
        echo "\"/>
                            <input type=\"hidden\" name=\"form_redirect\" id=\"login_form_redirect\" value=\"\"/>
                            <input type=\"password\" placeholder=\"\" name=\"form_password\" id=\"login_form_password\" class=\"input\" value=\"\" size=\"20\"
                                   autocorrect=\"off\" autocapitalize=\"none\"
                                   tabindex=\"20\" />
                            <label for=\"login_form_password\"><i class=\"icon-locked icon\"></i> ";
        // line 42
        echo \Piwik\piwik_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Password")), "html", null, true);
        echo "</label>
                        </div>
                    </div>

                    <div class=\"row actions\">
                        <div class=\"col s12\">
                            <input name=\"form_rememberme\" type=\"checkbox\" id=\"login_form_rememberme\" value=\"1\" tabindex=\"90\"
                                   ";
        // line 49
        if ($this->getAttribute($this->getAttribute(($context["form_data"] ?? $this->getContext($context, "form_data")), "form_rememberme", array()), "value", array())) {
            echo "checked=\"checked\" ";
        }
        echo "/>
                            <label for=\"login_form_rememberme\">";
        // line 50
        echo \Piwik\piwik_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_RememberMe")), "html", null, true);
        echo "</label>
                            <input class=\"submit btn\" id='login_form_submit' type=\"submit\" value=\"";
        // line 51
        echo \Piwik\piwik_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LogIn")), "html", null, true);
        echo "\"
                                   tabindex=\"100\"/>
                        </div>
                    </div>

                </form>
                <p id=\"nav\">
                    ";
        // line 58
        echo call_user_func_array($this->env->getFunction('postEvent')->getCallable(), array("Template.loginNav", "top"));
        echo "
                    <a id=\"login_form_nav\" href=\"#\"
                       title=\"";
        // line 60
        echo \Piwik\piwik_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LostYourPassword")), "html", null, true);
        echo "\">";
        echo \Piwik\piwik_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LostYourPassword")), "html", null, true);
        echo "</a>
                    ";
        // line 61
        echo call_user_func_array($this->env->getFunction('postEvent')->getCallable(), array("Template.loginNav", "bottom"));
        echo "
                </p>

                ";
        // line 64
        if (($context["isCustomLogo"] ?? $this->getContext($context, "isCustomLogo"))) {
            // line 65
            echo "                    <p id=\"piwik\">
                        <i><a href=\"https://matomo.org/\" rel=\"noreferrer noopener\" target=\"_blank\">";
            // line 66
            echo \Piwik\piwik_escape_filter($this->env, ($context["linkTitle"] ?? $this->getContext($context, "linkTitle")), "html", null, true);
            echo "</a></i>
                    </p>
                ";
        }
        // line 69
        echo "
            ";
    }

    public function getTemplateName()
    {
        return "@Login/login.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  358 => 69,  352 => 66,  349 => 65,  347 => 64,  341 => 61,  335 => 60,  330 => 58,  320 => 51,  316 => 50,  310 => 49,  300 => 42,  292 => 37,  283 => 31,  274 => 25,  270 => 23,  264 => 21,  262 => 20,  259 => 19,  251 => 16,  246 => 13,  244 => 12,  239 => 10,  235 => 8,  232 => 7,  215 => 6,  42 => 129,  40 => 74,  36 => 72,  34 => 6,  31 => 5,  28 => 4,  11 => 2,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("
{% extends '@Login/loginLayout.twig' %}

{% block loginContent %}
    <div class=\"contentForm loginForm\">
        {% embed 'contentBlock.twig' with {'title': 'Login_LogIn'|translate} %}
            {% block content %}
                <div class=\"message_container\">

                    {{ include('@Login/_formErrors.twig', {formErrors: form_data.errors } )  }}

                    {% if AccessErrorString %}
                        <div piwik-notification
                             noclear=\"true\"
                             context=\"error\">
                            <strong>{{ 'General_Error'|translate }}</strong>: {{ AccessErrorString|raw }}<br/>
                        </div>
                    {% endif %}

                    {% if infoMessage %}
                        <p class=\"message\">{{ infoMessage|raw }}</p>
                    {% endif %}
                </div>

                <form {{ form_data.attributes|raw }} ng-non-bindable>
                    <div class=\"row\">
                        <div class=\"col s12 input-field\">
                            <input type=\"text\" name=\"form_login\" placeholder=\"\" id=\"login_form_login\" class=\"input\" value=\"\" size=\"20\"
                                   autocorrect=\"off\" autocapitalize=\"none\"
                                   tabindex=\"10\" autofocus=\"autofocus\"/>
                            <label for=\"login_form_login\"><i class=\"icon-user icon\"></i> {{ 'Login_LoginOrEmail'|translate }}</label>
                        </div>
                    </div>

                    <div class=\"row\">
                        <div class=\"col s12 input-field\">
                            <input type=\"hidden\" name=\"form_nonce\" id=\"login_form_nonce\" value=\"{{ nonce }}\"/>
                            <input type=\"hidden\" name=\"form_redirect\" id=\"login_form_redirect\" value=\"\"/>
                            <input type=\"password\" placeholder=\"\" name=\"form_password\" id=\"login_form_password\" class=\"input\" value=\"\" size=\"20\"
                                   autocorrect=\"off\" autocapitalize=\"none\"
                                   tabindex=\"20\" />
                            <label for=\"login_form_password\"><i class=\"icon-locked icon\"></i> {{ 'General_Password'|translate }}</label>
                        </div>
                    </div>

                    <div class=\"row actions\">
                        <div class=\"col s12\">
                            <input name=\"form_rememberme\" type=\"checkbox\" id=\"login_form_rememberme\" value=\"1\" tabindex=\"90\"
                                   {% if form_data.form_rememberme.value %}checked=\"checked\" {% endif %}/>
                            <label for=\"login_form_rememberme\">{{ 'Login_RememberMe'|translate }}</label>
                            <input class=\"submit btn\" id='login_form_submit' type=\"submit\" value=\"{{ 'Login_LogIn'|translate }}\"
                                   tabindex=\"100\"/>
                        </div>
                    </div>

                </form>
                <p id=\"nav\">
                    {{ postEvent(\"Template.loginNav\", \"top\") }}
                    <a id=\"login_form_nav\" href=\"#\"
                       title=\"{{ 'Login_LostYourPassword'|translate }}\">{{ 'Login_LostYourPassword'|translate }}</a>
                    {{ postEvent(\"Template.loginNav\", \"bottom\") }}
                </p>

                {% if isCustomLogo %}
                    <p id=\"piwik\">
                        <i><a href=\"https://matomo.org/\" rel=\"noreferrer noopener\" target=\"_blank\">{{ linkTitle }}</a></i>
                    </p>
                {% endif %}

            {% endblock %}
        {% endembed %}
    </div>
    <div class=\"contentForm resetForm\" style=\"display:none;\">
        {% embed 'contentBlock.twig' with {'title': 'Login_ChangeYourPassword'|translate} %}
            {% block content %}

                <div class=\"message_container\">
                </div>

                <form id=\"reset_form\" method=\"post\" ng-non-bindable>
                    <div class=\"row\">
                        <div class=\"col s12 input-field\">
                            <input type=\"hidden\" name=\"form_nonce\" id=\"reset_form_nonce\" value=\"{{ nonce }}\"/>
                            <input type=\"text\" placeholder=\"\" name=\"form_login\" id=\"reset_form_login\" class=\"input\" value=\"\" size=\"20\"
                                   autocorrect=\"off\" autocapitalize=\"none\"
                                   tabindex=\"10\"/>
                            <label for=\"reset_form_login\"><i class=\"icon-user icon\"></i> {{ 'Login_LoginOrEmail'|translate }}</label>
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"col s12 input-field\">
                            <input type=\"password\" placeholder=\"\" name=\"form_password\" id=\"reset_form_password\" class=\"input\" value=\"\" size=\"20\"
                                   autocorrect=\"off\" autocapitalize=\"none\"
                                   tabindex=\"20\" autocomplete=\"off\"/>
                            <label for=\"reset_form_password\"><i class=\"icon-locked icon\"></i> {{ 'Login_NewPassword'|translate }}</label>
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"col s12 input-field\">
                            <input type=\"password\" placeholder=\"\" name=\"form_password_bis\" id=\"reset_form_password_bis\" class=\"input\" value=\"\"
                                   autocorrect=\"off\" autocapitalize=\"none\"
                                   size=\"20\" tabindex=\"30\" autocomplete=\"off\"/>
                            <label for=\"reset_form_password_bis\"><i class=\"icon-locked icon\"></i> {{ 'Login_NewPasswordRepeat'|translate }}</label>
                        </div>
                    </div>

                    <div class=\"row actions\">
                        <div class=\"col s12\">
                            <input class=\"submit btn\" id='reset_form_submit' type=\"submit\"
                                   value=\"{{ 'General_ChangePassword'|translate }}\" tabindex=\"100\"/>

                            <span class=\"loadingPiwik\" style=\"display:none;\">
                            <img alt=\"Loading\" src=\"plugins/Morpheus/images/loading-blue.gif\"/>
                        </span>
                        </div>
                    </div>

                    <input type=\"hidden\" name=\"module\" value=\"{{ loginModule }}\"/>
                    <input type=\"hidden\" name=\"action\" value=\"resetPassword\"/>
                </form>
                <p id=\"nav\">
                    <a id=\"reset_form_nav\" href=\"#\"
                       title=\"{{ 'Mobile_NavigationBack'|translate }}\">{{ 'General_Cancel'|translate }}</a>
                    <a id=\"alternate_reset_nav\" href=\"#\" style=\"display:none;\"
                       title=\"{{'Login_LogIn'|translate}}\">{{ 'Login_LogIn'|translate }}</a>
                </p>
            {% endblock %}
        {% endembed %}
    </div>

{% endblock %}", "@Login/login.twig", "/html/analytics/plugins/Login/templates/login.twig");
    }
}


/* @Login/login.twig */
class __TwigTemplate_af8a498923b0cedda33a725b95544a4cf49cf0fbb05579b3ad72d0ab34ebfa91_1364865922 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        // line 74
        $this->parent = $this->loadTemplate("contentBlock.twig", "@Login/login.twig", 74);
        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "contentBlock.twig";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 75
    public function block_content($context, array $blocks = array())
    {
        // line 76
        echo "
                <div class=\"message_container\">
                </div>

                <form id=\"reset_form\" method=\"post\" ng-non-bindable>
                    <div class=\"row\">
                        <div class=\"col s12 input-field\">
                            <input type=\"hidden\" name=\"form_nonce\" id=\"reset_form_nonce\" value=\"";
        // line 83
        echo \Piwik\piwik_escape_filter($this->env, ($context["nonce"] ?? $this->getContext($context, "nonce")), "html", null, true);
        echo "\"/>
                            <input type=\"text\" placeholder=\"\" name=\"form_login\" id=\"reset_form_login\" class=\"input\" value=\"\" size=\"20\"
                                   autocorrect=\"off\" autocapitalize=\"none\"
                                   tabindex=\"10\"/>
                            <label for=\"reset_form_login\"><i class=\"icon-user icon\"></i> ";
        // line 87
        echo \Piwik\piwik_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LoginOrEmail")), "html", null, true);
        echo "</label>
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"col s12 input-field\">
                            <input type=\"password\" placeholder=\"\" name=\"form_password\" id=\"reset_form_password\" class=\"input\" value=\"\" size=\"20\"
                                   autocorrect=\"off\" autocapitalize=\"none\"
                                   tabindex=\"20\" autocomplete=\"off\"/>
                            <label for=\"reset_form_password\"><i class=\"icon-locked icon\"></i> ";
        // line 95
        echo \Piwik\piwik_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_NewPassword")), "html", null, true);
        echo "</label>
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"col s12 input-field\">
                            <input type=\"password\" placeholder=\"\" name=\"form_password_bis\" id=\"reset_form_password_bis\" class=\"input\" value=\"\"
                                   autocorrect=\"off\" autocapitalize=\"none\"
                                   size=\"20\" tabindex=\"30\" autocomplete=\"off\"/>
                            <label for=\"reset_form_password_bis\"><i class=\"icon-locked icon\"></i> ";
        // line 103
        echo \Piwik\piwik_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_NewPasswordRepeat")), "html", null, true);
        echo "</label>
                        </div>
                    </div>

                    <div class=\"row actions\">
                        <div class=\"col s12\">
                            <input class=\"submit btn\" id='reset_form_submit' type=\"submit\"
                                   value=\"";
        // line 110
        echo \Piwik\piwik_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_ChangePassword")), "html", null, true);
        echo "\" tabindex=\"100\"/>

                            <span class=\"loadingPiwik\" style=\"display:none;\">
                            <img alt=\"Loading\" src=\"plugins/Morpheus/images/loading-blue.gif\"/>
                        </span>
                        </div>
                    </div>

                    <input type=\"hidden\" name=\"module\" value=\"";
        // line 118
        echo \Piwik\piwik_escape_filter($this->env, ($context["loginModule"] ?? $this->getContext($context, "loginModule")), "html", null, true);
        echo "\"/>
                    <input type=\"hidden\" name=\"action\" value=\"resetPassword\"/>
                </form>
                <p id=\"nav\">
                    <a id=\"reset_form_nav\" href=\"#\"
                       title=\"";
        // line 123
        echo \Piwik\piwik_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Mobile_NavigationBack")), "html", null, true);
        echo "\">";
        echo \Piwik\piwik_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("General_Cancel")), "html", null, true);
        echo "</a>
                    <a id=\"alternate_reset_nav\" href=\"#\" style=\"display:none;\"
                       title=\"";
        // line 125
        echo \Piwik\piwik_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LogIn")), "html", null, true);
        echo "\">";
        echo \Piwik\piwik_escape_filter($this->env, call_user_func_array($this->env->getFilter('translate')->getCallable(), array("Login_LogIn")), "html", null, true);
        echo "</a>
                </p>
            ";
    }

    public function getTemplateName()
    {
        return "@Login/login.twig";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  624 => 125,  617 => 123,  609 => 118,  598 => 110,  588 => 103,  577 => 95,  566 => 87,  559 => 83,  550 => 76,  547 => 75,  530 => 74,  358 => 69,  352 => 66,  349 => 65,  347 => 64,  341 => 61,  335 => 60,  330 => 58,  320 => 51,  316 => 50,  310 => 49,  300 => 42,  292 => 37,  283 => 31,  274 => 25,  270 => 23,  264 => 21,  262 => 20,  259 => 19,  251 => 16,  246 => 13,  244 => 12,  239 => 10,  235 => 8,  232 => 7,  215 => 6,  42 => 129,  40 => 74,  36 => 72,  34 => 6,  31 => 5,  28 => 4,  11 => 2,);
    }

    /** @deprecated since 1.27 (to be removed in 2.0). Use getSourceContext() instead */
    public function getSource()
    {
        @trigger_error('The '.__METHOD__.' method is deprecated since version 1.27 and will be removed in 2.0. Use getSourceContext() instead.', E_USER_DEPRECATED);

        return $this->getSourceContext()->getCode();
    }

    public function getSourceContext()
    {
        return new Twig_Source("
{% extends '@Login/loginLayout.twig' %}

{% block loginContent %}
    <div class=\"contentForm loginForm\">
        {% embed 'contentBlock.twig' with {'title': 'Login_LogIn'|translate} %}
            {% block content %}
                <div class=\"message_container\">

                    {{ include('@Login/_formErrors.twig', {formErrors: form_data.errors } )  }}

                    {% if AccessErrorString %}
                        <div piwik-notification
                             noclear=\"true\"
                             context=\"error\">
                            <strong>{{ 'General_Error'|translate }}</strong>: {{ AccessErrorString|raw }}<br/>
                        </div>
                    {% endif %}

                    {% if infoMessage %}
                        <p class=\"message\">{{ infoMessage|raw }}</p>
                    {% endif %}
                </div>

                <form {{ form_data.attributes|raw }} ng-non-bindable>
                    <div class=\"row\">
                        <div class=\"col s12 input-field\">
                            <input type=\"text\" name=\"form_login\" placeholder=\"\" id=\"login_form_login\" class=\"input\" value=\"\" size=\"20\"
                                   autocorrect=\"off\" autocapitalize=\"none\"
                                   tabindex=\"10\" autofocus=\"autofocus\"/>
                            <label for=\"login_form_login\"><i class=\"icon-user icon\"></i> {{ 'Login_LoginOrEmail'|translate }}</label>
                        </div>
                    </div>

                    <div class=\"row\">
                        <div class=\"col s12 input-field\">
                            <input type=\"hidden\" name=\"form_nonce\" id=\"login_form_nonce\" value=\"{{ nonce }}\"/>
                            <input type=\"hidden\" name=\"form_redirect\" id=\"login_form_redirect\" value=\"\"/>
                            <input type=\"password\" placeholder=\"\" name=\"form_password\" id=\"login_form_password\" class=\"input\" value=\"\" size=\"20\"
                                   autocorrect=\"off\" autocapitalize=\"none\"
                                   tabindex=\"20\" />
                            <label for=\"login_form_password\"><i class=\"icon-locked icon\"></i> {{ 'General_Password'|translate }}</label>
                        </div>
                    </div>

                    <div class=\"row actions\">
                        <div class=\"col s12\">
                            <input name=\"form_rememberme\" type=\"checkbox\" id=\"login_form_rememberme\" value=\"1\" tabindex=\"90\"
                                   {% if form_data.form_rememberme.value %}checked=\"checked\" {% endif %}/>
                            <label for=\"login_form_rememberme\">{{ 'Login_RememberMe'|translate }}</label>
                            <input class=\"submit btn\" id='login_form_submit' type=\"submit\" value=\"{{ 'Login_LogIn'|translate }}\"
                                   tabindex=\"100\"/>
                        </div>
                    </div>

                </form>
                <p id=\"nav\">
                    {{ postEvent(\"Template.loginNav\", \"top\") }}
                    <a id=\"login_form_nav\" href=\"#\"
                       title=\"{{ 'Login_LostYourPassword'|translate }}\">{{ 'Login_LostYourPassword'|translate }}</a>
                    {{ postEvent(\"Template.loginNav\", \"bottom\") }}
                </p>

                {% if isCustomLogo %}
                    <p id=\"piwik\">
                        <i><a href=\"https://matomo.org/\" rel=\"noreferrer noopener\" target=\"_blank\">{{ linkTitle }}</a></i>
                    </p>
                {% endif %}

            {% endblock %}
        {% endembed %}
    </div>
    <div class=\"contentForm resetForm\" style=\"display:none;\">
        {% embed 'contentBlock.twig' with {'title': 'Login_ChangeYourPassword'|translate} %}
            {% block content %}

                <div class=\"message_container\">
                </div>

                <form id=\"reset_form\" method=\"post\" ng-non-bindable>
                    <div class=\"row\">
                        <div class=\"col s12 input-field\">
                            <input type=\"hidden\" name=\"form_nonce\" id=\"reset_form_nonce\" value=\"{{ nonce }}\"/>
                            <input type=\"text\" placeholder=\"\" name=\"form_login\" id=\"reset_form_login\" class=\"input\" value=\"\" size=\"20\"
                                   autocorrect=\"off\" autocapitalize=\"none\"
                                   tabindex=\"10\"/>
                            <label for=\"reset_form_login\"><i class=\"icon-user icon\"></i> {{ 'Login_LoginOrEmail'|translate }}</label>
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"col s12 input-field\">
                            <input type=\"password\" placeholder=\"\" name=\"form_password\" id=\"reset_form_password\" class=\"input\" value=\"\" size=\"20\"
                                   autocorrect=\"off\" autocapitalize=\"none\"
                                   tabindex=\"20\" autocomplete=\"off\"/>
                            <label for=\"reset_form_password\"><i class=\"icon-locked icon\"></i> {{ 'Login_NewPassword'|translate }}</label>
                        </div>
                    </div>
                    <div class=\"row\">
                        <div class=\"col s12 input-field\">
                            <input type=\"password\" placeholder=\"\" name=\"form_password_bis\" id=\"reset_form_password_bis\" class=\"input\" value=\"\"
                                   autocorrect=\"off\" autocapitalize=\"none\"
                                   size=\"20\" tabindex=\"30\" autocomplete=\"off\"/>
                            <label for=\"reset_form_password_bis\"><i class=\"icon-locked icon\"></i> {{ 'Login_NewPasswordRepeat'|translate }}</label>
                        </div>
                    </div>

                    <div class=\"row actions\">
                        <div class=\"col s12\">
                            <input class=\"submit btn\" id='reset_form_submit' type=\"submit\"
                                   value=\"{{ 'General_ChangePassword'|translate }}\" tabindex=\"100\"/>

                            <span class=\"loadingPiwik\" style=\"display:none;\">
                            <img alt=\"Loading\" src=\"plugins/Morpheus/images/loading-blue.gif\"/>
                        </span>
                        </div>
                    </div>

                    <input type=\"hidden\" name=\"module\" value=\"{{ loginModule }}\"/>
                    <input type=\"hidden\" name=\"action\" value=\"resetPassword\"/>
                </form>
                <p id=\"nav\">
                    <a id=\"reset_form_nav\" href=\"#\"
                       title=\"{{ 'Mobile_NavigationBack'|translate }}\">{{ 'General_Cancel'|translate }}</a>
                    <a id=\"alternate_reset_nav\" href=\"#\" style=\"display:none;\"
                       title=\"{{'Login_LogIn'|translate}}\">{{ 'Login_LogIn'|translate }}</a>
                </p>
            {% endblock %}
        {% endembed %}
    </div>

{% endblock %}", "@Login/login.twig", "/html/analytics/plugins/Login/templates/login.twig");
    }
}
