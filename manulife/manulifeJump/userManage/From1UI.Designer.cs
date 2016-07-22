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

namespace userManage
{
    partial class Form1
    {

        public void addpo()
        {
            int i = 0;
            int py = 0, pt = 50;
            Dlists = Orders.Xmldata();
            int Ptext = Dlists.Count();
            TTAddUserSelect[2] = new ComboBox();
            TTAddUserSelect[2].KeyPress += new KeyPressEventHandler(this.ComboBoxno);
            TOrdersText = new TextBox[Ptext + 1];
            TOrdersLabelText = new Label[Ptext + 2];

            TOrdersLabelText[Ptext + 1] = new Label();
            TOrdersLabelText[Ptext + 1].Text = "分组选择";
            TOrdersLabelText[Ptext + 1].Location = new System.Drawing.Point(50, 30);
            TOrdersLabelText[Ptext + 1].Width = 60;
            this.groupBox2.Controls.Add(TOrdersLabelText[Ptext + 1]);


            for (int zfi = 0; zfi < 26; zfi++)
            {
                int zf = 65 + zfi;
                string word = char.ConvertFromUtf32(zf);
                TTAddUserSelect[2].Items.Add(word);

                TTAddUserSelect[2].Location = TOrdersLabelText[Ptext + 1].Location;
                TTAddUserSelect[2].Left += TOrdersLabelText[Ptext + 1].Width + 3;
                TTAddUserSelect[2].Top -= 4;
            }


            this.groupBox2.Controls.Add(TTAddUserSelect[2]);

            foreach (xmldb.lists d in Dlists)
            {
                int pi = (i < 1 ? 0 : i - 1);
                TOrdersLabelText[i] = new Label();

                if (i % 3 == 0)
                {
                    pt = 50;
                    py = (TOrdersLabelText[pi].Height + TOrdersLabelText[pi].Top) + 15;
                    TOrdersLabelText[i].Location = new System.Drawing.Point(pt, py);
                }
                else
                {
                    pt += 300;
                    TOrdersLabelText[i].Location = new System.Drawing.Point(pt, py);
                }

                TOrdersText[i] = new TextBox();
                TOrdersText[i].Left = TOrdersLabelText[i].Width + TOrdersLabelText[i].Left;
                TOrdersText[i].Top = TOrdersLabelText[i].Top - 4;
                TOrdersText[i].Width = 150;

                TOrdersText[i].KeyUp += new KeyEventHandler(this.mytextbok_KeyPress);
                TOrdersText[i].KeyPress += new System.Windows.Forms.KeyPressEventHandler(this.mytextbok_KeyPress);
                TOrdersText[i].MaxLength = 3;
                TOrdersLabelText[i].Text = d.Content.ToString();
                this.groupBox3.Controls.Add(TOrdersText[i]);
                this.groupBox3.Controls.Add(TOrdersLabelText[i]);
                ++i;
            }
        }

        public void IsLogin()
        {

            Label[] LoginTag = new Label[2];

            this.Login[1] = new TextBox();
            this.Login[0] = new TextBox();

            this.Login[1].PasswordChar = char.Parse("*");
            this.TSubmit[3] = new Button();
            this.TSubmit[3].Text = "提交";



            LoginTag[0] = new Label();
            LoginTag[1] = new Label();

            LoginTag[0].Text = "用户名 ";
            LoginTag[1].Text = "密码 ";

            Login[0].Width = Login[1].Width = 350;
            LoginTag[0].Location = Login[0].Location = new Point(this.Width / 3, 80);
            LoginTag[1].Location = Login[1].Location = new Point(this.Width / 3, 130);
           
            LoginTag[0].Left = LoginTag[1].Left -= 100;

            TSubmit[3].Location = new Point(Login[0].Left + Login[0].Width / 2 - TSubmit[3].Width / 2, 180);


            TSubmit[3].Click += new EventHandler(this.TSubmit3_login);
            this.panel6.Controls.Add(Login[1]);
            this.panel6.Controls.Add(Login[0]);
            this.panel6.Controls.Add(LoginTag[1]);
            this.panel6.Controls.Add(LoginTag[0]);
            this.panel6.Controls.Add(TSubmit[3]);
        }

      




