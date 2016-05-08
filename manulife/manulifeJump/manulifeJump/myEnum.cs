using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;

namespace manulifeJump
{
    public enum IDcard
    {
        radio1,
        radio2,
    }




    public static class userCard
    {
        public static string isUserCard(int ts)
        {//Afternoon
            Array t = Enum.GetValues(typeof(IDcard));
            return t.GetValue(ts).ToString();
        }
    }
}
