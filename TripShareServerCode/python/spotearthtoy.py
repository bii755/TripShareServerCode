import requests
import json
import time
import mysql.connector
from bs4 import BeautifulSoup
import sys
import io
sys.stdout = io.TextIOWrapper(sys.stdout.detach(), encoding = 'utf-8')
sys.stderr = io.TextIOWrapper(sys.stderr.detach(), encoding = 'utf-8')

data = {
 'lang' : 'ko',
 'pl_ci' : '310',
 'member_srl' : '0',
 'cur_page' : '1',
 'per_page' : '15',
 'pl_category' : '3',
 'pl_sub_category':'',
 'pl_tag_groups':'',
 'order': 'pl_clip_cnt'
}
# headers = {'Content-Type': 'application/x-www-form-urlencoded', 'charset':'utf-8'}

# 웹페이지의 소스를 가져오기
json_url = 'https://www.earthtory.com/api/spot/get_spot_list'
url = 'https://www.earthtory.com/ko/city/seoul_310/attraction#1'
# 요청결과인 json 데이터를 받아옴
json_string = requests.post(json_url, data=data).text
# 결과를 parsing함
data_list = json.loads(json_string)
# 해당 소스를 파싱하기
# 성공 했는지 여부와 총 몇 페이지인지 정보 페이지 수는 디비에 저장할 것이다.
result_code = data_list['response_result']['result_code']

total_page = data_list['response_result']['total_page']
if result_code == '000':
    pl_lat = data_list['response_data'][0]['pl_lat']
    pl_lng = data_list['response_data'][0]['pl_lng']
    pl_name = data_list['response_data'][0]['pl_name']
    pl_desc = data_list['response_data'][0]['pl_desc']
    pl_addr_en = data_list['response_data'][0]['pl_addr_en']
    pl_img_url = data_list['response_data'][0]['pl_img_url']
    pl_clip_cnt = data_list['response_data'][0]['pl_clip_cnt']
    rate = data_list['response_data'][0]['rate']
    pl_tags = data_list['response_data'][0]['pl_tags']
    link_url = data_list['response_data'][0]['link_url']

    print(pl_lng+"\n"+pl_name+"\n"+pl_desc+"\n"+pl_addr_en+"\n"+pl_img_url+"\n"+pl_clip_cnt
    +"\n"+rate+"\n"+pl_tags+"\n"+link_url)

else:
    print("no")
