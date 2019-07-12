import requests
import json
import time
import pymysql
conn = pymysql.connect(host="localhost", user='root', password='ok1644ok1644!'
, db='tripshare', charset='utf8')

curs = conn.cursor()
# this is swiapied json data from me
json_url = 'https://www.earthtory.com/api/spot/get_spot_list_country'
# start time and page num
starttime = time.time()
page = 1
# total page
while page < 229:
    # requests data
    print(page)
    data = {
     'lang' : 'ko',
     'pl_cu' : '204',
     'member_srl' : '0',
     'cur_page' : '%d' % page,
     'per_page' : '15',
     'pl_category' : '3',
     'pl_sub_category':'',
     'pl_tag_groups':'',
     'order': 'pl_clip_cnt'
    }
    json_string = requests.post(json_url, data=data).text
    print(json_string)
    data_list = json.loads(json_string)
    result_code = data_list['response_result']['result_code']
    if result_code == '000':
        dataindex = 1
        while dataindex < 15:
            pl_lat = data_list['response_data'][dataindex]['pl_lat']
            pl_lng = data_list['response_data'][dataindex]['pl_lng']
            pl_name = data_list['response_data'][dataindex]['pl_name']
            pl_img_url = data_list['response_data'][dataindex]['pl_img_url']
            pl_clip_cnt = data_list['response_data'][dataindex]['pl_clip_cnt']
            rate = data_list['response_data'][dataindex]['rate']
            pl_tags = data_list['response_data'][dataindex]['pl_tags']
            pl_category_nm = data_list['response_data'][dataindex]['pl_category_nm']
            pl_sub_category_nm = data_list['response_data'][dataindex]['pl_sub_category_nm']
            dataindex = dataindex +1

            sql = """insert into GB(pl_lat, pl_lng, pl_name, pl_img_url, pl_clip_cnt, rate, pl_tags, pl_category_nm, pl_sub_category_nm)
            values (%s, %s, %s, %s, %s, %s, %s, %s, %s)"""

            curs.execute(sql , (pl_lat, pl_lng, pl_name, pl_img_url, pl_clip_cnt, rate, pl_tags, pl_category_nm, pl_sub_category_nm))
            conn.commit()
        endtime = time.time() - starttime
        print(endtime)
        page = page+1
    else:
        print("no")

conn.close()
