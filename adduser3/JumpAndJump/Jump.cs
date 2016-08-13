using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Runtime.InteropServices;
using System.Security.Permissions;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using System.IO;

namespace JumpAndJump
{
    [PermissionSet(SecurityAction.Demand, Name = "FullTrust")]
    [ComVisible(true)]//com+可见
    public partial class Jump : Form
    {

        private int logout = 0;
        private TextBox[] textbox = new TextBox[20];
        private string File = Application.StartupPath;
        private int Needle_ = 0;
        private string[] gotoSum = { };
        private bool isNotOff = false;
        private string FileErr = null;
        private IniClass FileConfig = null;
        private DataRowCollection userInformation;
        private Boolean modify = true;
        public Jump()
        {
            InitializeComponent();
        }
        #region Jump 主体
        private void Jump_Load(object sender, EventArgs e)
        {
            #region Jump 文档配置
            string Errlog = System.Configuration.ConfigurationManager.AppSettings["Errlog"].ToString();
            this.FileErr = this.File + Errlog;
            string FromConfig = System.Configuration.ConfigurationManager.AppSettings["FromConfig"].ToString();
            this.FileConfig = new IniClass(this.File + FromConfig);
            //获取用户组信息
            int usergGroup = this.getUserGriup();
            //获取金额流向
            this.gotoSum = this.getGoToSum();
            //获取用户信息 
            this.userInformation = this.getUserInformation(usergGroup);
            //获取修改判定
            this.modify = this.getModify();
            this.JumpConfig();
            #endregion
        }


        #endregion
        /// <summary>
        /// Config 配置
        /// </summary>
        private void JumpConfig()
        {
            this.webBrowser1.Navigate("https://www.bocpt.com/english/mpf/login.aspx");
            this.panel1.Width = this.Width;
            this.panel1.Height = this.Height;
            this.panel1.Left = 0;
            this.panel1.Top = 0;
        }

        private void webBrowser1_DocumentCompleted(object sender, WebBrowserDocumentCompletedEventArgs e)
        {
            this.isNotOff = true;
            this.userLogin(e);
            this.linkInvestment(e);
            this.eeinvestrebaltermsClick(e);
            this.eeinvestrebalClick(e);
        }

        /// <summary>
        /// 登录
        /// </summary>
        /// <param name="e"></param>
        private void userLogin(WebBrowserDocumentCompletedEventArgs e)
        {
            if (e.Url.ToString().IndexOf("login.aspx") > 1)
            {
                try
                {

                    if (this.userInformation.Count <= this.Needle_)
                    {
                        //删除错误文档
                        System.IO.File.Delete(this.FileErr);
                        //可以关闭窗口简历
                        this.isNotOff = false;
                        //清理缓存条Jump功能
                        new System.Threading.Mutex(true, Application.ProductName).ReleaseMutex();
                        Application.Restart();
                        return;
                    }

                    //用户名
                    this.webBrowser1.Document.GetElementById("txtSSID").SetAttribute("value", this.userInformation[this.Needle_]["userName"].ToString().Trim());
                    this.click("btn_Continue");

                    ///密码
                    this.webBrowser1.Document.GetElementById("txtPassword").SetAttribute("value", this.userInformation[this.Needle_]["userPasswd"].ToString().Trim());
                    this.click("btn_Login");
                }
                catch { return; }
            }
        }

        /// <summary>
        /// 点击投资按钮
        /// 在登录后点击前往投资的按钮 
        /// </summary>
        /// <param name="e"></param>
        private void linkInvestment(WebBrowserDocumentCompletedEventArgs e)
        {
            if (e.Url.ToString().IndexOf("eeaccount.aspx") > 1)
            {
                HtmlElementCollection colleciton = webBrowser1.Document.GetElementById("ctl00_GridView2").GetElementsByTagName("a");
                foreach (HtmlElement ele in colleciton)
                {
                    try
                    {
                        if (ele.InnerText == "Re-Balance Existing Investment")
                        {
                            ele.InvokeMember("click");
                        }
                    }
                    catch { }
                }
            }
        }
        /// <summary>
        /// 在确认页，点击确认按钮完成确认事项
        /// </summary>
        /// <param name="e"></param>
        private void eeinvestrebaltermsClick(WebBrowserDocumentCompletedEventArgs e)
        {
            if (e.Url.ToString().IndexOf("eeinvestrebalterms.aspx") > 1)
            {
                try
                {
                    this.webBrowser1.Document.GetElementById("ctl00_MainContent_btnContinue").InvokeMember("click");
                }
                catch { }
            }

        }
        /// <summary>
        ///  投资项单，完成投资项单，退出投资
        /// </summary>
        /// <param name="e"></param>
        private void eeinvestrebalClick(WebBrowserDocumentCompletedEventArgs e)
        {
            if (e.Url.ToString().IndexOf("eeinvestrebal.aspx") > 1)
            {
                this.logout++;
                if (this.modify == true)
                {
                    try
                    {
                        switch (this.logout)
                        {
                            case 1:
                                this.eeinvestrebalClickmodify();
                                break;
                            case 2:
                                this.eeinvestrebalClickFillData();
                                break;
                            case 3:
                                this.eeinvestrebalClickConfirm();
                                break;
                            case 4:
                                this.eeinvestrebalClickLogOut();
                                break;
                        }
                    }
                    catch { }
                }
                else
                {
                    try
                    {
                        switch (this.logout)
                        {
                            case 1:
                                this.eeinvestrebalClickFillData();
                                break;
                            case 2:
                                this.eeinvestrebalClickConfirm();
                                break;
                            case 3:
                                this.eeinvestrebalClickLogOut();
                                break;
                        }
                    }
                    catch { }
                }
            }
        }

