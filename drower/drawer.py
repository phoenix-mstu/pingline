#!/usr/bin/python

from PIL import Image, ImageDraw
import datetime

imgheight=10
imgwidth=1080
period=1
lenheight=10
lendist=0
textlength=0

#!!!!!!!!!!!!

linecolor='#F7A882'

#!!!!!!!!!!!!

image = Image.new("RGBA", (imgwidth,imgheight), (0,0,0,0))

def drawfathorizline(draw,x,y,l,s=lenheight,color=linecolor):
	draw.polygon([imgwidth-x,y,imgwidth-x-l,y,imgwidth-x-l,y+s,imgwidth-x,y+s], fill=color)

def drawline(draw,t1,t2,strnum):
	if t2>t1:
		drawfathorizline(draw, period*t1+textlength,strnum*lenheight+lendist,period*(t2-t1),s=(lenheight-lendist*2))

def drawstamp(draw,t,h,color="green",y0=25):
	x=imgwidth - period*t - textlength
	y1=y0-h*5
	len=1
	draw.polygon([x,y0,x+len,y0,x+len,y1,x,y1], fill=color)	


def drawtext(draw, text, strnum):
	draw.text([0,strnum*lenheight+lendist],text, fill="green")

def prepareimg(draw, lines):
	draw.polygon([0,0,textlength,0,textlength,imgheight,0,imgheight], fill="#D0D0D0")
	draw.polygon([textlength,0,imgwidth,0,imgwidth,imgheight,textlength,imgheight], fill="#E0FFE0")
	for i in range(0,lines//2):
		drawfathorizline(draw, 0,i*2*lenheight,textlength,color="#E0FFE0")
		drawfathorizline(draw, textlength,i*2*lenheight,imgwidth-textlength,color="#D0D0D0")

def saveimg(filename):
	image.save("/var/www/mrtg/drower.png", "PNG")

def draw_timeline(line, filename):
	if line<>[]:
		draw = ImageDraw.Draw(image)
		drawfathorizline(draw,0,0,imgwidth,color="#D0D0D0")
		for i in range(0,len(line)):
			if line[i][0] > imgwidth:
				line[i][0]=imgwidth
			drawline(draw, line[i][1],line[i][0],0)
		del draw
		image.save(filename, "PNG")

def draw_header(filename,filename1):
	#shapka
	height=25
	image = Image.new("RGBA", (imgwidth,height), (0,0,0,0))
	draw = ImageDraw.Draw(image)
	drawfathorizline(draw,0,0,imgwidth,color="#D0A0EF",s=height)

	a=datetime.datetime.now()
	
	midnight=a-datetime.datetime(a.year,a.month,a.day)	
        midnight_sec=midnight.seconds//60 + midnight.days*1140

	a=24
	for i in range(midnight_sec,imgwidth,60):
		drawstamp(draw, i, 1, "green")
		if a<>24:
	                draw.text([imgwidth - i - 2  - textlength,4], str(a), fill="white")
                a=a-1
        a=0
	for i in range(midnight_sec,0,-60):
                drawstamp(draw, i, 1, "green")
		draw.text([imgwidth - i - 2  - textlength,4], str(a), fill="white")
		a=a+1

	drawstamp(draw, midnight_sec, 2, "red")

	del draw
	image.save(filename, "PNG")
	del image

	#poloska
	image = Image.new("RGBA", (imgwidth,2), (0,0,0,0))
        draw = ImageDraw.Draw(image)
        drawfathorizline(draw,0,0,imgwidth,color="#D0A0EF",s=2)
	
	for i in range(midnight_sec,imgwidth,60):
                drawstamp(draw, i, 1, "green",5)
        for i in range(midnight_sec,0,-60):
                drawstamp(draw, i, 1, "green",5)
	drawstamp(draw, midnight_sec, 1, "red", 5)

        del draw
        image.save(filename1, "PNG")
        del image

