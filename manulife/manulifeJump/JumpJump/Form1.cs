using System;
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
        public JumpJump()
        {
            InitializeComponent();
        }
        private int sizeSelectedPol = -1;
        private bool isOk = true;
        private bool isClick = false;
        private bool isSetOk = false;
        private int[] cursor = new int[27];

        private string ckUrl = "https://www.mymanulife.com.hk/ebs/cws_main.jsp?action=empf_cw008_01_init&amp;locale=zh_TW";
        private string coutUrl = "https://www.mymanulife.com.hk/wps/myportal/CwsHome/CwsAccountSetting/CwsUpdateContactInfo";
        private string Url = "https://www.mymanulife.com.hk";

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

        private void openurl()
        {
            webBrowser1.ScriptErrorsSuppressed = true; //禁用错误脚本提示  
            this.webBrowser1.Navigate(Url);
        }



        private void webBrowser1_DocumentCompleted(object sender, WebBrowserDocumentCompletedEventArgs e)
        {
            //用户身份
            int userIdentity = 0;

            cursor[0] = 50;
            cursor[1] = 50;

            int h = e.Url.ToString().IndexOf("wps/porta");
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
                        j.SetAttribute("value", "g5315882");
                    }
                    if (j.Id == "mcnPin")
                    {
                        j.SetAttribute("value", "92181866");
                    }

                }
          
                foreach (HtmlElement j in webBrowser1.Document.GetElementsByTagName("a"))
                {

                    if (j.GetAttribute("tabindex").ToString() == "3")
                    {
                        j.InvokeMember("click");
                    }
                }
            }
            h = e.Url.ToString().IndexOf("wps/myportal/CwsHome/CwsPortfolio");
            if (h > 0)
            {
                this.webBrowser1.Navigate(ckUrl);
            }
            h = e.Url.ToString().IndexOf("init");

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
                try
                {
                    foreach (HtmlElement j in webBrowser1.Document.GetElementById("buttonsty").GetElementsByTagName("a"))
                    {
                        sizeSelectedPol = sizeSelectedPol - 1;
                        if (sizeSelectedPol < 0)
                        {
                            isOk = true;
                        }
                        j.InvokeMember("click");
                    }
                }
                catch
                {
                    foreach (HtmlElement j in webBrowser1.Document.GetElementsByTagName("a"))
                    {
                        if (j.InnerText == "登出")
                        {
                            j.InvokeMember("click");
                        }
                    }
                }
            }
            h = e.Url.ToString().IndexOf("_go");
            int s = e.Url.ToString().IndexOf("_sel_cont");

            if (h > 0 || s > 0)
            {
                int contributionType = 0;
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
                }

                int xj = 0, xjj = 0;

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

                try
                {
                    //多帐套时候报错
                    HtmlElement buttonsty = webBrowser1.Document.GetElementById("buttonsty");

                    foreach (HtmlElement j in buttonsty.GetElementsByTagName("a"))
                    {
                        MessageBox.Show(j.InnerText);
                        if (j.InnerText == "呈交")
                        {
                            j.InvokeMember("click");
                        }
                        if (j.InnerText == "確定")
                        {
                            isSetOk = true;
                        }
                    }
                    if (isSetOk)
                    {
                        if (isOk)
                        {
                            //MessageBox.Show("byebye");
                            this.webBrowser1.Navigate(coutUrl);
                        }
                        else
                        {
                            this.webBrowser1.Navigate(ckUrl);
                        }
                    }
                }
                catch
                {

                }
                return;
            }
            return;
        }

        //public static void start(object webBrowsers)
        //{
        //    WebBrowser webBrowser1 = (WebBrowser)webBrowsers;
        //    foreach (HtmlElement j in webBrowser1.Document.GetElementsByTagName("input"))
        //    {
        //        if (j.GetAttribute("type") == "radio" && j.Name.ToString() == "selectedFunc" && int.Parse(j.GetAttribute("value")) == 3)
        //        {
        //            j.SetAttribute("checked", "true");

        //        }
        //    }
        //    foreach (HtmlElement j in webBrowser1.Document.GetElementById("buttonsty").GetElementsByTagName("a"))
        //    {
        //        j.InvokeMember("click");
        //    }
        //}
    }

}
