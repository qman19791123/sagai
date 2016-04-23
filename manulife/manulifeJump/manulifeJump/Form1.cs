using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace manulifeJump
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            this.panel1.Width = Width;
            this.panel1.Height = Height;
            this.panel1.Top = this.panel1.Left = 0;
            this.openurl();
        }
        private void openurl()
        {
            webBrowser1.ScriptErrorsSuppressed = true; //禁用错误脚本提示  
            this.webBrowser1.Navigate("https://www.mymanulife.com.hk");
        }

        private void webBrowser1_DocumentCompleted(object sender, WebBrowserDocumentCompletedEventArgs e)
        {
            int h = e.Url.ToString().IndexOf("wps/porta");
            if (h > 0)
            {

                bool isclick = false;
                foreach (HtmlElement j in webBrowser1.Document.GetElementsByTagName("input"))
                {

                    if (j.GetAttribute("type") == "radio" && j.Id == "radio2")
                    {
                        j.SetAttribute("checked", "true");
                    }

                    if (j.Id == "user_id")
                    {
                        j.SetAttribute("value", "nicole88");
                    }

                    if (j.Id == "mcnPin")
                    {
                        j.SetAttribute("value", "93480832");
                    }

                }
                //tabindex="3"

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
                this.webBrowser1.Navigate(" https://www.mymanulife.com.hk/ebs/cws_main.jsp?action=empf_cw008_01_init&amp;locale=zh_TW");
            }
            // var text = this.webBrowser1.Document.get("user_id");
            //<input type="radio" name="selectedFunc" value="1">
            h = e.Url.ToString().IndexOf("init");
            if (h > 0)
            {

                foreach (HtmlElement j in webBrowser1.Document.GetElementsByTagName("input"))
                {
                    if (j.GetAttribute("type") == "radio" && j.Name.ToString() == "selectedFunc" && int.Parse(j.GetAttribute("value")) == 1)
                    {
                        j.SetAttribute("checked", "true");

                    }
                }

                foreach (HtmlElement j in webBrowser1.Document.GetElementById("buttonsty").GetElementsByTagName("a"))
                {
                    j.InvokeMember("click");
                }
            }
            h = e.Url.ToString().IndexOf("go");

            if (h > 0)
            {

            }
        }
    }
}
