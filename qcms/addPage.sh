#!/bin/bash
# 文件设计方案
notfile=0
if [ ! -z "${1}" ] ;  then

    if [ ! -d "application/v" ]; then
        mkdir -p "application/v"
        chmod -R 777 "application"
    fi

    if [ ! -d "application/c" ]; then
        mkdir -p "application/c"
        chmod -R 777 "application"
    fi

    if [ ! -d "application/m" ]; then
        mkdir -p "application/m"
        chmod -R 777 "application"
    fi

# 1 视图文件
    Cout=""
    Cout="<html>"$'\n'
    Cout="$Cout<?php \$${1}Cout = \$this->content();?>"$'\n'
    Cout="$Cout<head><title><?php echo \$${1}Cout['title'];?></title></head>"$'\n'
    Cout="$Cout<body>"$'\n'
    Cout="$Cout<div><?php echo \$${1}Cout['content'];?></div>"$'\n'
    Cout="$Cout</body>"$'\n'
    Cout="$Cout</html>"
        if [ ! -f "application/v/V${1}.php" ]; then
            echo "${Cout}" >> "application/v/V${1}.php"
        else
            notfile=1
            echo "V${1}.php 文件已经存在"
        fi

# 2 控制层文件
    Cout=""
    Cout="<?php "$'\n'
    Cout="${Cout}class C${1} extends controllers {"$'\n'
   
    Cout=$Cout$'\t'"var \$news ;"$'\n'
    Cout=$Cout$'\t'"var \$newssubject ;"$'\n'
    Cout=$Cout$'\t'"var \$classify;"$'\n'

    Cout="${Cout}"$'\t'"public function __construct() {"$'\n'
    Cout="${Cout}"$'\t\t'"parent::__construct();"$'\n'
    Cout="$Cout"$'\t\t'"# 请安需要装载框架,加载框架越多将会越慢"$'\n'
    Cout="${Cout}"$'\t\t'"\$this->news=\$this->news();"$'\n'
     Cout="${Cout}"$'\t\t'"\$this->newssubject=\$this->newssubject();"$'\n'
    Cout="${Cout}"$'\t\t'"\$this->classify=\$this->classify();"$'\n'
    Cout="${Cout}"$'\t'"}"$'\n'

    Cout="${Cout}"$'\t'"public function index() {"$'\n'
    Cout="${Cout}"$'\t\t'"\$data=['title'=>'hello world','content'=>'This is the system information'];"$'\n'
    Cout="${Cout}"$'\t\t'"\$this->Cout(\$data);"$'\n'
    Cout="${Cout}"$'\t'"}"$'\n'


    Cout="${Cout}}"$'\n'

        if [ ! -f "application/c/C${1}.php" ]; then
            echo "${Cout}" >> "application/c/C${1}.php"
        else
            notfile=1
            echo "C${1}.php 文件已经存在"
        fi

# 3 模型层文件
    Cout=""
    Cout="<?php "$'\n'
    Cout="${Cout}class M${1} extends models {"$'\n'
    Cout="${Cout}"$'\t'"public function __construct() {"$'\n'
    Cout="${Cout}"$'\t\t'"parent::__construct();"$'\n'
    Cout="${Cout}"$'\t'"}"$'\n'
    Cout="${Cout}}"$'\n'
    
        if [ ! -f "application/m/M${1}.php" ]; then 
            echo "${Cout}" >> "application/m/M${1}.php"
        else
            notfile=1
            echo "M${1}.php 文件已经存在"
        fi

        #写入MVC 配置文件
        if [ ! -f "mvc.php" ] ;then
                Cout=""
                Cout="${Cout}<?php"$'\n'
                Cout="${Cout}\$qmanVMC['${1}']= '${1}.php';"
                echo "${Cout}" >> "mvc.php" 
        else
            if  [ "${notfile}" -eq 0 ] ;then
                Cout=""
                Cout="${Cout}\$qmanVMC['${1}']= '${1}.php';"
                echo "${Cout}" >> "mvc.php" 
            fi
        fi 


    else
        echo "格式不真确"
    fi 




    
    