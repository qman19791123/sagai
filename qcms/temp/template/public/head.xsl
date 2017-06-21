<?xml version = "1.0"?>
<xsl:stylesheet 
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0"
    xmlns:php="http://php.net/xsl"
    xsl:extension-element-prefixes="php"
>
    <xsl:template  name="header">
            
               <base href="{xml/HTTP_SERVER}" />
               
               <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1, user-scalable=no" />
               <meta content="email=no" name="format-detection" />
               <meta name="format-detection" content="telephone=no" />
               <link rel="stylesheet" href="/css/bootstrap/bootstrap.min.css" type="text/css" />
               <link rel="stylesheet" href="/css/Font-Awesome/css/font-awesome.min.css" type="text/css"/>
               <link rel="stylesheet" href="/css/Swiper/swiper.min.css"  type="text/css"/>
               <link rel="stylesheet" href="/{php:function('load::fun','lessc','qcmsindex.less')}" type="text/css"/>
          
           
    </xsl:template>
</xsl:stylesheet>

