using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.IO;
using System.Linq;
using System.Reflection;
using System.Security.Permissions;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
using JumpJump;

namespace userManage
{

    [PermissionSet(SecurityAction.Demand, Name = "FullTrust")]
    [System.Runtime.InteropServices.ComVisibleAttribute(true)]
    public partial class Form1 : Form
    {

        public int Class_ = 0;
        public string Query_ = null;

        public Form1()
        {
            InitializeComponent();
        }

        Boolean TIsUpdate = false;
        WebBrowser TWebBrowser = new WebBrowser();
        LinkLabel[] TButton = new LinkLabel[31];
        TextBox TQuery = new TextBox();
        TextBox[] TAddUser = new TextBox[2];
        Label[] TAddUserTag = new Label[4];
        ComboBox[] TTAddUserSelect = new ComboBox[3];
        Button[] TSubmit = new Button[6];
        int TUserId = 0;
        TextBox[] TOrdersText = null;
        Label[] TOrdersLabelText = null;

        xmldb Orders = new xmldb();
        List<xmldb.lists> Dlists = null;

        string[] TAddUserConfig = new string[] { "用户名", "密　码", "登录方式", "分组" };
        string[] TAddUserIdentityConfig = new string[] { "身份证", "用戶名" };


        private string File = Application.StartupPath;

        JumpJump.JumpJump JumpJump_JumpJump = new JumpJump.JumpJump();


        private void Form1_Load(object sender, EventArgs e)
        {
            

            Color t = Color.FromArgb(255, 255, 255);
            this.BackColor = t;
            this.Size = new System.Drawing.Size(1024, 623);
            this.panel3.Size = this.panel2.Size = this.panel1.Size = new System.Drawing.Size(this.Width - 20, this.Size.Height - 120);
            this.panel4.Location = new System.Drawing.Point(0, 45);
            this.panel4.Size = new System.Drawing.Size(this.Width, 30);
            this.panel3.Location = this.panel2.Location = this.panel1.Location = new System.Drawing.Point(0, 80);
            this.groupBox1.Dock = DockStyle.Fill;
            this.panel3.BackColor = this.panel2.BackColor = this.panel1.BackColor = t;

            this.panel5.Location = new System.Drawing.Point(0, 0);
            this.panel5.Size = this.Size;


            this.panel3.Top = this.panel2.Top -= 30;

            TWebBrowser.Size = this.panel1.Size;
            TWebBrowser.Dock = DockStyle.Fill;
            TWebBrowser.IsWebBrowserContextMenuEnabled = false;
            TWebBrowser.ScriptErrorsSuppressed = true; //禁用错误脚本提示   
            TWebBrowser.AllowWebBrowserDrop = false;//禁止拖拽
            TWebBrowser.Location = new System.Drawing.Point(0, 0);
            TWebBrowser.ObjectForScripting = this;
            TWebBrowser.DocumentText = this.zy("js.index.html");

            for (int i = 0; i < 26; i++)
            {
                int zf = 65 + i;
                string word = char.ConvertFromUtf32(zf);
                TButton[i] = new LinkLabel();
                TButton[i].Text = word;
                TButton[i].Width = 20;
                TButton[i].Left = (TButton[i].Width + 3) * i + 10;
                TButton[i].Font = new Font("微软雅黑", 10, FontStyle.Bold);
                TButton[i].LinkColor = Color.FromArgb(80, 80, 80);
                TButton[i].ActiveLinkColor = Color.FromArgb(150, 150, 150);
                TButton[i].Click += new System.EventHandler(this.TButton_Click);
                this.panel4.Controls.Add(TButton[i]);
            }


            TButton[26] = new LinkLabel();
            TButton[26].Text = "账户系管理";
            TButton[26].Top = 10;
            TButton[26].Left = 10;
            TButton[26].Width = 80;
            TButton[26].Click += new EventHandler(this.TSubmit_Return_Click);
            this.Controls.Add(TButton[26]);

            TButton[27] = new LinkLabel();
            TButton[27].Text = "自动下单";
            TButton[27].Click += new System.EventHandler(this.TButton_Orders);
            TButton[27].Top = 10;
            TButton[27].Left = 100;
            TButton[27].Width = 80;
            this.Controls.Add(TButton[27]);

            TButton[28] = new LinkLabel();
            TButton[28].Text = "管理员登录";

            TButton[28].Top = 10;
            TButton[28].Left = 200;
            TButton[28].Width = 80;
            this.Controls.Add(TButton[28]);


            TQuery = new TextBox();
            TQuery.Width = 250;
            TQuery.Left = this.Width - TQuery.Width - 150;
            this.panel4.Controls.Add(TQuery);

            TButton[29] = new LinkLabel();
            TButton[29].Text = "查询";
            TButton[29].Width = 35;
            TButton[29].Location = TQuery.Location;
            TButton[29].Left += TQuery.Width + 5;
            TButton[29].Click += new System.EventHandler(this.TButton_Query_Click);
            this.panel4.Controls.Add(TButton[29]);

            TButton[30] = new LinkLabel();
            TButton[30].Text = "添加用户";
            TButton[30].Location = TButton[29].Location;
            TButton[30].Left += TButton[29].Width + 10;
            TButton[30].Click += new System.EventHandler(this.TButton_AddUser_Click);
            this.panel4.Controls.Add(TButton[30]);



           

            TSubmit[0] = new Button();
            TSubmit[0].Text = "返回";
            TSubmit[0].Click += new System.EventHandler(this.TSubmit_Return_Click);
            TSubmit[0].Location = new System.Drawing.Point(450, 300);
            this.groupBox1.Controls.Add(TSubmit[0]);
            TSubmit[1] = new Button();
            TSubmit[1].Text = "提交";
            TSubmit[1].Click += new System.EventHandler(this.TSubmit_Submit_Click);
            TSubmit[1].Location = new System.Drawing.Point(550, 300);
            this.groupBox1.Controls.Add(TSubmit[1]);


            Dlists = Orders.Xmldata();

            this.panel1.Show();
            this.panel2.Hide();
            this.panel3.Hide();
            this.panel1.Controls.Add(TWebBrowser);
            this.panel4.Show();
            this.panel5.Hide();
            this.panel6.Hide();

            this.EditUI();
            this.addpo();
            

        }


