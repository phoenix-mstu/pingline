#!/bin/bash

if [ -f /var/lock/pinger ]; 
then    echo 'крон уже запущен';
	echo `date` 'крон уже запущен' > /var/log/pinger.log
else    touch /var/lock/pinger;
	/root/cron/pinger/ping_cron_multithread_v3.py	
	rm /var/lock/pinger; 
fi;
