<?xml version="1.0" encoding="UTF-8"?>
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
    <xsl:include href="../public/head.xsl"></xsl:include>
    <xsl:include href="../public/temp.xsl"></xsl:include>
    <xsl:template match="/">
        <html lang="zh-CN">
            <head>
                <title>QCMS </title>
                <xsl:call-template name="header"></xsl:call-template>
            </head>
            <body>
                <div class="index">
                    <xsl:call-template name="top"></xsl:call-template>

                    <div class="content">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-3">
                                    <h2>开发者动态</h2>
                                    <span>Developer dynamics</span>
                                </div>
                                <div class="col-sm-9">
                                    <dl class="info">
                                        <dt>2016  </dt> 
                                        <dd class="infolist">
                                            <ul>
                                                <xsl:for-each select="xml/DeveloperDynamics/node">
                                                    <li>
                                                        <section>
                                                            <a href="index.php/news/content/{classifyId}/{id}">  
                                                                <h3>
                                                                    <xsl:value-of select='title'/> 
                                                                </h3>
                                                            </a>
                                                            <span class="hidden-xs">[<xsl:value-of select="php:function('date','Y-m-d',string(time))"/>]</span>
                                                            <p>
                                                                <xsl:value-of select="subtitle"/>
                                                                <button class="btn btn-primary visible-xs-inline-block"  onclick="window.location.href='index.php/news/content/{classifyId}/{id}'">查看</button>
                                                            </p>
                                                        </section> 
                                                    </li>
                                                </xsl:for-each>
                                            </ul>
                                        </dd>
                                    </dl>
                                </div>

                                <div class="col-sm-3">
                                    <h2>CMS手册</h2>
                                    <span>Development Manual</span>
                                </div>
                                <div class="col-sm-9">
                                    <dl class="info">
                                        <dt>2016 </dt>
                                        <dd class="infolist">
                                            <ul>
                                                <xsl:for-each select="xml/DevelopmentManual/node">
                                                    <li>
                                                        <section>
                                                            <a href="index.php/news/content/{classifyId}/{id}">  
                                                                <h3>
                                                                    <xsl:value-of select='title'/> 
                                                                </h3>
                                                            </a>
                                                            <span class="hidden-xs">[<xsl:value-of select="php:function('date','Y-m-d',string(time))"/>]</span>
                                                            <p>
                                                                <xsl:value-of select="subtitle"/>
                                                                <button class="btn btn-primary visible-xs-inline-block"  onclick="location.href='index.php/news/content/{classifyId}/{id}'">查看</button>
                                                            </p>
                                                        </section> 
                                                    </li>
                                                </xsl:for-each>
                                            </ul>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                    <xsl:call-template name="fool"></xsl:call-template>
                </div>
                <script type="text/javascript" src="js/jquery.min.js"></script>
                <script type="text/javascript" src="js/bootstrap/bootstrap.min.js"></script>
                <script type="text/javascript" src="js/Swiper/swiper.min.js"></script>
                <script type="text/javascript"><![CDATA[
                        var swiper = new Swiper('.swiper-container', {
                            pagination: '.swiper-pagination',
                            loop: true,
                            grabCursor: true,
                            paginationClickable: true,
                            autoplay: 2500,
                            autoplayDisableOnInteraction: false,
                        });
                    ]]>
                </script>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