        //private void Login1_Leave(object sender, EventArgs e)
        //{
        //    Login[1].PasswordChar = char.Parse("*"); //设置密码框显示字符为*
        //    if (Login[1].Text == "请输入密码")
        //    {
        //        Login[1].Text = "";
        //    }
        //}

        //private void Login1_Enter(object sender, EventArgs e)
        //{
        //    if (Login[1].Text == "")
        //    {
        //        Login[1].Text = "请输入密码";
        //        Login[1].PasswordChar = '\0'; //清空PasswordChar设置
        //    }

        //}


        public void EditUI()
        {
            for (int i = 0; i <= 1; i++)
            {
                int pi = (i < 1 ? 0 : i - 1);
                TAddUserTag[i] = new Label();
                TAddUser[i] = new TextBox();
                TTAddUserSelect[i] = new ComboBox();
                //TTAddUserSelect[i].KeyPress += new KeyPressEventHandler(this.TTAddUserSelectNO);
                if (i == 0)
                {
                    TAddUserTag[i].Location = new System.Drawing.Point(50, 80);
                }
                else
                {
                    TAddUserTag[i].Location = new System.Drawing.Point(50, (TAddUser[pi].Height + TAddUser[pi].Top) + 25);
                }

                switch (i)
                {
                    case 0:
                        TAddUserTag[2] = new Label();
                        TAddUserTag[2].Location = new System.Drawing.Point(50, 175);
                        TAddUserTag[2].Text = TAddUserConfig[2];
                        TAddUserTag[2].Width = 80;
                        //TTAddUserSelect[i].DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList;
                        TTAddUserSelect[i].Width = 150;
                        TTAddUserSelect[i].Items.Add(TAddUserIdentityConfig[0]);
                        TTAddUserSelect[i].Items.Add(TAddUserIdentityConfig[1]);
                        TTAddUserSelect[i].Location = TAddUserTag[2].Location;
                        TTAddUserSelect[i].Left += TAddUserTag[2].Width + 15;


                        this.groupBox1.Controls.Add(TAddUserTag[2]);
                        break;
                    case 1:
                        TAddUserTag[3] = new Label();
                        TAddUserTag[3].Location = TAddUserTag[2].Location;
                        TAddUserTag[3].Top += TAddUserTag[2].Height + 25;
                        TAddUserTag[3].Text = TAddUserConfig[3];
                        TAddUserTag[3].Width = 80;
                        this.groupBox1.Controls.Add(TAddUserTag[3]);


                        // TTAddUserSelect[i].DropDownStyle = System.Windows.Forms.ComboBoxStyle.DropDownList;
                        TTAddUserSelect[i].Width = 150;
                        for (int zfi = 0; zfi < 26; zfi++)
                        {
                            int zf = 65 + zfi;
                            string word = char.ConvertFromUtf32(zf);
                            TTAddUserSelect[i].Items.Add(word);
                            TTAddUserSelect[i].Location = TAddUserTag[3].Location;
                            TTAddUserSelect[i].Left += TAddUserTag[3].Width + 15;
                        }

                        break;
                }
                TAddUserTag[i].Width = 80;
                TAddUser[i].Width = 250;
                TAddUser[i].Location = TAddUserTag[i].Location;
                TAddUser[i].Left += TAddUserTag[i].Width + 15;

                TAddUserTag[i].Text = TAddUserConfig[i];
                this.groupBox1.Controls.Add(TAddUser[i]);
                this.groupBox1.Controls.Add(TAddUserTag[i]);
                this.groupBox1.Controls.Add(TTAddUserSelect[i]);
            }
        }

    }
}
