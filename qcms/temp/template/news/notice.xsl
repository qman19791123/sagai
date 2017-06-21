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
        
        
      
        
        <xsl:variable name="pId">
            <xsl:value-of select="xml/pId"></xsl:value-of>
        </xsl:variable>
        
        <html lang="en">
            <head>
                <title> 
                    <xsl:value-of select="xml/content/node/title"/> 
                </title>
              
                <xsl:call-template name="header"></xsl:call-template>
                <meta name="Keywords" content="{xml/content/node/keywords}"/>
                <meta name="description" content="{php:function('tfunction::compressionFile',string(xml/content/node/description))}" />
            </head>
            <body>

                <div class="newcontent">
                    <xsl:call-template name="top"></xsl:call-template>

                    <div class="content">
                        <div class="container">
                            <ul class="row">
                                <li class="col-sm-9">
                                    <a href="index.php/news/index/{$pId}">
                                        <xsl:choose>
                                            <xsl:when test="$pId=27">
                                           
                                                <h2>开发日志</h2>
                                                <span>Developer dynamics</span>
                                            
                                            </xsl:when>
                                            <xsl:when test="$pId=28">
                                           
                                                <h2>CMS手册</h2>
                                                <span>Development Manual</span>
                                            
                                            </xsl:when>
                                            <xsl:when test="$pId=29">
                                                <h2>程序下载</h2>
                                                <span> Program download</span>
                                            </xsl:when>
                                        </xsl:choose>
                                    </a>

                                    <div class="infolist">
                                        <h3>
                                            <xsl:value-of select="xml/content/node/title"/>
                                        </h3>
                                        <content>
                                            <time>
                                                <xsl:value-of select="php:function('date','Y-m-d H:i:s',string(xml/content/node/time))"/>
                                            </time>
                                        </content>
                                        <article>
                                            <xsl:value-of select="xml/content/node/subtitle"/>
                                        </article>
                                        <div class="info">
                                            <xsl:value-of select="php:function('tfunction::decode',string(xml/content/node/newText))"  disable-output-escaping="yes"/>
                                        </div>
                                      
                                        
                                    </div>
                                    <div class="page">
                                       
                                        <p>
                                            <xsl:if test="xml/lrpage/r/node/title!=''">
                                                <a href="index.php/news/content/{xml/lrpage/r/node/classifyId}/{xml/lrpage/r/node/id}">
                                                &lt;&lt;   
                                                    <xsl:value-of select="xml/lrpage/r/node/title"/> 
                                                </a>
                                            </xsl:if>
                                        </p>
                                      
                                        <p>
                                            <xsl:if test="xml/lrpage/l/node/title!=''">
                                                <a href="index.php/news/content/{xml/lrpage/l/node/classifyId}/{xml/lrpage/l/node/id}">
                                                    <xsl:value-of select="xml/lrpage/l/node/title"/>  &gt;&gt;
                                                </a>
                                            </xsl:if>
                                        </p>
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
