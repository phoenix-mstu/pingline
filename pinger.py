#!/usr/bin/python

import os
import string
import subprocess
import scapy

def renew_mac(ip):
	mac=scapy.getmacbyip(ip) 
	if mac==None: mac='none'
	return mac

