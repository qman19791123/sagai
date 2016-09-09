=============================================

<h1>文件拖取程式</h1>

* 请执行 run.py 文件 <br/>
* 执行方法 python run.py <br/>
* 后台执行方法 <br/>
1 chmod +x run.py (赋予执行权限)<br/>
2 nohup ./run.py & (使用 nohup 方式执行程序)<br/>
<h2> 1 文件 </h2>
	CollectionDocument.py				集合文档<br/>
	GetFiles.py					获取文件<br/>
	config.py					配置文件<br/>
	run.py						执行文件<br/>
<h2> 2 文件介绍 </h2>
	* 1 GetFiles.py <br/>
	用于采集服务器上的所有相关文件的地址<br/>
	* 2 CollectionDocument.py <br/>
	用于获取服务器上的文件并下载至本地<br/>
	* 3 config.py <br/>
	配置采集需要的信息 （如服务器名，密码...等）<br/>
	* 4 run.py<br/>
	执行程序<br/>

</h2> 3 代码介绍 </h2>
** 1 GetFiles.py **
``` python

	#!/usr/bin/python
	#coding=utf-8
	#引入包
	import os,sys,pexpect,time，config
	try:
		import cPickle as pickle
	except ImportError:
		import pickle
			
	#hots 服务器地址
	hots_ = config.hots_
	#user 服务器用户名
	user_ = config.user_
	#pass 服务器密码
	pass_= config.pass_
	# live make 临时使用
	lm =[]
	i=0
	j=0
	t=False

	
	#配置 config 文件夹 不存在时候建立
	if not os.path.exists('./config'):
		os.makedirs('%s/%s'%(os.getcwd(),'./config'))
	if not os.path.exists('./config/log.txt'):
		#以写方式开启日志文件
		fout = open ('./config/log.txt', "w")
	
		#平装服务连接链
		cmd = 'ssh %s@%s'%(user_,hots_)
		child = pexpect.spawn(cmd)
		#执行密码
		child.expect('.ssword:')
		child.sendline(pass_)
		#转入web文件夹地址
		child.sendline('cd /%s'%config.webFolder_)
		#拖取文件中相关文件全地址
		for fileFolder in config.fileFolder_:
			for fileFormat in config.fileFormat_:
			   child.sendline('find ./%s|grep %s'%(fileFolder,fileFormat))

		#退出服务器
		child.sendline('exit')
	
		# 将拖取到信息记录
		child.logfile = fout
		child.expect(pexpect.EOF)
		fout.close()

		# 转换格式去除部分信息写入dump文件中
		inputs = open('./config/log.txt', 'r')
		for x in inputs:
			if '# find' in x:
				continue
			if 
			for fileFormat in config.fileFormat_:
				if fileFormat in x:
					p = x.replace('\r\n','').replace('\r','')
					if p:
						lm.append(p)
		f = open('./config/dump.txt', 'wb')
		pickle.dump(lm, f)
		f.close()
```
** 2 CollectionDocument.py ** 
``` python
	#!/usr/bin/python
	#coding=utf-8
	#引入包		
	import datetime
	import os,sys,pexpect,time

	try:
		import cPickle as pickle
	except ImportError:
		import pickle

	import config
	
	#配置文件

	
	#短串联重复序列 （#网站目录）
	strs = config.webFolder_
	# 时间
	now = datetime.datetime.now()
	
	#获取 dump 文件中内容
	inputs = open('./config/dump.txt', 'r').read()
	# 转换格式
	rp = pickle.loads(inputs)

	#配置 log 文件夹 不存在时候建立
	if not os.path.exists(config.log_):
		os.makedirs('%s/%s'%(os.getcwd(),config.log_))

	#以写方式开启日志文件
	fout = open ('%s/%s/log'%(os.getcwd(),config.log_), "w")

	#建立本地与服务器对应文件夹
	for x in rp:
		file =  '/'.join(x.split('/')[1:-1])
		#li.append(file)
		try:
		os.makedirs('%s/%s'%(os.getcwd(),file))
		except:
		continue

	#拖取文件
	for x in rp:
		#在相对时间主动关闭程序
		if(int(now.strftime("%H")) == int(config.orvetime_)):
		exit();
		file2 = '%s%s'%(strs,x[1:])
		file1 = '%s/%s'%(os.getcwd(),'/'.join(x.split('/')[1:-1]))
		isfile ='%s/%s'%(os.getcwd(),'/'.join(x.split('/')))
		if not os.path.exists(isfile):
		#使用 scp 方式拖取文件
		cmd = 'scp -r  %s@%s:%s %s'%(config.user_,config.hots_,file2,file1)
		child = pexpect.spawn(cmd)
		child.expect('.ssword:')
		child.sendline(config.pass_)
		#完成后记录日志
		fout.write("%s ok \r\n"%cmd)
		#暂停半秒钟
		time.sleep(0.5)
	#完成后关闭文件
	fout.close()
```

