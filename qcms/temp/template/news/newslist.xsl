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
        <xsl:variable name="title" >
            <xsl:choose>
                <xsl:when test="xml/pId=27"> 开发日志 Developer dynamics </xsl:when>
                <xsl:when test="xml/pId=28"> CMS手册  Development Manual </xsl:when>
                <xsl:when test="xml/pId=29"> 程序下载  Program download </xsl:when>
            </xsl:choose>
        </xsl:variable>
        
        <html lang="en">
            <!--head.xsl->header 包含 头部css js 等全局 文件 -->
            <head>
                <title>
                    <xsl:value-of select="$title" disable-output-escaping="no"/>
                </title>
                <xsl:call-template name="header"></xsl:call-template>
            </head>
            <body>
                <div class="log">
                    <!--temp.xsl->top 通用文件 -->
                    <xsl:call-template name="top"></xsl:call-template>
                    <div class="content">
                        <div class="container">
                            <ul class="row">
                                <li class="col-sm-9 r">
                                    
                                    <xsl:choose>
                                        <xsl:when test="xml/pId=27">
                                            <h2>开发日志</h2>
                                            <span>Developer dynamics</span>
                                        </xsl:when>
                                        <xsl:when test="xml/pId=28">
                                            <h2>CMS手册</h2>
                                            <span>Development Manual</span>
                                        </xsl:when>
                                        <xsl:when test="xml/pId=29">
                                            <h2>程序下载</h2>
                                            <span> Program download</span>
                                        </xsl:when>
                                    </xsl:choose>
                                    
                                    <div class="infolist">
                                        
                                        <xsl:for-each select="xml/list/page/node">
                                            <section>
                                                <a href="index.php/news/content/{classifyId}/{id}">  
                                                    <xsl:value-of select="title"/>
                                                </a>
                                                <span class="hidden-xs">[<xsl:value-of select="php:function('date','Y-m-d',string(time))"/>]</span>
                                            </section> 
                                        </xsl:for-each>
                                        
                                    </div>
                                    <div class="page">
                                        <nav aria-label="Page navigation">
                                            <ul class="pagination">
                                                <li>
                                                    
                                                    <a href="index.php/news/index/{xml/pId}/{xml/upPage}" aria-label="Previous">
                                                   
                                                        <span aria-hidden="true">«</span>
                                                    </a>
                                                </li>
                                                <xsl:call-template name="page">
                                                    <xsl:with-param name="i"> 
                                                        <xsl:value-of select="xml/list/count/pageStart"/>
                                                    </xsl:with-param>
                                                    <xsl:with-param name="count">
                                                        <xsl:value-of select="xml/list/count/pageEnd"/>
                                                    </xsl:with-param>
                                                </xsl:call-template>
                                                <li>
                                                    <a href="index.php/news/index/{xml/pId}/{xml/downPage}" aria-label="Next">
                                                        <span aria-hidden="true">»</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </nav>
                                    </div>
                                </li>
                                <li class="col-sm-3 hidden-xs">
                                    <dl class="classify">
                                        <dt>CMS手册<span>Development Manual</span></dt>
                                        <dd>
                                            <section>
                                                冷天的周末，窝在租处中。拖延了好几天<span class="hidden-xs">[2016-11-12]</span>
                                            </section>
                                            <section>
                                                冷天的周末，窝在租处中。拖延了好几天<span class="hidden-xs">[2016-11-12]</span>
                                            </section>
                                            <section>
                                                冷天的周末，窝在租处中。拖延了好几天<span class="hidden-xs">[2016-11-12]</span>
                                            </section>
                                            <section>
                                                冷天的周末，窝在租处中。拖延了好几天<span class="hidden-xs">[2016-11-12]</span>
                                            </section>
                                            <section>
                                                冷天的周末，窝在租处中。拖延了好几天<span class="hidden-xs">[2016-11-12]</span>
                                            </section>
                                        </dd>
                                    </dl>
                                    <dl class="classify">
                                        <dt>程序下载 <span>Program download</span></dt>
                                        <dd>
                                            <section>
                                                冷天的周末，窝在租处中。拖延了好几天<span class="hidden-xs">[2016-11-12]</span>
                                            </section>
                                            <section>
                                                冷天的周末，窝在租处中。拖延了好几天<span class="hidden-xs">[2016-11-12]</span>
                                            </section>
                                            <section>
                                                冷天的周末，窝在租处中。拖延了好几天<span class="hidden-xs">[2016-11-12]</span>
                                            </section>
                                            <section>
                                                冷天的周末，窝在租处中。拖延了好几天<span class="hidden-xs">[2016-11-12]</span>
                                            </section>
                                            <section>
                                                冷天的周末，窝在租处中。拖延了好几天<span class="hidden-xs">[2016-11-12]</span>
                                            </section>

                                        </dd>
                                    </dl>
                                </li>

                            </ul>
                        </div>
                    </div>
                    <!--temp.xsl->fool 通用文件 -->
                    <xsl:call-template name="fool"></xsl:call-template>
                </div>
                <script type="text/javascript" src="/js/jquery.min.js"></script>
                <script type="text/javascript" src="/js/bootstrap/bootstrap.min.js"></script>
                <script type="text/javascript" src="/js/Swiper/swiper.min.js"></script>
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
