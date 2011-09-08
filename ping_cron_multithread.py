#!/usr/bin/python


###########################
# nastraivat tut

threads=6
startip=1
endip=254

###########################

import MySQLdb
import pinger
import os

def check_range(start,num):

	def querydb(query):
        	db.query(query)
	        return db.store_result().fetch_row()	

	db = MySQLdb.connect(user="icstec", passwd="Chahmei4", db="pinger")
	cur = db.cursor()

	for i in range(start,start+num):
		ip = "192.168.26."+str(i)
		b = querydb("select time from online where ip='"+ip+"'")
		if len(b): old_time = b[0][0]
		else:	   old_time = 0
	
		db.query("delete from online where ip='"+ip+"'")
		not_closed_history = querydb("select count(*) from history where ip='"+ip+"' and time_down=0")[0][0]
		if pinger.host_is_up(ip):
			db.query("insert into online (ip,time) values ('"+ip+"',now())")
			if not old_time or not not_closed_history: db.query("insert into history (ip,time_up) values ('"+ip+"',now())")
		elif old_time or not_closed_history:
			db.query("update history set time_down=now() where ip='"+ip+"' and time_down=0")


endip=endip+1
length=(endip-startip)//threads
start=startip

i=0 # zashita na vsyakii pozarniy
while i<100:
	i=i+1
	if not os.fork(): #fork sozdayot klon processa
		check_range(start,length)
		break
	else:
		start=start+length
		if start+length>=endip:
			length=endip-start
			check_range(start,length)
			break
