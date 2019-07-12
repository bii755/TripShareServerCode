from selenium import webdriver

driver = webdriver.Chrome()
# driver = webdriver.Firefox()
# driver = webdriver.Ie()

driver.set_page_load_timeout(10)
driver.get("http://google.com")
driver.find_element_by_id("fakebox-input").send_keys("Automation step by step")
