#!/usr/bin/python

period=1 #minute
interval='1080' #minute

import drawer
import MySQLdb
import datetime

db = MySQLdb.connect(user="icstec", passwd="Chahmei4", db="pinger")

def querydb(query):
	db.query(query)
	return db.store_result().fetch_row()

def drawmac(mac):
	db.query("select * from history where name='"+mac+"' and (time_down>now()-interval "+interval+" minute or time_down=0)")
	a=db.store_result()
	row=a.fetch_row()
	line=[]
	while len(row)<>0:
		up_time=   datetime.datetime.now()-row[0][2]
		up =   up_time.seconds//60 + up_time.days*1140
		if row[0][3]<>None:
			down_time= datetime.datetime.now()-row[0][3]
			down = down_time.seconds//60 + down_time.days*1140
		else: down=0
		line.append([up, down])
	#	print datetime.timedelta(row[0][2])
		row=a.fetch_row()
	print line
	drawer.draw_timeline(line,"/var/www/school/mrtg/pinger/img_"+mac+".png")


db.query("select name from history where (time_down>now()-interval "+interval+" minute or time_down=0) group by name")
a=db.store_result()
row=a.fetch_row()
line=[]
while len(row)<>0:
	print row
	mac=row[0][0]
	drawmac(mac)
	row=a.fetch_row()

drawer.draw_header("/var/www/school/mrtg/pinger/header.png", "/var/www/school/mrtg/pinger/line.png")
