﻿using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.IO;
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
        private int jumpOver = 0;


        private int[] cursor = new int[27];

        private string File_ = Application.StartupPath;
        private string FromConfig = "/Config.ini";
        private IniClass ini_ = null;
        private db db_ = null;
        private int dbCount_, cpid_ = 0;
        DataRowCollection Data_ = null;

        private Boolean ERRXXXXFUCK = false;


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
            db_ = new db();
            ini_ = new IniClass(File_ + FromConfig);
            string ini_IClass = ini_.IniReadValue("IClass", "class");

            string sql = "select * from [user] where classify = {0}";
            sql = string.Format(sql, ini_IClass);

            string filePath = this.File_ + "/overLog/over.log";
            if (System.IO.File.Exists(filePath))
            {
                string Content = this.fileContent(filePath).Substring(1);
                if (!string.IsNullOrWhiteSpace(Content))
                {
                    sql += " and  id not in(" + Content + ")";
                }
                
            }
            Data_ = db_.dbfile(string.Format(sql, ini_IClass)).Tables[0].Rows;
            dbCount_ = Data_.Count;
            

            for (int i = 0; i < cursor.Count(); ++i)
            {
                string F_ = ini_.IniReadValue("InputContent", string.Format("F_{0}", i));
                if (!string.IsNullOrEmpty(F_))
                {
                    cursor[i] = Convert.ToInt16(F_);
                }
            }

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
            try
            {

                if (webBrowser1.Document.Body.InnerText.IndexOf("您已於另一設備／瀏覽器登入宏利網站") > 0 || webBrowser1.Document.Body.InnerText.IndexOf("閣下已經過限定時間") > 0)
                {
                    ERRXXXXFUCK = true;
                    jumpOver = 0;
                    this.webBrowser1.Navigate(Url);
                    return;
                   
                }

                if (webBrowser1.Document.Body.InnerText.IndexOf("无法显示此页") > 0)
                {
                    MessageBox.Show("系统出现错误，将会立刻关闭程序");
                    string Erruser = string.Format("程序出现错误，错误的用户是\r\n编号：{1}\t名字：{0}\r\n", Data_[cpid_]["user"].ToString().Trim(), Data_[cpid_]["ID"].ToString().Trim());
                    this.fileWriter(Erruser, "/errLog/", "/System_Serious.log");
                    this.Close();
                    new System.Threading.Mutex(true, Application.ProductName).ReleaseMutex();
                    Application.Exit();
                    return;
                }
            }
            catch
            {

            }

            int userIdentity = Convert.ToInt16(Data_[cpid_]["identity"]);
            int h = 0;

            #region 登出
            if (IsCout && jumpOver >= 2)
            {
                foreach (HtmlElement j in webBrowser1.Document.GetElementsByTagName("a"))
                {
                    if (j.InnerText == "登出")
                    {
                        j.InvokeMember("click");
                        jumpOver = 0;
                        this.fileWriter("," + Data_[cpid_]["ID"].ToString().Trim(), "/overLog/", "over.log");
                        return;
                    }
                }
            }
            #endregion

            #region 回滚
            h = e.Url.ToString().IndexOf("ebs/cws_main_public.jsp?action=clogin_portal_logout");
            if (h > 0)
            {
                if (ERRXXXXFUCK)
                {
                    return;
               }
                sizeSelectedPol = -1;
                isOk = true;
                isClick = false;
                isSetOk = false;
                IsCout = false;
                if (dbCount_ - 1 > cpid_)
                {
                    cpid_++;
                }
                else
                {
                    MessageBox.Show("订单处理完毕");
                    string filePath = this.File_ + "/overLog/over.log";
                    File.Delete(filePath);

                    this.Close();
                    new System.Threading.Mutex(true, Application.ProductName).ReleaseMutex();
                    Application.Restart();
                    return;
                }
                this.webBrowser1.Navigate(Url);
            }
            #endregion

            #region 登录部分
            //  //wps/portal/cwscustomerlogin
            h = e.Url.ToString().IndexOf("wps/portal/cwscustomerlogin/!");

            if (ERRXXXXFUCK || h > 0)
            {
                ERRXXXXFUCK = false;
               // MessageBox.Show(Data_[cpid_]["user"].ToString().Trim());
                if (jumpOver <= 0)
                {
                
                    foreach (HtmlElement j in webBrowser1.Document.GetElementsByTagName("input"))
                    {
                        if (j.GetAttribute("type") == "radio" && j.Id == userCard.isUserCard(userIdentity))
                        {
                            j.SetAttribute("checked", "true");
                        }
                        if (j.Id == "user_id")
                        {
                            j.SetAttribute("value", Data_[cpid_]["user"].ToString().Trim());
                        }
                        if (j.Id == "mcnPin")
                        {
                            j.SetAttribute("value", Data_[cpid_]["password"].ToString().Trim());
                        }
                    }

                    foreach (HtmlElement j in webBrowser1.Document.GetElementsByTagName("a"))
                    {

                        if (!string.IsNullOrEmpty(j.InnerText) && j.InnerText.ToString() == "登入")
                        {
                            j.InvokeMember("click");
                            jumpOver = 1;
                        }
                    }

                    return;
                }
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

                try
                {
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
                }
                catch { }
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
                            jumpOver = 2;
                            j.InvokeMember("click");
                            return;
                        }
                    }
                }
                catch
                {

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


        /// <summary>
        /// 写入文档
        /// </summary>
        /// <param name="id"></param>
        private void fileWriter(string id, string folder, string err)
        {

            if (!System.IO.Directory.Exists(this.File_ + folder))
            {
                System.IO.Directory.CreateDirectory(this.File_ + folder);
            }

            System.IO.StreamWriter log = new System.IO.StreamWriter(this.File_ + folder + err, true);
            log.WriteLine(id);
            log.Close();
        }
        /// <summary>
        /// 读取文档
        /// </summary>
        /// <returns></returns>
        private string fileContent(string FileErr)
        {
            StreamReader sr = new StreamReader(FileErr, Encoding.Default);
            string FileContent;
            FileContent = sr.ReadToEnd();
            sr.Close();
            return FileContent;
        }
    }
}