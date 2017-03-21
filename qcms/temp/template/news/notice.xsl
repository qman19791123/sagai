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
    <xsl:template match="/xml">
        <html lang="zh-CN">
            <head>
                <base href="{HTTP_SERVER}" />
                <meta name="title" content="{list/node/title}" />
                <meta name="keywords" content="{list/node/keywords}" />
                <meta name="description" content="{list/node/description}" />
                
                <meta name="viewport" content="width=device-width, initial-scale=1.0 maximum-scale=1, user-scalable=no" />
                <meta content="email=no" name="format-detection" />
                <meta name="format-detection" content="telephone=no" />
                <link rel="stylesheet" href="css/bootstrap/bootstrap.min.css" type="text/css" />
                <link rel="stylesheet" href="css/Font-Awesome/css/font-awesome.min.css" type="text/css"/>
                <link rel="stylesheet" href="css/Swiper/swiper.min.css"  type="text/css"/>
                <link rel="stylesheet" href="{php:function('load::fun','lessc','qcmsindex.less')}" type="text/css"/>
                <title>
                    <xsl:value-of select="list/node/title"/>
                </title>
                
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
                                            <xsl:for-each select="class/node">
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
                                            <xsl:for-each select="notice/node">
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
                        <h3>
                            <xsl:value-of select="list/node/title"/>
                        </h3>
                        <time>
                            <xsl:value-of select="php:function('date','Y-m-d',string(list/node/time))"/>
                        </time>
                        <article>
                            <xsl:value-of select="list/node/subtitle"/>
                        </article>
                        <div>
                            <xsl:value-of select="list/node/newText"/>
                        </div>
                    </div>
                </div>
            </body>
        </html>
    </xsl:template>

</xsl:stylesheet>
