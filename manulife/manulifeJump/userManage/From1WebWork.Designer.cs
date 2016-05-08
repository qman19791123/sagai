using Newtonsoft.Json;
using Newtonsoft.Json.Converters;
using System;
using System.Data;
using System.Text;
using System.Windows.Forms;
namespace userManage
{
    partial class Form1
    {
        public string _SQl(string sql)
        {

            db tdb = new db();
            DataSet rs = tdb.dbfile(sql);
            try
            {
                string result = JsonConvert.SerializeObject(rs.Tables, new DataTableConverter());
                return result;
            }
            catch (Exception e)
            {
                return e.ToString();
            }
        }
        public void Message(string t = "")
        {
            MessageBox.Show(t);
        }
        public string _Jquery
        {
            get
            {
                return zy("js.jquery.js");
            }
        }

        public string _Css
        {
            get
            {
                return zy("js.css.css");
            }
        }
        public void showWin(int id)
        {
            this.groupBox1.Text = "修改用户";
            this.panel1.Hide();
            this.panel2.Show();
            this.panel3.Hide();
            db tdb = new db();
            string sql = "select * from [user] where [id] ={0}";
            sql = string.Format(sql, id);
            DataRow rs = tdb.dbfile(sql).Tables[0].Rows[0];
            TTAddUserSelect[0].Text = TAddUserIdentityConfig[(int)rs["identity"]];
            int zf = 65 + (int)rs["classify"] - 1;
            string word = char.ConvertFromUtf32(zf);
            TTAddUserSelect[1].Text = word;
            TAddUser[0].Text = rs["user"].ToString();
            TAddUser[1].Text = rs["password"].ToString();
            TIsUpdate = true;
            TUserId = id;
            TSubmit[1].Text = "修改";
        }
        public void del(int id)
        {
            db tdb = new db();
            var h = MessageBox.Show("确认删除", "提示", MessageBoxButtons.YesNo, MessageBoxIcon.Warning);
            if (h.ToString().ToUpper() == "YES")
            {
                try
                {
                    string sql = "select classify  from [user] where [id] ={0}";
                    sql = string.Format(sql, id);
                    int t = int.Parse(tdb.dbfile(sql).Tables[0].Rows[0]["classify"].ToString());

                    sql = "delete  from [user] where [id] ={0}";
                    sql = string.Format(sql, id);
                    tdb.dbfile(sql);
                    MessageBox.Show("删除成功");

                    Class_ = t;
                    TWebBrowser.DocumentText = this.zy("js.index.html");
                    this.panel1.Show();
                    this.panel2.Hide();
                    this.panel3.Hide();
                }
                catch { }
            }

        }
    }
}
