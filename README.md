# Walkmate Backend

## API List
별도로 표기하지 않으면 **_Method는 POST_**.

---

### **산책 글 API**

* ### **getWalkList.php**

    산책 모집 목록을 요청합니다.    
    - **Request Body** | Type : JSON
        |Data Name|Type|Description|More|
        |:---:|:---:|---|:---|
        |requireCount|Number|한번에 요청할 모집 글의 개수|-|
        |walkListCount|Number|현재까지 요청한 개수|-|
        |requestTime|Datetime|요청할 최종 시간|"yyyy-mm-dd hh:mm:ss" 형식, 이 시간 이전에 등록된 글만 조회|

        **Example**
        ```json
        //Case: 11월 21일 10시 이전 작성된 글만 10개씩 요청하며 현재까지 20개 요청된 상황
        {
            "requireCount": 10,
            "walkListCount": 20,
            "requestTime": "2021-11-21 10:00:00"
        }
        ```
    - **Response Body** | Type : JSON
        |Data Name|Type|Description|More|
        |:--:|:---:|---|:---|
        |isSuccess|Boolean|성공 여부|-|
        |code|Number|실패 코드|실패시에만 반환 (isSuccess === false)|
        |errorDetail|String|오류 내용 설명|실패시에만 반환|
        |walksCount|Number|조회한 모집 글의 개수|-|
        |walks|JSON|조회한 모집글 데이터|숫자가 작을 수록 최근 글|

        + walks 하위 구조
            
            0부터 walksCount까지의 데이터 이름 아래에 동일한 구조의 객체가 존재
            |Data Name|Type|Description|More|
            |:---:|:---:|---|:---|
            |walkKey|Number|모집 글 식별 키|-|
            |hostKey|Number|작성자 사용자 키|-|
            |hostID|String|작성자 아이디|-|
            |hostNickname|String|작성자 닉네임|-|
            |title|String|모집 글 제목|-|
            |depLatitude|Decimal|출발 장소|-|
            |depLongitude|Decimal|출발 장소|-|
            |nowMemberCount|Number|현재 참가 인원|-|
            |maxMemberCount|Number|최대 참가 인원|-|
            |applyMemberCount|Number|참가 신청 인원|-|
            |description|String|모집 글 설명|-|
            |depTime|Datetime|산책 일시|"yyyy-mm-dd hh:mm:ss" 형식|
            |writeTime|Datetime|글 작성 일시|"yyyy-mm-dd hh:mm:ss" 형식|
            |distance|Decimal|등록한 주소와의 거리|km 단위, 로그인 상태만 반환|

        **Example**
        ```json
        //Case: Success
        {
            "isSuccess": true,
            "walksCount": 1,
            "walks": {
                "0": {
                    {
                    "walkKey": 1,
                    "hostKey": 1,
                    "hostID": "hostID",
                    "hostNickname": "hostNick",
                    "title": "산책가실분~",
                    "depLatitude": 35.199294829143,
                    "depLongitude": 128.07520607663,
                    "nowMemberCount": 1,
                    "maxMemberCount": 5,
                    "applyMemberCount": 0,
                    "description": "자유 산책입니다.",
                    "depTime": "2021-11-25 18:00:00",
                    "writeTime": "2021-11-21 21:35:44",
                    "distance": 0.3949711476380123
                    }
                }
            }
        }

        //Case: Fail
        {
            "isSuccess": false,
            "code": 1,
            "reason": "DB 오류"
        }
        ```

