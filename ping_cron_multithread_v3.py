#!/usr/bin/python


###########################
# nastraivat tut

threads=6
startip=2
endip=254

###########################

import MySQLdb
import pinger
import os

def check_range(start,num):


        db = MySQLdb.connect(user="icstec", passwd="Chahmei4", db="pinger")

	def querydb(query):
        	db.query(query)
	        return db.store_result().fetch_row()	

	for i in range(start,start+num):
		ip = "192.168.26."+str(i)
		mac = pinger.renew_mac(ip)
		
		qw = querydb("select name from history where ip='"+ip+"' and time_down=0")
		closed_history = (len(qw)==0)
		if not closed_history: name_in_history = qw[0][0]

		if mac<>'none':
			if closed_history: 
				db.query("insert into history (ip,name,time_up) values ('"+ip+"','"+mac+"',now())")
			elif mac<>name_in_history:
				db.query("update history set time_down=now() where ip='"+ip+"' and time_down=0")
				db.query("insert into history (ip,name,time_up) values ('"+ip+"','"+mac+"',now())")				
		elif not closed_history:
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
