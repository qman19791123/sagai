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
    <xsl:output method="html"/>
    <xsl:template match="/">
        <html lang="zh-CN">
            <head>
                <link rel="stylesheet" type="text/css" href="css/index.css"/>
                <link rel="stylesheet" type="text/css" href="css/Font-Awesome/css/font-awesome.min.css"/>
            </head>
            <body>
                <main class="index">
                    <header>
                        <div class="top">QMAN-CMS</div>
                        <div class="link">
                            <ul>
                                <li class="logo">111</li>
                                <li class="menu">
                                    <xsl:for-each select="xml/class/node">
                                        <span>
                                            <a href="index.php/news/index/{id}">
                                                <xsl:value-of select="text"/>
                                            </a>
                                        </span>
                                    </xsl:for-each>
                                </li>
                            </ul>
                        </div>
                        <div class="headquery">
                            <p class="host">1</p>
                            <form class="query">
                                <input type="" name="" />
                                <button  class="icon-search"></button>
                            </form>
                        </div>
                    </header>
                    <content class="p1">
                        <div class="add">111</div>
                        <div class="content">
                            <div class="l">
                                <div class="newstop">
                                    <h2>从个人角度去理解，微信平台兴起只因大家现在缺少一</h2>
                                    <strong>共舞台，所以不得不委屈求共舞台，所以不得不委屈求共共舞台，所以不得不委屈求共舞台，所以不得不委屈求共共舞台，所以不得不委屈求共舞台，所以不得不委屈求共共舞台，所以不得不委屈求共舞台，所以不得不委屈求共共舞台，所以不得不委屈求共舞台，所以不得不委屈求共</strong>
                                    <p>
                                        <span>
                                            <img src=""/>共舞台，所以不得不委屈求共舞台，所以不得不委屈求共舞台，所以不得不委屈求共舞台，所以不得不委屈求共舞台，所以不得不委屈求
                                        </span>
                                        <span>
                                            <img src=""/>共舞台，所以不得不委屈求共舞台，所以不得不委屈求共舞台，所以不得不委屈求共舞台，所以不得不委屈求共舞台，所以不得不委屈求

                                        </span>
                                    </p>
                                    <p>
                                        <span>
                                            <img src=""/>共舞台，所以不得不委屈求共舞台，所以不得不委屈求共舞台，所以不得不委屈求共舞台，所以不得不委屈求共舞台，所以不得不委屈求

                                        </span>
                                        <span>
                                            <img src=""/>共舞台，所以不得不委屈求共舞台，所以不得不委屈求共舞台，所以不得不委屈求共舞台，所以不得不委屈求共舞台，所以不得不委屈求
                                        </span>
                                    </p>
                                </div>
                                <dl>
                                    <dt>技术</dt>
                                    <dd>
                                        <ul>
                                            <li>
                                                <img src="" alt=""/>
                                                <span>从个人角度去理解，微信平台兴起只因大家现在缺少一个可以对外有效展示的公共舞台，所以不得不委屈求全的在这个闭环舞台空间中进行艰难的拓展。</span>
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                        </ul>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt>资讯</dt>
                                    <dd>
                                        <ul>
                                            <li>
                                                <img src="" alt=""/>
                                                <span>从个人角度去理解，微信平台兴起只因大家现在缺少一个可以对外有效展示的公共舞台，所以不得不委屈求全的在这个闭环舞台空间中进行艰难的拓展。</span>
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                        </ul>
                                    </dd>
                                </dl>
                                <div class="add">1111</div>
                                <dl>
                                    <dt>运营</dt>
                                    <dd>
                                        <ul>
                                            <li>
                                                <img src="" alt=""/>
                                                <span>从个人角度去理解，微信平台兴起只因大家现在缺少一个可以对外有效展示的公共舞台，所以不得不委屈求全的在这个闭环舞台空间中进行艰难的拓展。</span>
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                        </ul>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt>图集</dt>
                                    <dd>
                                        <ul>
                                            <li>
                                                <img src="" alt=""/>
                                                <span>从个人角度去理解，微信平台兴起只因大家现在缺少一个可以对外有效展示的公共舞台，所以不得不委屈求全的在这个闭环舞台空间中进行艰难的拓展。</span>
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                        </ul>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt>商店</dt>
                                    <dd>
                                        <ul>
                                            <li>
                                                <img src="" alt=""/>
                                                <span>从个人角度去理解，微信平台兴起只因大家现在缺少一个可以对外有效展示的公共舞台，所以不得不委屈求全的在这个闭环舞台空间中进行艰难的拓展。</span>
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                        </ul>
                                    </dd>
                                </dl>
                                <dl>
                                    <dt>论坛</dt>
                                    <dd>
                                        <ul>
                                            <li>
                                                <img src="" alt=""/>
                                                <span>从个人角度去理解，微信平台兴起只因大家现在缺少一个可以对外有效展示的公共舞台，所以不得不委屈求全的在这个闭环舞台空间中进行艰难的拓展。</span>
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                            <li>
                                                <span>微信提供了什么？微信能给我们带来什么？</span> [12-25]
                                            </li>
                                        </ul>
                                    </dd>
                                </dl>
                            </div>
                            <div class="r">
                                <dl>
                                    <dt>用户登录 </dt>
                                    <dd>
                                        <ul class="login">
                                            <li>
                                                <span>用户名</span>
                                                <input type="text" name=""/>
                                            </li>
                                            <li>
                                                <span>密码</span>
                                                <input type="password" name=""/>
                                            </li>
                                            <li>
                                                <button>登陆</button>
                                            </li>
                                        </ul>
                                    </dd>
                                </dl>
                                <div class="add">1111</div>
                                <dl>
                                    <dt>推荐内容 </dt>
                                    <dd>
                                        <ul class="news">
                                            <li>1</li>
                                            <li>2</li>
                                            <li>3</li>
                                            <li>4</li>
                                            <li>5</li>
                                            <li>1</li>
                                        </ul>
                                    </dd>
                                </dl>
                                <div class="add">1111</div>
                                <dl>
                                    <dt>最新产业 </dt>
                                    <dd>
                                        <ul class="news">
                                            <li>1</li>
                                            <li>2</li>
                                            <li>3</li>
                                            <li>4</li>
                                            <li>5</li>
                                            <li>1</li>
                                        </ul>
                                    </dd>
                                </dl>
                                <div class="add">1111</div>
                                <dl>
                                    <dt>最新产业 </dt>
                                    <dd>
                                        <ul class="news">
                                            <li>1</li>
                                            <li>2</li>
                                            <li>3</li>
                                            <li>4</li>
                                            <li>5</li>
                                            <li>1</li>
                                        </ul>
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </content>
      
                    <footer></footer>
                </main>
            </body>
        </html>
    </xsl:template>

</xsl:stylesheet>