* ### **getRecWalkList.php**

    거리 조건으로 산책 목록을 요청합니다. 로그인 필요
      
    - **Request Body** | Type : JSON
        |Data Name|Type|Description|More|
        |:---:|:---:|---|:---|
        |requireCount|Number|한번에 요청할 모집 글의 개수|-|
        |walkListCount|Number|현재까지 요청한 개수|-|
        |requestTime|Datetime|요청할 최종 시간|"yyyy-mm-dd hh:mm:ss" 형식, 이 시간 이전에 등록된 글만 조회|
        |limitDistance|Decimal|요청할 최대 거리|해당 값 이하의 거리만 반환, km 단위|

        **Example**
        ```json
        //Case: 11월 21일 10시 이전 작성된 제한 거리 1km인 글만 10개씩 요청하며 현재까지 20개 요청된 상황
        {
            "requireCount": 10,
            "walkListCount": 20,
            "requestTime": "2021-11-21 10:00:00",
            "limitDistance": 1.0
        }
        ```
    - **Response Body** | Type : JSON
        |Data Name|Type|Description|More|
        |:--:|:---:|---|:---|
        |isSuccess|Boolean|성공 여부|-|
        |code|Number|실패 코드|실패시에만 반환 (isSuccess === false)|
        |errorDetail|String|오류 내용 설명|실패시에만 반환|
        |walksCount|Number|조회한 모집 글의 개수|-|
        |walks|JSON|조회한 모집글 데이터|숫자가 작을 수록 가까운 글(walks 하위구조 참고)|

        **Example**
        ```json
        //Case: Success
        {
            "isSuccess": true,
            "walksCount": 1,
            "walks": {
                "0": {
                    "walkKey": 1,
                    "hostKey": 1,
                    "hostID": "hostID",
                    "hostNickname": "hostNick",
                    "title": "산책가실분~",
                    "depLatitude": 35.199294829143,
                    "depLongitude": 128.07520607663,
                    "nowMemberCount": 1,
                    "maxMemberCount": 5,
                    "applyMemberCount": 0,
                    "description": "자유 산책입니다.",
                    "depTime": "2021-11-25 18:00:00",
                    "writeTime": "2021-11-21 21:35:44",
                    "distance": 0.3949711476380123
                    }
                }
            }
        }

        //Case: Fail
        {
            "isSuccess": false,
            "code": 1,
            "reason": "DB 오류"
        }
        ```

* ### **getWalkDetail.php**

    산책 모집글 세부내용을 요청합니다.

    - **Request Body** | Type : JSON
        |Data Name|Type|Description|More|
        |:--:|:---:|---|:---|
        |walkKey|Number|산책 글의 번호|-|

        **Example**
        ```json
        {
            "walkKey": 1
        }
        ```

    - **Response Body** | Type : JSON
        |Data Name|Type|Description|More|
        |:--:|:---:|---|:---|
        |isSuccess|Boolean|성공 여부|-|
        |code|Number|실패 코드|실패시에만 반환 (isSuccess === false)|
        |errorDetail|String|오류 내용 설명|실패시에만 반환|
        |body|JSON|글의 내용|getWalkList.php의 **walks 하위구조** 참고|
        |isHost|boolean|현재 계정의 작성자 여부|작성자가 아니거나 비 로그인 상태이면 false
        |memberList|JSON|참가자 목록|isHost === true면 반환|
        |applyList|JSON|신청자 목록|isHost === true면 반환|

        **Example**
        ```json
        //Case: Success
        {
            "isSuccess":true,
            "body":{
                "walkKey": 1,
                "hostKey": 1,
                "hostID": "hostID",
                "hostNickname": "hostNick",
                "title": "산책가실분~",
                "depLatitude": 35.199294829143,
                "depLongitude": 128.07520607663,
                "nowMemberCount": 1,
                "maxMemberCount": 5,
                "applyMemberCount": 0,
                "description": "자유 산책입니다.",
                "depTime": "2021-11-25 18:00:00",
                "writeTime": "2021-11-21 21:35:44",
                "distance": 0.3949711476380123
            },
            "isHost":true,
            "memberList":{
                "0":{
                    "walkKey":2,
                    "memberKey":1,
                    "memberID":"hostID",
                    "nickname":"hostNick",
                    "joinTime":"2021-11-18 16:52:36"
                },
            },
            "applyList":{
                "0":{
                    "walkKey":2,
                    "memberKey":8,
                    "memberID":"member2ID",
                    "nickname":"menber2",
                    "applyTime":"2021-11-18 22:01:32"
                }
            }
        }

        //Case: Fail
        {
            "isSuccess": false,
            "code": 1,
            "errorDetail": "DB 오류"
        }
        ```

    