        /// <summary>
        /// 修改信息
        /// </summary>
        private void eeinvestrebalClickmodify()
        {
            // webBrowser1.Document.InvokeScript("eval", new string[] { "window.confirm = true;" });
            webBrowser1.Document.GetElementById("ctl00_MainContent_btn_modify").InvokeMember("Click");
        }

        /// <summary>
        /// 填写需要投资的项单并提交
        /// </summary>
        private void eeinvestrebalClickFillData()
        {
            HtmlElementCollection table = webBrowser1.Document.GetElementsByTagName("table");
            foreach (HtmlElement ele in table)
            {
                if (ele.Id == "ctl00_MainContent_ContentTable_MandatoryContribution" ||
                    ele.Id == "ctl00_MainContent_ContentTable_RegularandLumpSumSpecialVoluntaryContribution" ||
                    ele.Id == "ctl00_MainContent_ContentTable_VoluntaryContribution")
                {
                    if (this.modify == true)
                    {
                        this.eeinvestrebalClickFillDataDangerousCodeStart(ele.GetElementsByTagName("tr"));
                    }
                    this.eeinvestrebalClickFillDataDangerousCode(ele.GetElementsByTagName("tr"));
                }
            }
            webBrowser1.Document.GetElementById("ctl00_MainContent_btn_Submit2").InvokeMember("Click");
        }

        /// <summary>
        /// 确认订单并提交订单
        /// </summary>
        private void eeinvestrebalClickConfirm()
        {
            this.webBrowser1.Document.GetElementById("ctl00_MainContent_btn_Confirm").InvokeMember("click");
            this.fileWriter(this.userInformation[this.Needle_]["id"].ToString().Trim());
            //this.eeinvestrebalClickLogOut();
            //eeinvestrebalClickLogOut();

        }
        /// <summary>
        /// 退出项单
        /// </summary>
        private void eeinvestrebalClickLogOut()
        {
           this.webBrowser1.Document.GetElementById("ctl00_btnLogOut").InvokeMember("click");
            this.Needle_++;
            this.logout = 0;
        }
        void eeinvestrebalClickFillDataDangerousCodeStart(HtmlElementCollection collecitons)
        {
            int i = 0, j = 0;
            // 循环整个列表区域
            foreach (HtmlElement ele in collecitons)
            {

                /*  取出中间输入 区域 
                 *  i---------------------
                 *  i 代表了头部 5 TR 区域
                 * j---------------------
                 * j 代表15个输入框所在区域，所以在超过了15 后将跳出整个循环区块。
                */

                i++;
                if (i <= 5)
                {
                    continue;
                }
                else if (j >= 15)
                {
                    break;
                }
                else
                {
                    int x = 0;
                    HtmlElementCollection collecitontd = ele.GetElementsByTagName("td");
                    foreach (HtmlElement eletd in collecitontd)
                    {
                        if (x >= 4)
                        {

                            HtmlElementCollection collecitontdinput = eletd.GetElementsByTagName("input");
                            foreach (HtmlElement eleinput in collecitontdinput)
                            {
                                eleinput.SetAttribute("value", "");
                                eleinput.InvokeMember("onBlur");
                            }
                        }
                        x++;
                    }
                    j++;

                }
            }
        }
        /// <summary>
        /// 正式填写投资单域
        /// </summary>
        /// <param name="collecitons"></param>
        void eeinvestrebalClickFillDataDangerousCode(HtmlElementCollection collecitons)
        {
            int i = 0, j = 0;
            // 循环整个列表区域
            foreach (HtmlElement ele in collecitons)
            {

                /*  取出中间输入 区域 
                 *  i---------------------
                 *  i 代表了头部 5 TR 区域
                 * j---------------------
                 * j 代表15个输入框所在区域，所以在超过了15 后将跳出整个循环区块。
                */

                i++;
                if (i <= 5)
                {
                    continue;
                }
                else if (j >= 15)
                {
                    break;
                }
                else
                {
                    int x = 0;
                    HtmlElementCollection collecitontd = ele.GetElementsByTagName("td");
                    foreach (HtmlElement eletd in collecitontd)
                    {
                        if (x >= 4)
                        {

                            HtmlElementCollection collecitontdinput = eletd.GetElementsByTagName("input");
                            foreach (HtmlElement eleinput in collecitontdinput)
                            {
                                if (!string.IsNullOrWhiteSpace(this.gotoSum[j].ToString()))
                                {
                                    eleinput.SetAttribute("value", this.gotoSum[j].ToString());

                                    break;

                                }
                                eleinput.InvokeMember("onBlur");
                            }
                        }
                        x++;
                    }
                    j++;

                }
            }
        }
        /// <summary>
        /// 点击登按钮
        /// </summary>
        /// <param name="clickName">
        /// 按钮名称
        /// </param>
        private void click(string clickName)
        {
            HtmlElementCollection colleciton = webBrowser1.Document.GetElementsByTagName("input");
            foreach (HtmlElement ele in colleciton)
            {
                if (clickName == ele.Name)
                {
                    ele.InvokeMember("click");
                }
            }
        }
        /// <summary>
        /// 开关
        /// </summary>
        public bool NotOff
        {
            get
            {
                return this.isNotOff;
            }
        }


