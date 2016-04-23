using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Data;
using System.Data.OleDb;
using System.Data.SqlClient;

namespace JumpAndJump
{
    class db
    {
        private OleDbDataAdapter oda = new OleDbDataAdapter();


        public DataSet dbfile(string sql)
        {
          
            SqlDataAdapter da;
           
            SqlConnection conn;
            DataSet ds = new DataSet();
            try
            {


                var dataConfig = System.Configuration.ConfigurationManager.AppSettings["dataConfig"].ToString();
                conn = new SqlConnection(dataConfig);

                da = new SqlDataAdapter(sql, conn);
                if (conn.State == ConnectionState.Closed)
                {
                    conn.Open();
                }
                da.Fill(ds);
                conn.Close();
            }
            catch
            {
                return null;
            }
            return ds;
        }
    }
}
