import requests
import json
import time

import pymysql
conn = pymysql.connect(host="localhost", user='root', password='ok1644ok1644!'
, db='tripshare', charset='utf8')
curs = conn.cursor()

json_url = 'https://www.earthtory.com/api/spot/get_spot_list'
starttime = time.time()
page = 1

while page < 47:
    data = {
     'lang' : 'ko',
     'pl_ci' : '10488',
     'member_srl' : '0',
     'cur_page' : '%d' % page,
     'per_page' : '15',
     'pl_category' : '3',
     'pl_sub_category':'',
     'pl_tag_groups':'',
     'order': 'pl_clip_cnt'
    }

    json_string = requests.post(json_url, data=data).text
    data_list = json.loads(json_string)

    result_code = data_list['response_result']['result_code']
    if result_code == '000':
        dataindex = 0
        while dataindex < 15:
            pl_lat = data_list['response_data'][dataindex]['pl_lat']
            pl_lng = data_list['response_data'][dataindex]['pl_lng']
            sql = "update CA set city = '토론토' where pl_lat= %s and pl_lng= %s"
            dataindex = dataindex +1
            curs.execute(sql, (pl_lat, pl_lng))
            conn.commit()
        endtime = time.time() - starttime
        print(endtime)
    page = page+1
conn.close()
