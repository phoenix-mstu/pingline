#!/bin/bash

/usr/sbin/arp -n | grep "$1 " | /usr/bin/tr -s " " | /usr/bin/cut -d " " -f 3 | /usr/bin/tr -d "\n"
