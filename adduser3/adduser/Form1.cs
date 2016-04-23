using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using Newtonsoft.Json;
using Newtonsoft.Json.Converters;
using System.Reflection;
using System.IO;
using System.Security.Permissions;
using JumpAndJump;

namespace adduser
{
    [PermissionSet(SecurityAction.Demand, Name = "FullTrust")]
    [System.Runtime.InteropServices.ComVisibleAttribute(true)]

    public partial class Form1 : Form
    {

        private JumpAndJump.Jump thisJump = null;

        TextBox[] T_ = new TextBox[15];

        Label[] L_ = new Label[15];

        private string File = Application.StartupPath;

        private bool isAdminLogIn_ = false;

        private int Groupid = 1;

        private bool NotOff = false;

        public int _id = 0;

        public string isfromtitle = "BOC Automatic";

        private string[] inputname = { "China Equity", "Hong Kong Equity", "Japan Equity", 
                                     "Asia Equity", "Global Equity", "CSI HK 100 Tracker",
                                     "European Index Tracking", "North America Index Tracking",
                                     "Growth" ,"Balanced","Stable","Bond","RMB && HKD Money Market","MP Conservative"
                                 };

        private IniClass ini_ = null;

        private IniClass adminini_ = null;

        public Form1()
        {
            InitializeComponent();
        }

        private void Form1_Load(object sender, EventArgs e)
        {
            this.thisJump = new Jump();

            string Errlog = System.Configuration.ConfigurationManager.AppSettings["Errlog"].ToString();
            string FileErr = this.File + @"\Errlog\Err.text";

            string FromConfig = System.Configuration.ConfigurationManager.AppSettings["FromConfig"].ToString();
            this.ini_ = new IniClass(File + FromConfig);

            string AdminConfig = System.Configuration.ConfigurationManager.AppSettings["AdminConfig"].ToString();
            this.adminini_ = new IniClass(File + AdminConfig);

            


            //标题设置
            this.Text = this.isfromtitle + " -账户信息";
            //程序出错模块
            this.systemErrModel(FileErr);
            // form 样式设置
            this.formStyle();
            /*区域设置*/
            // panel1 样式设置
            this.panel1Style();
            // panel2 样式设置
            this.panel2Style();
            // panel3 样式设置
            this.panel3Style();
            // panel4 样式设置
            this.panel4Style();

            this.panel1.Show();
            this.panel2.Hide();
            this.panel3.Hide();
            this.panel4.Hide();

            // 用户登录样式设置
            this.userLoginStyle();
            // 用户组选择样式设置
            this.userGroupSelectStyle();
            // 用户组选择样 - 下来菜单样式设置
            this.comboBoxStyle();
            // 用户组选择样 - 输入框样式设置
            this.TextBoxStyle();
            //浏览器设置
            this.webBrowserStyle();

        }

        private void linkLabel1_LinkClicked(object sender, LinkLabelLinkClickedEventArgs e)
        {
            this.panel1.Show();
            this.panel2.Hide();
            this.panel3.Hide();
            this.panel4.Hide();

            this.Text = this.isfromtitle + " - 账户信息";
        }

        private void linkLabel2_LinkClicked(object sender, LinkLabelLinkClickedEventArgs e)
        {
            this.panel2.Show();
            this.panel1.Hide();
            this.panel3.Hide();
            this.panel4.Hide();
            this.Text = this.isfromtitle + " - 自动下单";
        }

        private void button1_Click(object sender, EventArgs e)
        {
            // 用户们都是大牛谁知道 会给你个什么 值呀？？
            float p = 0;
            for (int i = 0; i < T_.Count(); ++i)
            {
                if (!string.IsNullOrEmpty(T_[i].Text.ToString()))
                {
                    //float
                    p += float.Parse(T_[i].Text.ToString());
                }
            }
            if (p != 100)
            {
                MessageBox.Show("基金百分比总和需为100", "警告提示", MessageBoxButtons.OK, MessageBoxIcon.Warning);
            }
            else
            {
                //var h = MessageBox.Show("是否设置完成，启动自动提交表单轮询事物？\n如果选择开始启动、在此期间不要关闭窗口或强行退出程序！", "提示", MessageBoxButtons.YesNo, MessageBoxIcon.Warning);
                var h = MessageBox.Show("请再次确认用户组和基金分类！", "提示", MessageBoxButtons.YesNo, MessageBoxIcon.Warning);
                if (h.ToString().ToUpper() == "YES")
                {
                    // MessageBox.Show("开始启动事物！为了保证正确，在此期间请不要关闭窗口或强行退出！", "警告提示", MessageBoxButtons.OK, MessageBoxIcon.Warning);

                    for (int i = 0; i < T_.Count(); ++i)
                    {
                        this.ini_.IniWriteValue("InputContent", "F_" + i, T_[i].Text.ToString());
                    }
                    this.ini_.IniWriteValue("userKey", "key", this.comboBox1.Text);

                    this.ini_.IniWriteValue("modify", "modify", checkBox1.Checked.ToString());

                    this.openJump();
                }
                // MessageBox.Show( ini_.IniReadValue("InputContent","F_0"));
            }
        }

        private void Form1_FormClosing(object sender, FormClosingEventArgs e)
        {
            NotOff = this.thisJump.NotOff;

            if (NotOff)
            {
                var d = MessageBox.Show("在运行中不能关闭程序", "提示", MessageBoxButtons.YesNo, MessageBoxIcon.Question);
                if (d.ToString() == "No")
                {
                    e.Cancel = true;
                }
            }
        }

        private void linkLabel3_LinkClicked(object sender, LinkLabelLinkClickedEventArgs e)
        {

            if (this.linkLabel3.Text == "管理员登出")
            {
                this.isAdminLogIn_ = false;
                this.linkLabel3.Text = "管理员登录";
                this.webBrowser1.Navigate(Application.StartupPath + "/user.html");

                this.panel2.Hide();
                this.panel1.Show();
                this.panel3.Hide();
                this.panel4.Hide();
                //MessageBox.Show("管理员退出的成功");
                return;

            }

            this.panel2.Hide();
            this.panel1.Hide();
            this.panel3.Hide();
            this.panel4.Show();
            this.Text = this.isfromtitle + " - 管理员登录";
        }

        private void button2_Click(object sender, EventArgs e)
        {

            //获取用户名和密码
            string pass = adminini_.IniReadValue("admin", "pass");


            string name = adminini_.IniReadValue("admin", "name");

            if (string.IsNullOrEmpty(pass) && string.IsNullOrEmpty(name))
            {
                MessageBox.Show("用户名或密码不正确", "提示");
                return;
            }
            else
            {

                if (this.textBox1.Text == name && this.textBox2.Text == pass)
                {
                    this.isAdminLogIn_ = true;
                    this.panel2.Hide();
                    this.panel1.Show();
                    this.panel3.Hide();
                    this.panel4.Hide();
                    this.linkLabel3.Text = "管理员登出";
                    this.webBrowser1.Navigate(Application.StartupPath + "/user.html");
                }
                else
                {
                    MessageBox.Show("用户名或密码不正确", "提示");
                }
            }
        }

        /// <summary>
        /// 设置Textbox 数字
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void mytextbok_KeyPress(object sender, KeyPressEventArgs e)
        {
            try
            {
                int kc = (int)e.KeyChar;
                if ((kc < 48 || kc > 57) && kc != 8)
                {
                    e.Handled = true;
                }
            }
            catch (Exception)
            {
            }
        }
    }
}
