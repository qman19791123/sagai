<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : new.xsl
    Created on : 2016年11月16日, 下午4:07
    Author     : qman
    Description:
        Purpose of transformation follows.
-->

<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html"/>

    <!-- TODO customize transformation rules 
         syntax recommendation http://www.w3.org/TR/xslt 
    -->
    <xsl:template match="/">
        <html>
            <head>
                <title> <xsl:value-of select="xml/title"/></title>
            </head>
            <body>aaa
            </body>
        </html>
    </xsl:template>

</xsl:stylesheet>