        /// <summary>
        /// 获取用户组信息
        /// </summary>
        /// <returns></returns>
        private int getUserGriup()
        {
            int Pkey_ = 0;
            string userGroup = this.FileConfig.IniReadValue("userKey", "key");
            for (int i = 65, j = 1; i < 65 + 26; i++, j++)
            {
                if (((char)i).ToString().ToUpper() == userGroup)
                {
                    Pkey_ = j;
                    break;
                }
            }
            return Pkey_;
        }
        /// <summary>
        /// 获取金额流向
        /// </summary>
        /// <returns></returns>
        private string[] getGoToSum()
        {
            string[] inputSum = new string[20];
            for (int i = 0; i < 20; i++)
            {
                inputSum[i] = this.FileConfig.IniReadValue("InputContent", "F_" + i.ToString());
            }
            return inputSum;
        }

        /// <summary>
        /// 获取用户信息
        /// </summary>
        /// <returns></returns>
        private DataRowCollection getUserInformation(int usergGroup)
        {
            string sql = "";
            if (System.IO.File.Exists(this.FileErr))
            {
                string Content = this.fileContent().Substring(1);
                if (string.IsNullOrWhiteSpace(Content))
                {
                    sql = "select userPasswd,userName,id from boc_user where [class]=" + usergGroup + " and  id not in(" + Content + ")";
                }
                else
                {
                    System.IO.File.Delete(this.FileErr);
                    this.isNotOff = false;
                    new System.Threading.Mutex(true, Application.ProductName).ReleaseMutex();
                    Application.Restart();
                    return null;
                }
            }
            else
            {
                sql = "select userPasswd,userName,id from boc_user where [class]=" + usergGroup;
            }
            db ISdb = new db();
            return ISdb.dbfile(sql).Tables[0].Rows;
        }
        /// <summary>
        /// 修改启动
        /// </summary>
        /// <returns></returns>
        private bool getModify()
        {
            string modify = this.FileConfig.IniReadValue("modify", "modify");
            //  return (Boolean)modify;
            return Convert.ToBoolean(modify);
        }
        /// <summary>
        /// 读取文档
        /// </summary>
        /// <returns></returns>
        private string fileContent()
        {
            StreamReader sr = new StreamReader(this.FileErr, Encoding.Default);
            string FileContent;
            FileContent = sr.ReadToEnd();
            sr.Close();
            return FileContent;
        }


        /// <summary>
        /// 写入文档
        /// </summary>
        /// <param name="id"></param>
        private void fileWriter(string id)
        {

            if (!System.IO.Directory.Exists(this.File + "/Errlog"))
            {
                Directory.CreateDirectory(this.File + "/Errlog");
            }

            StreamWriter log = new StreamWriter(this.FileErr, true);
            log.WriteLine("," + id);
            log.Close();
        }

    }
}
