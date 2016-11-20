<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : 2016.xsl.xsl
    Created on : 2016年11月16日, 下午5:27
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
                <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no"/>
                <title>
                    <xsl:value-of select="xml/title"/>
                </title>
                <base href="/" />
                <style>
                    *{
                    box-sizing: border-box;
                    }
                    ul,li,html,body{
                    margin: 0;
                    padding: 0;
                    }
                    ul,li,p{
                    list-style: none;
                    margin: auto;
                    
                    }
                    .activity{
                    width:80%;
                    margin: auto;
                    }
                    .activity li{
                    font-size: 0;
                    padding: 2.5% 0;
                    display: inline-block;
                    width: 100%;
                    }
                    .activity li p{
                    width: 100%;
                    border: 1px solid #ccc;
                    padding: 10px;
                    }
                    .activity li span{
                    padding: 8px;
                    width: 50%;
                    font-size: 12px;
                    display: inline-block;
                    } 
                    .activity li p select{
                    width: 80%;
                    height: 30px;
                    }
                    .activity li em{
                    font-size: 14px;
                    font-weight: bold;
                    display: inline-block;
                    margin: 2% 0;
                    font-style: normal;
                    }
                    .activity li span input{
                    float: right;
                    
                    }
                    .button button{
                    width:80%;
                    display: block;
                    margin: auto;
                    height:30px;
                    line-height:30px;
                    }
                    .top{
                    width:100%;
                    line-height:50px;
                    border:1px solid #ccc;
                    }
                    .Validator-error{
                        font-size:14px;
                    }
                    input[type=text]{
                    padding: 2%;
                    line-height:20px;
                    width:100%;
                    }
                </style>
            </head>
            <body>
                
                <div class="top"> 
                    <xsl:value-of select="xml/data/title"/>
                </div>
                <xsl:choose>
                    <xsl:when test="xml/data/content">
                        <form method="post" class="Validator" action="index.php/activity/index/1?activity=1">
                            
                            <xsl:for-each select="xml/data/content">
                                <ul class="activity">
                                    <li> 
                                        
                                        <!--/参数模版 start/-->
                                        <xsl:variable name="activityValue">
                                            <xsl:value-of select="activityValue"></xsl:value-of>
                                        </xsl:variable>
                                        <xsl:variable name="activityKey">
                                            <xsl:value-of select="activityKey"></xsl:value-of>
                                        </xsl:variable>
                                        <xsl:variable name="activityInput">
                                            <xsl:value-of select="activityInput"></xsl:value-of>
                                        </xsl:variable>
                                        <!--/参数模版 end/-->
                                        
                                        <xsl:if test="activityInput='text'">
                                            <label></label>
                                            <input placeholder="{$activityValue}" name="{$activityKey}" type="{$activityInput}" data-required="data-required" /> 
                                        </xsl:if>
                                        
                                        <xsl:if test="activityInput='radio' or  activityInput='checkbox' or  activityInput='select'">
                                            <em>
                                                <xsl:copy-of select="$activityValue"></xsl:copy-of>
                                            </em>
                                        </xsl:if>
                                        
                                        <xsl:if test="activityInput='radio' or  activityInput='checkbox'">
                                            <p>
                                                <xsl:for-each select="activitystateList/team">
                                                    <xsl:variable name="team">
                                                        <xsl:value-of select="."></xsl:value-of>
                                                    </xsl:variable>
                                                    <xsl:variable name="id">
                                                        <xsl:value-of select="./@id"></xsl:value-of>
                                                    </xsl:variable>
                                                    <label for="{$activityKey}-{$id}"> 
                                                        <span>
                                                            <xsl:copy-of select="$team" />
                                                            <input value="{$team}"  name="{$activityKey}[]" id="{$activityKey}-{$id}" data-required='data-required' type="{$activityInput}" /> 
                                                        </span>
                                                    </label>
                                                </xsl:for-each>
                                            </p>
                                        </xsl:if>
                                        
                                        <xsl:if test="activityInput='select'">
                                            <p>
                                                <select name="{$activityKey}">
                                                    <xsl:for-each select="activitystateList/team">
                                                        <xsl:variable name="team">
                                                            <xsl:value-of select="."></xsl:value-of>
                                                        </xsl:variable>                                               
                                                        <option>
                                                            <xsl:copy-of select="$team"></xsl:copy-of>
                                                        </option>
                                                    </xsl:for-each>
                                                </select>
                                            </p>
                                        </xsl:if>
                                        
                                    </li>
                                </ul>
                            </xsl:for-each>
                            
                            <div class="button">
                                <button>提交</button>
                            </div>
                        </form>
                    </xsl:when>
                    <xsl:otherwise>
                        <div>
                            <xsl:value-of select="xml/errContent"/>
                        </div>
                    </xsl:otherwise>
                </xsl:choose>
                
               
                
                <script type="text/javascript" src="js/jquery.min.js"></script>
                <script type="text/javascript" src="js/validate.min.js"></script>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>