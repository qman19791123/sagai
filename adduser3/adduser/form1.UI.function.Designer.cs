using System;
using System.Collections.Generic;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace adduser
{
    partial class Form1
    {

        private void formStyle()
        {
            this.Width = 1024;
            this.Height = 680;
        }
        private void panel1Style()
        {
            this.panel1.Width = this.Width;
            this.panel1.Left = (this.Width / 2) - this.panel1.Width / 2;
            this.panel1.Height = this.Height - (this.Height / 8);
        }

        private void panel2Style()
        {
            this.BackColor = Color.FromArgb(255, 255, 255);
            this.panel2.BackColor = this.BackColor;
            this.panel2.Width = this.Width - 100;
            this.panel2.Height = this.panel1.Height;
            this.panel2.Top = (this.Height / 2) - this.panel2.Height / 2 + 20;
            this.panel2.Left = (this.Width / 2) - this.panel2.Width / 2;
        }


        private void panel3Style()
        {
            this.panel3.BackColor = Color.FromArgb(0, 0, 0);
            this.panel3.Width = this.panel1.Width;
            this.panel3.Height = this.panel1.Height;
            this.panel3.Top = (this.Height / 2) - this.panel3.Height / 2;
            this.panel3.Left = (this.Width / 2) - this.panel3.Width / 2;

        }


        private void panel4Style()
        {
            this.panel4.Width = 443;
            this.panel4.Height = 300;
            this.panel4.Left = (this.Width / 2) - this.panel4.Width / 2;
            this.panel4.Top = (this.Height / 2) - this.panel4.Height / 2;

        }

        private void userGroupSelectStyle()
        {


            this.label1.Top = 5;
            this.label1.Left = 0;
            this.label1.Height = this.label1.Height;
            this.label1.Text = "用户组选择";


            this.groupBox1.Text = "基金分类选择";
            this.groupBox1.Top = this.label1.Top + this.label1.Height + 30;
            this.groupBox1.Left = 0;
            this.groupBox1.Width = this.panel2.Width;
            this.groupBox1.Height = this.panel2.Height - 200;

            /*提交按钮设置*/
            this.button1.Top = this.panel1.Height - this.button1.Height - 80;
            this.button1.Left = (this.Width / 2) + this.button1.Width * 3;
            this.button1.Text = "开始";


            /*下拉菜单设置*/
            this.comboBox1.Top = this.label1.Top - 3;
            this.comboBox1.Left = this.label1.Left + label1.Width + 5;

            /*Modify 单选按钮*/
            checkBox1.Text = "Modify";
            checkBox1.Left = comboBox1.Width + comboBox1.Left + 40;
            checkBox1.Top = comboBox1.Top + 5;

        }
        private void userLoginStyle()
        {
            this.groupBox2.Width = this.panel4.Width - 3;
            this.groupBox2.Height = this.panel4.Height - 50;
            this.groupBox2.Top = 0;
            this.groupBox2.Text = "管理员登录";


            this.label3.Top = this.textBox2.Top = (this.label2.Top = this.textBox1.Top = 80) + this.textBox1.Height + 20;
            this.label3.Left = this.label2.Left = this.textBox2.Left = this.textBox1.Left = 100;
            this.label3.Left = this.label2.Left = this.label2.Left - 60;

            this.textBox2.Height = this.textBox1.Height = 35;
            this.textBox2.Width = this.textBox1.Width = 250;

            this.button2.Left = this.groupBox2.Width - 100;
            this.button2.Top = this.groupBox2.Height - 50;


            this.label2.Text = "用户名";
            this.label3.Text = "密码";
            this.button2.Text = "登录";

            this.linkLabel1.Text = "账户信息";
            this.linkLabel2.Text = "自动下单";
            this.linkLabel3.Text = "管理员登录";
        }
        private void comboBoxStyle()
        {

            System.Collections.ArrayList list = new System.Collections.ArrayList();
            ////输出26 个字母
            for (int i = 65; i < 65 + 26; i++)
            {
                list.Add(((char)i).ToString().ToUpper());
            }
            comboBox1.DataSource = list;

        }

        private void TextBoxStyle()
        {
            try
            {
                for (int y = 1, x = 0, i = 0;
                    i < 20;
                    y = ((i + 1) % 2 == 0) ? y += 1 : y,
                    x = (x < 1) ? x += 1 : 0,
                    ++i)
                {

                    T_[i] = new TextBox();
                    L_[i] = new Label();
                    T_[i].KeyPress += new System.Windows.Forms.KeyPressEventHandler(this.mytextbok_KeyPress);
                    T_[i].MaxLength = 3;

                    L_[i].Text = this.inputname[i].ToString();
                    T_[i].Width = 180;
                    L_[i].Width = 180;
                    T_[i].Left = this.label1.Left + ((T_[i].Width + 220) * x + 220);
                    L_[i].Left = T_[i].Left - 200;

                    L_[i].Top = this.label1.Top + ((this.label1.Height + 30) * y);
                    T_[i].Top = L_[i].Top - 3;

                    this.groupBox1.Controls.Add(T_[i]);
                    this.groupBox1.Controls.Add(L_[i]);
                }
            }
            catch
            {

            }
        }

        private void webBrowserStyle()
        {
            webBrowser1.IsWebBrowserContextMenuEnabled = false;
            webBrowser1.ScriptErrorsSuppressed = true; //禁用错误脚本提示   
            webBrowser1.AllowWebBrowserDrop = false;//禁止拖拽
            this.webBrowser1.Navigate(this.File + "/user.html");
            webBrowser1.ObjectForScripting = this;
        }


        private void systemErrModel(string FileErr)
        {
            if (System.IO.File.Exists(FileErr))
            {
                this.openJump();
            }
        }


        private void openJump()
        {
            this.panel1.Hide();
            this.panel2.Hide();
            this.panel4.Hide();
            this.panel3.Show();
            this.linkLabel1.Enabled = false;
            this.linkLabel2.Enabled = false;
            this.linkLabel3.Enabled = false;

            this.thisJump.FormBorderStyle = FormBorderStyle.None; // 无边框

            this.thisJump.TopLevel = false;                     // 不是最顶层窗体
            this.thisJump.Top = 0;
            this.thisJump.Left = 0;
            this.thisJump.Width = this.panel3.Width;
            this.thisJump.Height = this.panel3.Height;

            panel3.Controls.Add(this.thisJump);             // 添加到 Panel中
            this.thisJump.Show();                           // 显示         
        }

    }
}
