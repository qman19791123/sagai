<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : notice.xsl
    Created on : 2017年3月3日, 下午1:26
    Author     : qman
    Description:
        Purpose of transformation follows.
-->

<xsl:stylesheet 
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0"
    xmlns:php="http://php.net/xsl"
    xsl:extension-element-prefixes="php"
>
    
    <xsl:output
        method="html"
        doctype-system="about:legacy-compat"
        encoding="UTF-8"
        indent="yes" 
    />

    <!-- TODO customize transformation rules 
         syntax recommendation http://www.w3.org/TR/xslt 
    -->
    <xsl:template match="/">
        <html>
            <head>
                <title>notice.xsl</title>
                
            </head>
            <body>
            </body>
        </html>
    </xsl:template>

</xsl:stylesheet>
