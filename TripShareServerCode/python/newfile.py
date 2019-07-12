import requests
import json
import time

from bs4 import BeautifulSoup
import pymysql
# conn = pymysql.connect(host="localhost", user='root', password='ok1644ok1644!'
# , db='tripshare', charset='utf8')
# curs = conn.cursor()
# this is swiapied json data from me
json_url = 'https://www.earthtory.com/api/spot/get_spot_list_country'
# start time and page num
starttime = time.time()

data = {
 'lang' : 'ko',
 'pl_cu' : '205',
 'member_srl' : '0',
 'cur_page' : '1',
 'per_page' : '15',
 'pl_category' : '3',
 'pl_sub_category':'',
 'pl_tag_groups':'',
 'order': 'pl_clip_cnt'
}
# headers = 'application/x-www-form-urlencoded; charset=UTF-8'
json_string = requests.post(json_url, data=data).text
data_list = json.loads(json_string)
print(data_list['response_data'])
