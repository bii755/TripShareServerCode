import requests
from bs4 import BeautifulSoup

# 특정 웹페이지 크롤링하는 파라미터는 최대 크롤링할 페이지
def spider(max_pages):
    page = 1
    while page < max_pages:
        # 크롤링할 사이트 예제 블로그 , 뒤에 페이지 번호를 합쳐 저장한 값을  str() : 문자열 형변환인 듯
        url ="http://creativeworks.tistory.com/" + str(page)
        source_code = requests.get(url)
        plain_text = source_code.text
        # 'lxml'이 html.parser보다 속도가 빠르다고 ㅎ ㅏㄴ다.
        soup = BeautifulSoup(plain_text, 'html.parser')
        return soup
print(spider(4))
