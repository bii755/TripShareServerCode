from bs4 import BeautifulSoup
import requests

#get 방식으로 해당 페이지 요청
page = requests.get("http://dataquestio.github.io/web-scraping-pages/ids_and_classes.html")
# page는 요청의 응답 결과를 담는 변수
#content를 통해 html 문서를 다운 받을 수 있음

soup = BeautifulSoup(page.content,'html.parser')
# print([type(item) for item in list(soup.children)])
"""
  배열을 반환함
html = list(soup.children)
"""
"""
하나씩 태그를 찾는 방법
html = list(soup.children)[2]
body = list(html.children)[3]
p = list(body.children)[1]
"""

print(soup.select("div p"))
