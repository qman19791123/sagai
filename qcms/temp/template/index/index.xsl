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
    <xsl:template match="/">
        <html lang="zh-CN">
            <head>
                <base href="{xml/HTTP_SERVER}" />
                <title>Document</title>
                <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1, user-scalable=no" />
                <meta content="email=no" name="format-detection" />
                <meta name="format-detection" content="telephone=no" />
                <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css" type="text/css" />
                <link rel="stylesheet" href="css/Font-Awesome/css/font-awesome.min.css" type="text/css"/>
                <link rel="stylesheet" href="css/Swiper/swiper.min.css"  type="text/css"/>
                <link rel="stylesheet" href="{php:function('load::fun','lessc','qcmsindex.less')}" type="text/css"/>
            </head>
            <body>
                <div class="index">
                    <div class="top">
                        <div class="header">
                            <div class="container">
                                <ul class="row  hidden-xs">
                                    <li class="col-sm-3">
                                        <h1 class="logo">qman-cms</h1>
                                    </li>
                                    <li class="col-sm-9 link">
                                        <nav>
                                            <span class="index">
                                                <a >首页</a>
                                            </span>
                                            <xsl:for-each select="xml/class/node">
                                                <xsl:if test="hide=1">
                                                    <span>
                                                        <xsl:if test="setting=0">
                                                            <a href="index.php/news/index/{id}">
                                                                <xsl:value-of select="text"/>
                                                            </a>
                                                        </xsl:if>
                                                        <xsl:if test="setting=1">
                                                            <a href="{url}">
                                                                <xsl:value-of select="text"/>
                                                            </a>
                                                        </xsl:if>
                                                        
                                                    </span>
                                                </xsl:if>
                                            </xsl:for-each>
                                        </nav>
                                        <form>
                                            <input placeholder="查询" />
                                            <button class="fa fa-search"></button>
                                        </form>
                                    </li>
                                </ul>
                                <ul class="row visible-xs-block ">
                                    <li class="col-xs-9 moblielogo">
                                        <h1 class="logo">qman-cms</h1>
                                    </li>
                                    <li class="col-xs-3 moblielink">
                                        <span class="fa fa-bars"></span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="banner">
                            <p>
                                <strong>Welcome <span>QMAN-CMS</span></strong>
                            </p>
                        </div>
                    </div>
                    <div class="notice hidden-xs">
                        <div class="container">
                            <ul class="row">
                                <li class="col-sm-2 ">
                                    <span class="title">公告</span>
                                </li>
                                <li class="col-sm-7">
                                    <div class="swiper-container">
                                        <ul class="swiper-wrapper">
                                            <xsl:for-each select="xml/notice/node">
                                                <li class="swiper-slide">
                                                    <a href="index.php/news/content/{classifyId}/{id}">
                                                        <xsl:value-of select='title'/>
                                                    </a>
                                                </li>
                                            </xsl:for-each>
                                        </ul>
                                    </div>
                                </li>
                                <li class="col-sm-3">
                                    <span class="annstati">qman-cms 国内完全开源及免费的cms</span>
                                </li>
                            </ul>
                        </div>
                    </div>

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
                                                            <h3>
                                                                <xsl:value-of select='title'/>
                                                            </h3>
                                                            <span class="hidden-xs">[<xsl:value-of select="php:function('date','Y-m-d',string(time))"/>]</span>
                                                            <p>
                                                                <xsl:value-of select="subtitle"/>
                                                                <button class="btn btn-primary visible-xs-inline-block">查看</button>
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
                                                            <h3>
                                                                <xsl:value-of select='title'/>
                                                            </h3>
                                                            <span class="hidden-xs">[<xsl:value-of select="php:function('date','Y-m-d',string(time))"/>]</span>
                                                            <p>
                                                                <xsl:value-of select="subtitle"/>
                                                                <button class="btn btn-primary visible-xs-inline-block">查看</button>
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
                    <div class="fool">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-4">
                                    <h1 class="logo">qman-cms</h1>
                                </div>
                                <div class="col-sm-8">
                                    <span class="webinfo">站为开源项目QMAN-CMS技术分享与开发讨论社区平台</span>
                                </div>
                            </div>
                        </div>
                    </div>
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
