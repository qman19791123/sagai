<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
    <xsl:output method="html"/>
    <xsl:template match="/">
        <html lang="zh-CN">
            <head>
                <meta charset="utf-8"/>
                <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
                <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
                <title>
                    <xsl:value-of select="xml/title"/>
                </title>
                <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css"/>
                <link rel="stylesheet" href="css/bootstrap/bootstrap-theme.min.css"/>
                <link rel="stylesheet" href="css/css/qmanWeb.css"/>
                <script type="text/javascript" src="js/jquery.min.js"> </script>
                <script src="js/bootstrap/bootstrap.min.js"></script>
            </head>
            <body>
                <div class="container">
                    <ul class="link">
                        <xsl:for-each select="xml/classify/content">
                            <li>
                                <xsl:choose>
                                    <xsl:when test="setting = 0">
                                        <a href="index.php/new/{folder}/list"> 
                                            <xsl:value-of select="text"/>
                                        </a>
                                    </xsl:when>
                                    <xsl:otherwise>
                                        <a href="{url}">
                                            <xsl:value-of select="text"/>
                                        </a>
                                    </xsl:otherwise>
                                </xsl:choose>
                            </li>
                        </xsl:for-each>
                    </ul>
                </div>
               
                <div class="container">
                    <div class="col-md-3">
                        <ul>
                        <xsl:for-each select="xml/news/newList-Xinwenzhongxin/content">
                            <li>
                                <span>
                                    <xsl:value-of  select="id"/>
                                </span>
                            </li>
                        </xsl:for-each> 
                        </ul>
                    </div>
                    
                    <div class="col-md-6 col-xs-12">
                        asdasd
                    </div>
                    
                    <div class="col-md-3">
                        asdasd
                    </div>
                </div>
              
               
            </body>
        </html>
    </xsl:template>

</xsl:stylesheet>
