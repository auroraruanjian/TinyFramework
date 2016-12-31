<?php

/* layout.html */
class __TwigTemplate_5df671690b6e4df5aadc95bdb8142d6427fe7a9d0ff3856f67626b9e3be506ba extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'content' => array($this, 'block_content'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "<!DOCTYPE html>
<html>
<head>
<meta charset=\"UTF-8\">
<title>Insert title here</title>
</head>
<body>
    <header>header</header>
    
    <content>
        ";
        // line 11
        $this->displayBlock('content', $context, $blocks);
        // line 13
        echo "    </content>
    
    <footer>footer</footer>
</body>
</html>";
    }

    // line 11
    public function block_content($context, array $blocks = array())
    {
        // line 12
        echo "        ";
    }

    public function getTemplateName()
    {
        return "layout.html";
    }

    public function getDebugInfo()
    {
        return array (  45 => 12,  42 => 11,  34 => 13,  32 => 11,  20 => 1,);
    }

    public function getSource()
    {
        return "<!DOCTYPE html>
<html>
<head>
<meta charset=\"UTF-8\">
<title>Insert title here</title>
</head>
<body>
    <header>header</header>
    
    <content>
        {% block content %}
        {% endblock %}
    </content>
    
    <footer>footer</footer>
</body>
</html>";
    }
}
