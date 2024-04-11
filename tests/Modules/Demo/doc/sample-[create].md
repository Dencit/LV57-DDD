####SAMPLE-[CREATE]

###### URL

~~~
POST : {{base_url}}/demo/sample/add


~~~

###### QUERY

~~~
~~~

###### HEADER

~~~
token : 
~~~

###### BODY

~~~
name : create新增
nick_name : 
mobile : 18588889999
photo : 
sex : 0
type : 0
status : 0
~~~

###### BODY_DESC

| 字段 | 类型 | 必须 | 默认值 | 说明 |
| --- | --- | --- | --- | --- |
| name | string 50 | yes |  | 用户昵称 |
| mobile | string 30 | yes |  | 绑定手机 |
| photo | string 200 | yes |  | 用户头像 |
| sex | int 3 | yes | 0 | 性别: 0未知, 1男, 2女 |
| type | int 3 | yes | 0 | 类型: 0未知, 1-否, 2-是 |
| status | int 3 | yes | 0 | 状态: 1-否, 2-是 |

###### RESPONSE

~~~
{"data":{"id":6,"name":"create新增","mobile":"18588889999","photo":"","sex":0,"type":0,"status":0,"created_at":"2021-04-12 02:02:16","updated_at":"2021-04-12 02:02:16"}}
~~~

