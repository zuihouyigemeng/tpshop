#coding=utf-8
import urllib2
import json
import time
import hashlib
from MySQL import *

class KaiJiang(object):
	def __init__(self, dbconfig):
		self.dbconfig = dbconfig

	def GetSsc(self):
		url = 'http://chart.cp.360.cn/zst/qkj/?lotId=255401'
		content = urllib2.urlopen(url)
		if content.getcode()==200:
			sscJson=json.loads(content.read().decode())
			content.close()
			file_object = open('issue.txt','r')
			line = file_object.readline()
			file_object.close()
			if int(time.time())>int(sscJson['preEndTime']) and int(sscJson['0']['Issue'])>int(line):
				file_object = open('issue.txt','w')
				file_object.write(sscJson['0']['Issue'])
				file_object.close()
				return sscJson['0']['WinNumber']
			else:
				time.sleep(10)
				return self.GetSsc()
			file_object.close()
		else:
			time.sleep(10)
			return self.GetSsc()

	def TimeFactor(self):
		lists=[]
		if self.dbconfig['party']==1:
			kaijiang_ssc=int(self.GetSsc())
		else:
			kaijiang_ssc=0
		db = MySQL(self.dbconfig)
		db.query('select kaijiang_count,kaijiang_ssc,number,id,kaijang_time,sid,no from '+self.dbconfig['prefix']+'shop_period where state=1')
		result = db.fetchAllRows()
		if result:
			for row in result:
				kaijang_num = (row[0]+row[1])%row[2]+10000001
				db.query('select uid from '+self.dbconfig['prefix']+'shop_record where pid=%d and FIND_IN_SET("%d",num)' % (row[3],kaijang_num))
				uid = db.fetchOneRow()
				if uid:
					db.update('update '+self.dbconfig['prefix']+'shop_period set kaijang_num="%s",uid=%d,state=2,kaijiang_ssc=%s where id=%d' % (kaijang_num,uid[0],kaijiang_ssc,row[3]))
					db.query('select nickname,openid from '+self.dbconfig['prefix']+'user where id=%d and status=1' % uid[0])
					user = db.fetchOneRow()
					if(user[1]):
						lists.append({"touser":user[1],"pid":row[3],"username":user[0],"no":row[6],"time":row[4],"sid":row[5]})
		db.close()
