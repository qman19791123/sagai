#!/usr/bin/python
#coding=utf-8

import os,sys,pexpect,time
try:
    import cPickle as pickle
except ImportError:
    import pickle

import config 

#引入包
#hots 服务器地址
hots_ = config.hots_
#user 服务器用户名
user_ = config.user_
#pass 服务器密码
pass_= config.pass_

lm =[]
j=0
t=False

if not os.path.exists('./config'):
    os.makedirs('%s/%s'%(os.getcwd(),'./config'))
if not os.path.exists('./config/log.txt'):
    fout = open ('./config/log.txt', "w")

    cmd = 'ssh %s@%s'%(user_,hots_)
    child = pexpect.spawn(cmd)
    child.expect('.ssword:')
    child.sendline(pass_)

    child.sendline('cd /%s'%config.webFolder_)
    for fileFolder in config.fileFolder_:
        for fileFormat in config.fileFormat_:
           child.sendline('find ./%s|grep %s'%(fileFolder,fileFormat))

    child.sendline('exit')

    child.logfile = fout
    child.expect(pexpect.EOF)
    fout.close()

    inputs = open('./config/log.txt', 'r')
    for x in inputs:
        if '# find' in x:
            continue
        for fileFormat in config.fileFormat_:
            if fileFormat in x:
                p = x.replace('\r\n','').replace('\r','')
                if p:
                    lm.append(p)
    f = open('./config/dump.txt', 'wb')
    pickle.dump(lm, f)
    f.close()