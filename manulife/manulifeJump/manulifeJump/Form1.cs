using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.Linq;
using System.Text;
using System.Threading;
using System.Threading.Tasks;
using System.Windows.Forms;
using IWshRuntimeLibrary;
namespace manulifeJump
{
    public partial class Form1 : Form
    {
        public Form1()
        {
            InitializeComponent();
        }
        private string File_ = Application.StartupPath;

        private void Form1_Load(object sender, EventArgs e)
        {
            System.Diagnostics.Process.Start("manulife.exe");

            bool b = System.IO.File.Exists(Environment.GetFolderPath(Environment.SpecialFolder.DesktopDirectory) + "//" + "manulife Automatic.lnk");

            if (!b)
            {


                string DesktopPath = System.Environment.GetFolderPath(System.Environment.SpecialFolder.Desktop);//得到桌面文件夹 
                IWshRuntimeLibrary.WshShell shell = new WshShell();
                IWshRuntimeLibrary.IWshShortcut shortcut = (IWshRuntimeLibrary.IWshShortcut)shell.CreateShortcut(DesktopPath + "\\manulife Automatic.lnk");
                shortcut.TargetPath = File_+"/manulife.exe";
                shortcut.Arguments = "";// 参数 
                shortcut.Description = "manulife";
                shortcut.WorkingDirectory = File_;
                shortcut.IconLocation = File_+"/manulife,0";//图标 
                shortcut.WindowStyle = 1;
                shortcut.Save();
            }

            this.Close();
        }
        //BOC Automatic.exe

        private void Form1_Resize(object sender, EventArgs e)
        {

        }



    }

}
