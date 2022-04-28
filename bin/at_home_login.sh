curl 'https://rappathome.net/content.aspx?page_id=31&club_id=442822&action=login&su=1&sc=1' \
  -i \
  -X POST \
  -c cookies.txt \
  -H 'Connection: keep-alive' \
  -H 'sec-ch-ua: " Not A;Brand";v="99", "Chromium";v="99", "Google Chrome";v="99"' \
  -H 'sec-ch-ua-mobile: ?0' \
  -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/99.0.4844.84 Safari/537.36' \
  -H 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8' \
  -H 'Cache-Control: no-cache' \
  -H 'X-Requested-With: XMLHttpRequest' \
  -H 'X-MicrosoftAjax: Delta=true' \
  -H 'sec-ch-ua-platform: "Linux"' \
  -H 'Accept: */*' \
  -H 'Origin: https://rappathome.net' \
  -H 'Sec-Fetch-Site: same-origin' \
  -H 'Sec-Fetch-Mode: cors' \
  -H 'Sec-Fetch-Dest: empty' \
  -H 'Referer: https://rappathome.net/content.aspx?page_id=31&club_id=442822&action=login&su=1&sc=1' \
  -H 'Accept-Language: en-US,en;q=0.9,es-US;q=0.8,es;q=0.7' \
  -H 'Cookie: MEMBER_TOKEN=701925236390; ASP.NET_SessionId=ixzezsagidzbamdp15amlwqk; ASP.NET_SessionId=' \
  --data-raw 'script_manager=ctl00%24ctl00%24ctl00%24ctl00%24login_wrapperPanel%7Cctl00%24ctl00%24login_button&style_sheet_manager_TSSM=&script_manager_TSM=%3B%3BSystem.Web.Extensions%2C%20Version%3D4.0.0.0%2C%20Culture%3Dneutral%2C%20PublicKeyToken%3D31bf3856ad364e35%3Aen-US%3Aba1d5018-bf9d-4762-82f6-06087a49b5f6%3Aea597d4b%3Ab25378d2%3BTelerik.Web.UI%3Aen-US%3A8b7d6a7a-6133-413b-b622-bbc1f3ee15e4%3A16e4e7cd%3A365331c3%3A24ee1bba%3Aed16cbdc&__EVENTTARGET=ctl00%24ctl00%24login_button&__EVENTARGUMENT=&DES_Group=&__VIEWSTATE=1%2FRtonNHmpeMZJXBmylzOaneg7eyQtOgMR0v7tCuo%2Bo3vOQvVV6QkO7IdjzJIuLDvaauemtv9PvBlB2grimwW0DLwC1pZYd6JIwaW0T8N4h44Ji0d0MwJR2SZhtU8qU8pi%2FerlubkR0%2Fg3DRojyKfosQOPo3ww4By%2BbwGt9rBHcDaXmA0hGkcf9N6BbaDIs0VGZzJN0PdpRBK7gpAxCr6fClyNptlOCLMP5DFrT%2F8l1E%2FmjAKbDnskHY1BUp8%2BFLBMsqxY1iVQiWWCEg1FiIqyRXv%2FUjMKtQUfYFmomzuyP5w8GddPwLhdP4sry7cFbwvaNuLGRzMZfXDf0nynTZi9fLGYDJyMM7KAj7BWYDRlZCfWipOmRkqjnwtPaYbKp4%2BOfRB5H6vpDa9m%2BVY3W3XeXctbvAZGdPSCpTHDKr%2FhWBXlML6EdokKLYBgs5bz8LNzBzA%2Fu9ruLv1HK90ZQnrP2GjKnbeo%2FOpk%2B%2B7MCEWC%2FM4LSq%2Fy4kevDw2EsWQmsAyU10HrFltlNpN9LTnebPVOODcDu%2B0t%2Ft5UpgdRE%2FKqXVVtdOyC3OkcyyNghy3n%2Bt3A7fwbrNf7eabtYDf83iz2RUwl88K%2F51P7XBOtfilkpoiGmRF9%2BJyN9gLSJab7CBhwqopY265%2FvUyuB38d2wRcAkqr8WKZ5nJfUxKPcmaOELXhxGU9TIOB%2FsJgiavFW1VfCh0YimumkkEJT6%2FOWZNiVoF6eR%2FVZwooguce%2F%2FcerMxGEdfA4RLG0UB9JQ2iygXooJyPnaGfoEtzpdvF7YZS6IqLPp7%2Fyx%2F5J4JeV%2BUPw7H2KNHvazmngugBKLYehQ46%2BU5%2B8lSrcTqDbQG48fYQmrRJDWWDBni9S2dQZW3BVbV6pt%2FvxmPS5nhxPHylZQajijFrpMZXE9jUOX2ipWSMtk%2F7pacffy0rdoEQSpyh0MfWo8&DES_JSE=1&__VIEWSTATEGENERATOR=65E7F3AF&changes_pending=&ctl00%24ctl00%24login_name=tacman&ctl00%24ctl00%24password=no2smoke&__ASYNCPOST=true&RadAJAXControlID=ctl00_ctl00_ajax_manager'

cat cookies.txt
