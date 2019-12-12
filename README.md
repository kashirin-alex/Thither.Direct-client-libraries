## Thither.Direct - CLIENT - LIBRARIES

### CLIENT LIBRARIES
The libraries are separated by the client's side language

+ [PYTHON](python)
+ [JAVA](java)
+ [PHP](php)






### Flow Metrics Statistics(FMS) API URLs structure

The API base url for requests: https://thither.direct/api/fms/
with options by version https://thither.direct/api/fms-v201807

The Authernication parameters applied to every type of request.

    Parameters (Auth):
      'fid'   - Your Flow-ID
      'ps'    - Passphrase, if no token
      'token' - the result of AES.MODE_EAX

'token' as example https://github.com/kashirin-alex/Thither.Direct-client-libraries/blob/6ff862edecfd6d74d7133d7ef99582348b88dacf/python/libthither/api/fms.py#L151





##### POST METHOD:


  https://thither.direct/api/fms/post/stats/item/

    Content-Type: 
      'application/x-www-form-urlencoded'
      'application/json'
    Parameters + Auth: 
      'mid'   - Your Metric-ID
      'dt'    - Unix Timestamp or format '%Y-%m-%d %H:%M:%S'
      'v'     - Integer Value positive, negative or =equal


  https://thither.direct/api/fms/post/stats/items_json/

    Content-Type: 
      'application/json'
    Parameters + Auth:
      'items' - list of items [[Metric ID, DateTime, Value],]


  https://thither.direct/api/fms/post/stats/items_csv/

    Content-Type: 
      'multipart/form-data'
      'application/json'
    Parameters + Auth:
      'csv' - data with ['mid', 'dt', 'v'] columns


###### RESPONSE TO POST:
  
    JSON format:
      {
        "status": NAME, 
        "msg": MESSAGE, 
        "succeed": COUNT, 
        "failed": COUNT, 
        "errors": LIST
      }







##### GET METHOD:
  https://thither.direct/api/fms/get/definitions/
  * units/
  * sections/
  * metrics/
  
  https://thither.direct/api/fms/get/stats/