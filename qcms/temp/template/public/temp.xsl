<?xml version = "1.0"?>
<xsl:stylesheet 
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
    version="1.0"
    xmlns:php="http://php.net/xsl"
    xsl:extension-element-prefixes="php"
>
    <!-- 头部内容 start -->
    <xsl:template  name="top">
        <div class="top">
            <div class="header">
                <div class="container">
                    <ul class="row  hidden-xs">
                        <li class="col-sm-3">
                            <h1 class="logo">qman-cms</h1>
                        </li>
                        <li class="col-sm-9 link">
                            <nav>
                                
                                <xsl:variable name="pId">
                                    <xsl:value-of select="xml/pId"></xsl:value-of>
                                </xsl:variable>
                                
                                <xsl:variable name="in">
                                    <xsl:if test="$pId = ''">index</xsl:if>
                                </xsl:variable>
                                
                                <span class='{$in}'>
                                    <a href="/">首页</a>
                                </span>
                                
                                <xsl:for-each select="xml/class/node">
                                    <xsl:if test="hide=1">
                                        <xsl:variable name="ppId">
                                            <xsl:if test="$pId=id">index</xsl:if>
                                        </xsl:variable>
                                        <span  class="{$ppId}">
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
    </xsl:template>
    <!-- 头部内容 end -->
    
    <!-- 底部内容 start -->
    <xsl:template  name="fool">
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
    </xsl:template>
    <!-- 底部内容 end -->
    
    
    <xsl:template name="page">
        <xsl:param name="i"/>
        <xsl:param name="count"/>
        <xsl:if test="$i &lt;= $count">
            <li> 
                 <a href="index.php/news/index/{xml/pId}/{$i}"><xsl:value-of select="$i" disable-output-escaping = "yes" /></a>
            </li>
        </xsl:if>
        <xsl:if test="$i &lt;= $count">
            <xsl:call-template name="page">
                <xsl:with-param name="i">
                    <xsl:value-of select="$i + 1"/>
                </xsl:with-param>
                <xsl:with-param name="count">
                    <xsl:value-of select="$count"/>
                </xsl:with-param>
            </xsl:call-template>
        </xsl:if>
    </xsl:template>
</xsl:stylesheet>


