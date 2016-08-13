using Newtonsoft.Json;
using Newtonsoft.Json.Converters;
using System;
using System.Data;
using System.Windows.Forms;
namespace adduser
{
    partial class Form1
    {
        public string _isWinData(string sql)
        {
            db t = new db();
            DataSet rs = t.dbfile(sql);
            try
            {
                string result = JsonConvert.SerializeObject(rs.Tables, new DataTableConverter());
                return result;
            }
            catch
            {
                return null;
            }
        }

        public bool _isAdminLogIn
        {
            get
            {
                return this.isAdminLogIn_;
            }
        }

        public void _isWinGroup(string GroupName, int Group)
        {
            Groupid = Group;
            // this.textBox1.Text = GroupName.ToUpper();
        }

        public void _isWinUp(int id)
        {
            _id = id;
            this.webBrowser1.Navigate(Application.StartupPath + "/user_add_or_update.html");
        }

        public void _isWinAddUser()
        {
            _id = 0;
            this.webBrowser1.Navigate(Application.StartupPath + "/user_add_or_update.html");
        }

        public void _isWinAddUpdate(string name, string userName, string userPasswd, string classs)
        {

            if (string.IsNullOrWhiteSpace(name))
            {
                MessageBox.Show("用户名不能为空", "提示");
                return;
            }
            if (string.IsNullOrWhiteSpace(userName))
            {
                MessageBox.Show("账户名不能为空", "提示");
                return;
            }
            if (string.IsNullOrWhiteSpace(userPasswd))
            {
                MessageBox.Show("账户密码不能为空", "提示");
                return;
            }
            if (string.IsNullOrWhiteSpace(classs.Trim()))
            {
                MessageBox.Show("分组不能为空", "提示");
                return;
            }

            if (_id > 0)
            {
                try
                {
                    string sql = @"UPDATE boc_user SET 
                name = '{0}' ,
                userName = '{1}', 
                userPasswd = '{2}', 
                class = '{3}'
                WHERE id = '{4}' ";
                    sql = string.Format(sql, name, userName, userPasswd, classs, _id);
                    _isWinData(sql);
                    MessageBox.Show("修改成功", "提示");
                    this.webBrowser1.Navigate(Application.StartupPath + "/user.html");
                }
                catch
                {
                    MessageBox.Show("修改失败", "提示");
                }
            }
            else
            {
                try
                {
                    string sql = @"INSERT INTO boc_user ([name],[userName],[userPasswd],[class]) VALUES ('{0}','{1}','{2}','{3}')";
                    sql = string.Format(sql, name, userName, userPasswd, classs, _id);
                    _isWinData(sql);
                    MessageBox.Show("添加成功", "提示");
                    this.webBrowser1.Navigate(Application.StartupPath + "/user.html");
                }
                catch
                {
                    MessageBox.Show("添加失败", "提示");
                }
            }
        }
        public bool _isWinDel(int id)
        {
            try
            {
                string sql = "DELETE FROM boc_user WHERE id =" + id;
                _isWinData(sql);
                this.webBrowser1.Refresh();
                MessageBox.Show("删除成功", "提示");
            }
            catch
            {
                MessageBox.Show("删除失败", "提示");
            }

            return true;
        }
        public void _isWinClose()
        {
            this.webBrowser1.Navigate(Application.StartupPath + "/user.html");
        }
    }
}