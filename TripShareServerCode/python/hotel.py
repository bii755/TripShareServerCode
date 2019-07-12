import requests

import json
# url = "https://www.earthtory.com/ko/city/seoul_310/hotel#1";

json_url = "https://www.earthtory.com/api/spot/get_spot_list"

data = {
    'pl_ci': '310',
    'member_srl': '0',
    'pl_category': '1',
    'cur_page': '1',
    'min_price': '92381',
    'max_price': '1068800',
    'star_rate': '',
    'from_lat': '',
    'from_lng': '',
    'order': 'pl_clip_cnt'
}
# headers = {
#     'Content-Type': 'application/x-www-form-urlencoded',
#     'charset':'UTF-8'
# }
req = requests.post(json_url,data=data).text
print(req)
# resultlist = json.loads(req)

# name = resultlist['response_result']['result_code']
# print(name)
