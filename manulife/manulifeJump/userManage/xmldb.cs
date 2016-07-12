using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Xml.Linq;

namespace userManage
{
    public class xmldb
    {
        public List<lists> Xmldata()
        {

            string path = "Data.xml";
            List<lists> TList = new List<lists>();
            XDocument doc = XDocument.Load(path);  
            var query = (from item in doc.Element("xml").Elements()
                         select new
                         {
                             TypeName = item.Value,
                         }).ToList();
            foreach (var item in query)
            {
                TList.Add(new lists { Content = item.TypeName.ToString() });
            }
            return TList;
        }
        public class lists
        {
            private string content;

            public string Content
            {
                get { return content; }
                set { content = value; }
            }
          

        }
    }
}
