#coding=utf-8
from apscheduler.schedulers.blocking import BlockingScheduler
from apscheduler.triggers.cron import CronTrigger
from datetime import datetime
import json
from kaijiang import *

def my_job():
	kjime=datetime.now().hour
	job=sched.get_job('my_job')
	if kjime>=10 and kjime<22:
		if str(job.trigger)!=str(CronTrigger(minute='*/10',second='50')):
			sched.reschedule_job(job.id, trigger='cron',minute='*/10',second='50')
	elif kjime<2 or kjime>=22:
		if str(job.trigger)!=str(CronTrigger(minute='*/5',second='50')):
			sched.reschedule_job(job.id, trigger='cron',minute='*/5',second='50')
	else:
		if str(job.trigger)!=str(CronTrigger(hour='10',second='50')):
			sched.reschedule_job(job.id, trigger='cron', hour='10',second='50')
	jiang = KaiJiang(dbconfig)
	jiang.TimeFactor()
	print 'time: %s' % datetime.now()

def my_kj():
	jiang = KaiJiang(dbconfig)
	jiang.TimeFactor()
	print 'time: %s' % datetime.now()

sched = BlockingScheduler()
f = open("config.json")
dbconfig = json.load(f)
f.close
if dbconfig['party']==1:
	dtime=datetime.now().hour
	if dtime>=10 and dtime<22:
		sched.add_job(my_job, 'cron',minute='*/10',second='50',id='my_job')
	elif dtime<2 or dtime>=22:
		sched.add_job(my_job, 'cron', minute='*/5',second='50',id='my_job')
	else:
		sched.add_job(my_job, 'cron', hour='10',second='50',id='my_job')
else:
	sched.add_job(my_kj, 'cron', minute='*/5',second='50',id='my_job')
sched.start()