* ### **writeWalk.php**
    
    __로그인 필수__

    산책 모집글을 작성합니다.
    - **Request Body** | Type : JSON
        |Data Name|Type|Description|More|
        |:--:|:---:|---|:---|
        |title|String|모집 글의 제목|-|
        |depLatitude|Decimal|출발 장소 위도|-|
        |depLongitude|Decimal|출발 장소 경도|-|
        |maxMemberCount|Number|최대 모집 인원|-|
        |description|String|산책에 대해서 간단한 설명|-|
        |depTime|Datetime|산책 출발시간|"yyyy-mm-dd hh:mm:ss" 형식|

        **Example**
        ```json
        {
            "title": "산책 가실 분",
            "depLatitude": 35.153355865549905,
            "depLongitude": 128.09943616923988,
            "maxMemberCount": 5,
            "description": "후문쪽에서 산책 가실분 구합니다~",
            "depTime": "2021-11-25 18:00:00"
        }
        ```

    - **Response Body** | Type : JSON
        |Data Name|Type|Description|More|
        |:--:|:---:|---|:---|
        |isSuccess|Boolean|성공 여부|-|
        |code|int|실패 코드|실패시에만 반환 (isSuccess === false)|
        |errorDetail|String|오류 내용 설명|실패시에만 반환|

        **Example**
        ```json
        //Case: Success
        {
            "isSuccess": true
        }

        //Case: Fail
        {
            "isSuccess": false,
            "code": 1,
            "errorDetail": "DB 오류"
        }
        ```

* ### **applyWalk.php**
    
    __로그인 필수__

    산책 글에 신청 요청을 합니다.    

    - **Request Body** | Type : JSON
        |Data Name|Type|Description|More|
        |:--:|:---:|---|:---|
        |walkKey|Number|산책 글 번호|-|

        **Example**
        ```json
        {
            "walkKey": 1
        }
        ```

    - **Response Body** | Type : JSON
        |Data Name|Type|Description|More|
        |:--:|:---:|---|:---|
        |isSuccess|Boolean|성공 여부|-|
        |code|int|실패 코드|실패시에만 반환 (isSuccess === false)|
        |errorDetail|String|오류 내용 설명|실패시에만 반환|

        **Example**
        ```json
        //Case: Success
        {
            "isSuccess": true
        }

        //Case: Fail
        {
            "isSuccess": false,
            "code": 1,
            "errorDetail": "DB 오류"
        }
        ```

* ### **confirmApplyWalk.php**
    
    __로그인 필수__

    신청을 승인 또는 거절 요청합니다. 

    - **Request Body** | Type : JSON
        |Data Name|Type|Description|More|
        |:--:|:---:|---|:---|
        |walkKey|Number|산책 글 번호|-|
        |confirmData|JSON|승인 내용|-|

        + confirmData 하위 구조
            
            |Data Name|Type|Description|More|
            |:---:|:---:|---|:---|
            |userKey|Number|신청자의 Key|-|
            |isAccept|Boolean|수락 여부|false면 거절|

        **Example**
        ```json
        {
            "walkKey": 1,
            "confirmData" : {
                "userKey": 2,
                "isAcccept": true
            }
        }
        ```

    - **Response Body** | Type : JSON
        |Data Name|Type|Description|More|
        |:--:|:---:|---|:---|
        |isSuccess|Boolean|성공 여부|-|
        |code|int|실패 코드|실패시에만 반환 (isSuccess === false)|
        |errorDetail|String|오류 내용 설명|실패시에만 반환|

        **Example**
        ```json
        //Case: Success
        {
            "isSuccess": true
        }

        //Case: Fail
        {
            "isSuccess": false,
            "code": 1,
            "errorDetail": "DB 오류"
        }
        ```

* ### **Error Codes**

    |Code|Description|
    |:--:|:---|
    |1|DB 오류|
    |2|로그인 세션 오류 (로그인 상태 아님)|
    |3|존재하지 않는 글|
    |4|중복 신청 / 승인할 신청자 없음|
    |5|권한 없음|
    