        private void ComboBoxno(object sender, KeyPressEventArgs e)
        {
            try
            {
                int kc = (int)e.KeyChar;
                if (kc != 8)
                {
                    e.Handled = true;
                }
            }
            catch (Exception)
            {
            }
        }

        /// <summary>
        /// 自动下单 界面部分
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void TButton_Orders(object sender, EventArgs e)
        {
            LinkLabel a = (LinkLabel)sender;
          
            this.groupBox2.Text = a.Text;
            this.groupBox3.Text = "基金项";
            this.groupBox3.Top = 60;
            this.groupBox3.Height = this.groupBox2.Height - 60;
            this.groupBox3.Width = this.groupBox2.Width - 5;
         

            TSubmit[0] = new Button();
            TSubmit[0].Text = "返回";
            TSubmit[0].Click += new System.EventHandler(this.TSubmit_Return_Click);
            TSubmit[0].Location = new System.Drawing.Point(450, 400);
            this.groupBox3.Controls.Add(TSubmit[0]);


            TSubmit[2] = new Button();
            TSubmit[2].Text = "提交";
            TSubmit[2].Click += new System.EventHandler(this.TSubmit_Orders_Click);
            TSubmit[2].Location = new System.Drawing.Point(550, 400);
            this.groupBox3.Controls.Add(TSubmit[2]);

            this.panel1.Hide();
            this.panel2.Hide();
            this.panel3.Show();
            this.panel4.Hide();
            this.panel5.Hide();
            this.panel6.Hide();
        }
        /// <summary>
        /// 输入框设置
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void mytextbok_KeyPress(object sender, KeyEventArgs e)
        {
            TextBox T = (TextBox)sender;
            if (!string.IsNullOrEmpty(T.Text))
            {
                int IText = Convert.ToInt16(T.Text);
                if (IText > 100)
                {
                    goto err;
                }
            }

            float p = 0;
            for (int i = 0; i < TOrdersText.Count() - 1; ++i)
            {
                if (!string.IsNullOrEmpty(TOrdersText[i].Text.ToString()))
                {
                    //float
                    p += float.Parse(TOrdersText[i].Text.ToString());
                }
            }
            if (p > 100)
            {
                goto err;
            }
            return;
        err:
            MessageBox.Show("基金百分比总和需为100", "警告提示", MessageBoxButtons.OK, MessageBoxIcon.Warning);
            return;
        }
        /// <summary>
        /// 输入框设置
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
        /// <summary>
        /// 自动下单 数据提交
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void TSubmit_Orders_Click(object sender, EventArgs e)
        {
            string FromConfig = "/Config.ini";
            float p = 0;
            IniClass ini_ = new IniClass(File + FromConfig);
            string[] k = new string[TOrdersText.Count()];


            if (!string.IsNullOrEmpty(TTAddUserSelect[2].Text.ToString()))
            {
                byte[] array = System.Text.Encoding.ASCII.GetBytes(TTAddUserSelect[2].Text.ToString());
                int IClass = (short)(array[0]) - 65 + 1;
                ini_.IniWriteValue("IClass", "class", IClass.ToString());
            }
            else
            {
                MessageBox.Show("分组选择不能为空", "警告提示", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                //("","分组选择不能为空");
                return;
            }



            for (int i = 0; i < TOrdersText.Count() - 1; ++i)
            {
                if (!string.IsNullOrEmpty(TOrdersText[i].Text.ToString()))
                {
                    //float
                    p += float.Parse(TOrdersText[i].Text.ToString());
                }
            }
            if (p != 100)
            {
                MessageBox.Show("基金百分比总和需为100", "警告提示", MessageBoxButtons.OK, MessageBoxIcon.Warning);
                return;
            }

            for (int i = 0; i < TOrdersText.Count() - 1; ++i)
            {
                try
                {
                    ini_.IniWriteValue("InputContent", "F_" + i, TOrdersText[i].Text.ToString());

                }
                catch (Exception ex)
                {
                    MessageBox.Show(ex.ToString());
                    return;
                }
            }


            this.JumpJump_JumpJump.FormBorderStyle = FormBorderStyle.None;
            this.JumpJump_JumpJump.TopLevel = false;
            this.JumpJump_JumpJump.Location = new System.Drawing.Point(0, 0);

            this.JumpJump_JumpJump.Size = this.Size;

            panel5.Controls.Add(this.JumpJump_JumpJump);
            this.JumpJump_JumpJump.Show();

            this.panel1.Hide();
            this.panel2.Hide();
            this.panel3.Hide();
            this.panel4.Hide();
            this.panel5.Show();
            this.panel6.Hide();

        }

        /// <summary>
        /// 添加用户/修改用户 数据提交部分
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void TSubmit_Submit_Click(object sender, EventArgs e)
        {
            db tdb = new db();
            string TName = TAddUser[0].Text;
            string TPassword = TAddUser[1].Text;
            string TLogin = TTAddUserSelect[0].Text;
            string TClass = TTAddUserSelect[1].Text;
            string p = null;
            string sql = null;

            if (string.IsNullOrEmpty(TName))
            {
                MessageBox.Show(TAddUserConfig[0] + "不能为空");
                return;
            }
            if (string.IsNullOrEmpty(TPassword))
            {
                MessageBox.Show(TAddUserConfig[1] + "不能为空");
                return;
            }
            if (string.IsNullOrEmpty(TLogin))
            {
                MessageBox.Show(TAddUserConfig[2] + "不能为空");
                return;
            }
            if (string.IsNullOrEmpty(TClass))
            {
                MessageBox.Show(TAddUserConfig[2] + "不能为空");
                return;
            }

            byte[] array = System.Text.Encoding.ASCII.GetBytes(TClass);
            int IClass = (short)(array[0]) - 65 + 1;

            int ILogin = (TLogin == "身份证" ? 0 : 1);

            if (!TIsUpdate)
            {
                try
                {
                    sql = "select count(id) as ic from [user] where [user] ='{0}'";
                    sql = string.Format(sql, TName);
                    try
                    {
                        int rs = int.Parse(tdb.dbfile(sql).Tables[0].Rows[0]["ic"].ToString());
                        if (rs > 0)
                        {
                            MessageBox.Show("用户已存在");
                            return;
                        }
                    }
                    catch { return; }
                    sql = "INSERT INTO  [user] ([user],[password],[identity],[classify]) VALUES('{0}','{1}','{2}','{3}')";
                    sql = string.Format(sql, TName, TPassword, ILogin, IClass);
                    p = "添加成功";
                    Class_ = IClass;
                }
                catch
                {
                    MessageBox.Show("内容不完整");
                }
            }
            else
            {
                try
                {
                    sql = "update [user] set [user]='{0}' , [password]='{1}',[identity]='{2}',[classify]='{3}' where id = {4}";
                    sql = string.Format(sql, TName, TPassword, ILogin, IClass, TUserId);
                    p = "修改成功";
                    Class_ = IClass;
                }
                catch
                {
                    MessageBox.Show("内容不完整");
                }
            }
            try
            {
                tdb.dbfile(sql);
                MessageBox.Show(p);
            }
            catch
            {
                MessageBox.Show("程序出错");
            }
        }
        /// <summary>
        /// 显示首页
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void TSubmit_Return_Click(object sender, EventArgs e)
        {
            TIsUpdate = false;
            TWebBrowser.DocumentText = this.zy("js.index.html");
            this.panel1.Show();
            this.panel2.Hide();
            this.panel3.Hide();
            this.panel4.Show();
            this.panel5.Hide();
            this.panel6.Hide();
        }

        /// <summary>
        /// 添加用户 界面部分
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void TButton_AddUser_Click(object sender, EventArgs e)
        {

            LinkLabel a = (LinkLabel)sender;
            this.groupBox1.Text = a.Text;
            TSubmit[1].Text = "提交";
            for (int i = 0; i < TAddUser.Count(); i++)
            {
                TAddUser[i].Text = "";
                TTAddUserSelect[i].Text = "";
            }

            this.panel1.Hide();
            this.panel2.Show();
            this.panel3.Hide();
            this.panel4.Hide();
            this.panel5.Hide();
            this.panel6.Hide();
        }

        /// <summary>
        /// 查询操作部分
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void TButton_Query_Click(object sender, EventArgs e)
        {
            Query_ = TQuery.Text;
            TWebBrowser.DocumentText = this.zy("js.index.html");
        }
        /// <summary>
        /// 26 个分类按钮
        /// </summary>
        /// <param name="sender"></param>
        /// <param name="e"></param>
        private void TButton_Click(object sender, EventArgs e)
        {
            LinkLabel a = (LinkLabel)sender;

            byte[] array = System.Text.Encoding.ASCII.GetBytes(a.Text);
            int asciicode = (short)(array[0]) - 65 + 1;
            Class_ = asciicode;
            for (int i = 0; i < 26; i++)
            {
                TButton[i].LinkColor = Color.FromArgb(80, 80, 80);
            }
            a.LinkColor = Color.FromArgb(0, 150, 200);
            TWebBrowser.DocumentText = this.zy("js.index.html");
            this.panel1.Show();
            this.panel2.Hide();
            this.panel3.Hide();
            this.panel4.Show();
            this.panel5.Hide();
            this.panel6.Hide();

        }
        /// <summary>
        /// 静态模版页配置
        /// </summary>
        /// <param name="file"></param>
        /// <returns></returns>
        private string zy(string file)
        {
            Stream sm = Assembly.GetExecutingAssembly().GetManifestResourceStream("userManage." + file);
            byte[] bs = new byte[sm.Length];
            sm.Read(bs, 0, (int)sm.Length);
            sm.Close();
            UTF8Encoding con = new UTF8Encoding();
            string str = con.GetString(bs);
            return str;


        }
    }
}
