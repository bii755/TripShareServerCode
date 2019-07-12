import requests
from bs4 import BeautifulSoup
page = requests.get("http://forecast.weather.gov/MapClick.php?lat=37.7772&lon=-122.4168")
soup = BeautifulSoup(page.content, 'html.parser')
seven_day = soup.find(id="seven-day-forecast")
'''
forecast_items = seven_day.find_all(class_="forecast-tombstone")

tonight = forecast_items[0]
# print(tonight.prettify())
period = tonight.find(class_="period-name").get_text()
short_desc = tonight.find(class_="short-desc").get_text()
temp = tonight.find(class_="temp").get_text()
#
# print(period)
# print(short_desc)
# print(temp)

img = tonight.find("img")
desc = img['title']
print(desc)
'''
period_tags =seven_day.select(".forecast-tombstone .period-name")
#
periods = [pt.get_text() for pt in period_tags]
print(periods)
# id명이 seven-day-forecast 라는 div 태그 안에서 선택자를 사용해서 찾겠다.
#클래스 명이 tombstone-container라는 곳의 li 태그 안에 클래스 명이 short-desc라는 p태그의 텍스트를 가져오겠다.
short_descs = [sd.get_text() for sd in seven_day.select(".tombstone-container .short-desc")]
# temps = [t.get_text() for t in seven_day.select(".tombstone-container .temp")
print(short_descs)
