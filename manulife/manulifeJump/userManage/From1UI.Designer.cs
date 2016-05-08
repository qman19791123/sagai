using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;
namespace userManage
{
    partial class Form1
    {

        public void EditUI()
        {
            for (int i = 0; i <= 1; i++)
            {
                int pi = (i < 1 ? 0 : i - 1);
                TAddUserTag[i] = new Label();
                TAddUser[i] = new TextBox();
                TTAddUserSelect[i] = new ComboBox();
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
