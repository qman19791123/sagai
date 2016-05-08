using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Data;
using System.Data.OleDb;
using System.Data.SqlClient;
using System.Configuration;

namespace userManage
{
    class db
    {
        private OleDbDataAdapter oda = new OleDbDataAdapter();

        public DataSet dbfile(string sql)
        {

            OleDbDataAdapter da;
            //SqlConnection conn;
            OleDbConnection conn;
            DataSet ds = new DataSet();
            try
            {
           
                var dataConfig = System.Configuration.ConfigurationManager.AppSettings["dataConfig"].ToString();

                conn = new OleDbConnection(dataConfig);
                da = new OleDbDataAdapter(sql, conn);
                if (conn.State == ConnectionState.Closed)
                {
                    conn.Open();
                }
                da.Fill(ds);
                conn.Close();
                da.Dispose();
                conn.Dispose();
            }
            catch
            {

                return null;
            }
            return ds;
        }
    }
}
