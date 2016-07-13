﻿using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace JumpJump
{
    public partial class JumpJump : Form
    {

        private int sizeSelectedPol = -1;
        private bool isOk = true;
        private bool isClick = false;
        private bool isSetOk = false;
        private bool IsCout = false;
        //  private bool NotContributionType = true;
        private int[] cursor = new int[27];


        private string ckUrl = "https://www.mymanulife.com.hk/ebs/cws_main.jsp?action=empf_cw008_01_init&amp;locale=zh_TW";
        private string coutUrl = "https://www.mymanulife.com.hk/wps/myportal/CwsHome/CwsAccountSetting/CwsUpdateContactInfo";
        private string Url = "https://www.mymanulife.com.hk";

        public JumpJump()
        {
            InitializeComponent();
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            this.Form1_Resize(sender, e);
            this.openurl();
        }
        private void Form1_Resize(object sender, EventArgs e)
        {
            this.panel1.Top = this.panel1.Left = 0;
            this.panel1.Size = this.Size;
        }

        /// <summary>
        /// 打开地址
        /// </summary>
        private void openurl()
        {
            webBrowser1.ScriptErrorsSuppressed = true; //禁用错误脚本提示  
            this.webBrowser1.Navigate(Url);
        }



        private void webBrowser1_DocumentCompleted(object sender, WebBrowserDocumentCompletedEventArgs e)
        {

            int userIdentity = 0;
            int h = 0;
            /*测试代码*/
            cursor[0] = 50;
            cursor[1] = 50;


            #region 登出
            if (IsCout)
            {
                foreach (HtmlElement j in webBrowser1.Document.GetElementsByTagName("a"))
                {
                    if (j.InnerText == "登出")
                    {
                        j.InvokeMember("click");
                        return;
                    }
                }
            }
            #endregion

            #region 回滚
            h = e.Url.ToString().IndexOf("ebs/cws_main_public.jsp?action=clogin_portal_logout");
            if (h > 0)
            {
                sizeSelectedPol = -1;
                isOk = true;
                isClick = false;
                isSetOk = false;
                IsCout = false;
                this.webBrowser1.Navigate(Url);
            }
            #endregion

            #region 登录部分
            h = e.Url.ToString().IndexOf("wps/portal/cwscustomerlogin/!");
            if (h > 0)
            {
                foreach (HtmlElement j in webBrowser1.Document.GetElementsByTagName("input"))
                {
                    if (j.GetAttribute("type") == "radio" && j.Id == userCard.isUserCard(userIdentity))
                    {
                        j.SetAttribute("checked", "true");
                    }
                    if (j.Id == "user_id")
                    {
                        j.SetAttribute("value", "C6334081");
                    }
                    if (j.Id == "mcnPin")
                    {
                        j.SetAttribute("value", "92168214");
                    }
                }

                foreach (HtmlElement j in webBrowser1.Document.GetElementsByTagName("a"))
                {

                    if (!string.IsNullOrEmpty(j.InnerText) && j.InnerText.ToString() == "登入")
                    {
                        j.InvokeMember("click");
                    }
                }
                return;
            }
            #endregion


            #region 进入欢迎页
            h = e.Url.ToString().IndexOf("ebs/cws_main.jsp?action=clogin");
            if (h > 0 && !IsCout)
            {
                this.webBrowser1.Navigate(ckUrl);
                return;
            }
            h = e.Url.ToString().IndexOf("wps/myportal/CwsHome/CwsPortfolio/!");
            if (h > 0)
            {
                this.webBrowser1.Navigate(ckUrl);
                return;
            }
            #endregion

            #region 进入 管理基金組合  步驟一 : 請選擇帳戶
            h = e.Url.ToString().IndexOf("init&amp;locale=zh_TW");
            if (h > 0)
            {
                isClick = false;
                if (sizeSelectedPol <= -1)
                {
                    foreach (HtmlElement j in webBrowser1.Document.GetElementsByTagName("input"))
                    {
                        if (j.GetAttribute("type") == "radio" && j.Name.ToString() == "selectedPol")
                        {
                            sizeSelectedPol++;
                            isOk = false;
                        }
                    }
                }

                foreach (HtmlElement j in webBrowser1.Document.GetElementsByTagName("input"))
                {
                    if (j.GetAttribute("type") == "radio" && j.Name.ToString() == "selectedPol" && int.Parse(j.GetAttribute("value")) == sizeSelectedPol)
                    {
                        j.SetAttribute("checked", "true");
                        break;
                    }
                }

                foreach (HtmlElement j in webBrowser1.Document.GetElementsByTagName("input"))
                {
                    if (j.GetAttribute("type") == "radio" && j.Name.ToString() == "selectedFunc" && int.Parse(j.GetAttribute("value")) == 3)
                    {
                        j.SetAttribute("checked", "true");
                    }
                }


                foreach (HtmlElement j in webBrowser1.Document.GetElementById("buttonsty").GetElementsByTagName("a"))
                {
                    if (j.InnerText.ToString() == "確定")
                    {
                        sizeSelectedPol = sizeSelectedPol - 1;
                        if (sizeSelectedPol < 0)
                        {
                            isOk = true;
                        }
                        j.InvokeMember("click");
                    }
                }
                return;
            }
            #endregion


            #region 进入 管理基金組合  基金價格及表現
            h = e.Url.ToString().IndexOf("_go");
            int s = e.Url.ToString().IndexOf("_sel_cont");
            if (h > 0 || s > 0)
            {
                try
                {
                    int contributionType = 0, xj = 0, xjj = 0;

                    if (!isClick)
                    {
                        foreach (HtmlElement j in webBrowser1.Document.GetElementsByTagName("input"))
                        {
                            if ((j.GetAttribute("type") == "radio" && j.Name.ToString() == "contributionType") && (int.Parse(j.GetAttribute("value")) == 0 || int.Parse(j.GetAttribute("value")) == 9))
                            {
                                j.SetAttribute("checked", "true");
                                j.InvokeMember("click");
                                isClick = true;
                                contributionType = int.Parse(j.GetAttribute("value"));
                            }
                        }
                        if (isClick) { return; }
                       
                    }
                    isClick = true;
                    foreach (HtmlElement j in webBrowser1.Document.GetElementsByTagName("input"))
                    {
                        if (j.Name.ToString() == ("mandCont" + xj).ToString())
                        {
                            j.SetAttribute("value", cursor[xj].ToString());
                            xj++;
                        }
                    }

                    foreach (HtmlElement j in webBrowser1.Document.GetElementsByTagName("input"))
                    {
                        if (j.Name.ToString() == ("volCont" + xjj).ToString())
                        {
                            j.SetAttribute("value", cursor[xjj].ToString());
                            xjj++;
                        }
                    }
                }
                catch { }
          
                try
                {
                    HtmlElement buttonsty = webBrowser1.Document.GetElementById("buttonsty");
                    foreach (HtmlElement j in buttonsty.GetElementsByTagName("a"))
                    {
                        if (j.InnerText == "呈交")
                        {
                            
                            j.InvokeMember("click");
                            return;
                        }
                        if (j.InnerText == "確定")
                        {
                            isSetOk = true;
                            isClick = false;
                            //return;
                        }
                    }
                }
                catch {
                    
                }
                if (isSetOk)
                {
                    if (isOk)
                    {
                        IsCout = true;
                        this.webBrowser1.Navigate(coutUrl);
                        return;
                    }
                    else
                    {
                        this.webBrowser1.Navigate(ckUrl);
                        return;
                    }
                }
                return;

            }
            #endregion
            return;
        }
    }
}