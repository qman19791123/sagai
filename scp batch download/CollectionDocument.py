#!/usr/bin/python
#coding=utf-8


import datetime
import os,sys,pexpect,time

try:
    import cPickle as pickle
except ImportError:
    import pickle

import config

strs = config.webFolder_

now = datetime.datetime.now()

inputs = open('./config/dump.txt', 'r').read()
rp = pickle.loads(inputs)

if not os.path.exists(config.log_):
    os.makedirs('%s/%s'%(os.getcwd(),config.log_))

fout = open ('%s/%s/log'%(os.getcwd(),config.log_), "w")

for x in rp:
    file =  '/'.join(x.split('/')[1:-1])
    #li.append(file)
    try:
        os.makedirs('%s/%s'%(os.getcwd(),file))
    except:
        continue

for x in rp:
    if(int(now.strftime("%H")) == int(config.orvetime_)):
        exit();
    file2 = '%s%s'%(strs,x[1:])
    file1 = '%s/%s'%(os.getcwd(),'/'.join(x.split('/')[1:-1]))
    isfile ='%s/%s'%(os.getcwd(),'/'.join(x.split('/')))
    if not os.path.exists(isfile):
        #cmd = 'scp -r root@192.168.1.202:%s %s'%(file2,file1)
        cmd = 'scp -r  %s@%s:%s %s'%(config.user_,config.hots_,file2,file1)
        child = pexpect.spawn(cmd)
        child.expect('.ssword:')
        child.sendline(config.pass_)

        fout.write("%s ok \r\n"%cmd)
        time.sleep(0.5)

fout.close()