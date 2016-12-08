<?xml version = "1.0"?>
<xsl:stylesheet 
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0"
    xmlns:php="http://php.net/xsl"
    xsl:extension-element-prefixes="php"
>
    <xsl:template  name="header">
        <xsl:value-of  select="php:functionString('date','Y-m-d H:i:s')"/>
    </xsl:template>
</xsl:stylesheet>