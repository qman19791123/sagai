using Microsoft.Win32;
using System;
using System.Collections.Generic;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace adduser
{
    partial class Form1
    {

        public void isIE()
        {
            string FEATURE_BROWSER_EMULATION = @"Software\Microsoft\Internet Explorer\Main\FeatureControl\FEATURE_BROWSER_EMULATION";
            string FEATURE_GPU_RENDERING = @"Software\Microsoft\Internet Explorer\Main\FeatureControl\FEATURE_GPU_RENDERING";
            string exename = Path.GetFileName(Application.ExecutablePath);
            using (RegistryKey IE = Registry.LocalMachine.CreateSubKey("software\\Microsoft\\Internet Explorer"))
            using (RegistryKey regkey0 = Registry.CurrentUser.CreateSubKey(FEATURE_GPU_RENDERING))
            using (RegistryKey regkey1 = Registry.CurrentUser.CreateSubKey(FEATURE_BROWSER_EMULATION))
            {
                int p = int.Parse(IE.GetValue("svcVersion").ToString().Split(char.Parse("."))[0]);
                int t = 8888;


                if (p <= 9 && p > 6)
                {
                    t = p * 1111;
                }
                else if (p >= 10)
                {
                    t = (p * 1000) + 1;
                }

                //
                regkey1.SetValue(exename, t, RegistryValueKind.DWord);
                regkey0.SetValue(exename, 1, RegistryValueKind.DWord);
                regkey1.Close();
                regkey0.Close();
                IE.Close();
            }
        }

        public void isIcon()
        {
            this.Icon =  System.Drawing.Icon.ExtractAssociatedIcon("manulife");
            
        }

    }

   
}
