#!/usr/bin/python

period=1 #minute
interval='1080' #minute
prefix='18hr_'

import drawer
import MySQLdb
import datetime

db = MySQLdb.connect(user="icstec", passwd="Chahmei4", db="pinger")

def querydb(query):
	db.query(query)
	return db.store_result().fetch_row()

def drawip(ip):
	db.query("select * from history where ip='"+ip+"' and (time_down>now()-interval "+interval+" minute or time_down=0)")
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
	drawer.draw_timeline(line,"/var/www/school/mrtg/pinger/"+ip+".png")

for i in range(1,256):
	drawip('192.168.26.'+str(i))

drawer.draw_header("/var/www/school/mrtg/pinger/header.png", "/var/www/school/mrtg/pinger/line.png")
