#!/usr/bin/python

import os
import string
import subprocess

def renew_names(hosts):
	f = open("./hosts_names.dat")
	a = f.readlines()
	b={}
	for i in range(0,len(a)):
		c,d=string.split(a[i], ' ')
		b[c]=d
	for i in range(0,len(hosts)):
		try:
			hosts[i]=b[hosts[i]]
		except: pass
	
def renew_mac(ip):
	a = subprocess.Popen(["/root/cron/pinger/renew_mac.sh", ip], stdout=subprocess.PIPE)
	a.wait()
	return a.communicate()[0]

def host_is_up(ip):
	a=os.system("/bin/ping "+ip+" -c 1 -W 1 > /dev/null")
        return (a==0)

