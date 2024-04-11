
# Artisanx 使用规范

---

## 1/模块接口 (以Demo模块为例子)

> 示例模块-代码定制
~~~
modules 根目录
    |__ Demo 模块目录
        |___Http                        应用层-端口业务
        |    |___Controllers            应用层-控制器(控制器=端口)
        |    |___Requests               应用层-输入验证
        |    |___Logics                 应用层-服务函数组合层(组合)
        |    |___Transformers           应用层-输出过滤
        |___Config                      领域层-模块配置
        |___Enums                       领域层-常量枚举值
        |___Exceptions                  领域层-业务异常值
        |___Jobs                        领域层-队列        
        |___Consoles                    领域层-业务指令
        |___Services                    领域层-服务函数层
        |___Aggrs                       领域层-聚合(curl/RPC/OpenAPI的聚合层)
        |___Repositorys                 领域层-仓储层
        |___Models                      基础层-MySql数据模型
        |___Entitys                     基础层-实体(curl/RPC/OpenAPI的数据模型)
        |___Edocs                       基础层-实体(ElasticSearch数据模型)
~~~

---

## 2/示例模块-接口调用规范


### 说明
~~~
如果是自动生成的接口代码, 且执行主表自定义查询, 都默认支持下面这些查询query.
~~~


### POST 类型接口
~~~
没有特别规定
~~~


### GET 类型接口
~~~
get接口若要开启临时缓存, 统一添加query参数: '_time'. 约定不传该参数时,默认调用缓存 ; 传 '_time=1' 时, 跳过缓存.
~~~


#### * 获取-列表-实时 & 获取-详情-实时 "_time":
~~~
{{base_url}}/demo/sample/index ?_time=1
{{base_url}}/demo/sample/read/1 ?_time=1
~~~
| 字段 | 示例 | 说明 |
| --- | --- | --- |
| _time | _time=1 | 跳过缓存: 不跳过=0,跳过=1, 默认不跳过. |


#### * 获取-列表-翻页 "_pagination,_page,_page_size":
~~~
{{base_url}}/demo/sample/index ?_pagination=true &_page=1 &_page_size=3
~~~
| 字段 | 示例 | 说明 |
| --- | --- | --- |
| _pagination | _pagination=true | 翻页 打开=true,关闭=false; 关闭时,一页100条数据上限; 默认20; |
| _page | _page=1 | 页码 默认1 |
| _page_size | _page_size=3 | 页数 默认20 |


#### * 获取-列表-关联副表数据 "_include": 
~~~
{{base_url}}/demo/sample/index ?_include=image,video
----------------------------------------
_include=? 关联数据: 按场景选择需关联对象,提高接口性能.
~~~
| 字段 | 示例 | 说明 |
| --- | --- | --- |
| _include | _include=image,video | 指定关联模型 关联 image,video 数据, 需要服务端定制; |


#### * 获取-列表-筛选动作 "_search" :
~~~
{{base_url}}/demo/sample/index ?_search=demo &type=1 &status=1,2 &name=陈%
~~~
| 字段 | 示例 | 说明 |
| --- | --- | --- |
| _search | _search=demo | 触发demo模块"主表筛选动作", 默认值对应实际api根路径名, 所以这里是'demo'. 若有其它筛选动作,再增加动作名称. |
| type | type=1 |  触发"主表筛选动作时", 添加 type = 1 的筛选条件, '=,>,<,>=,<='运算符,服务端内部设定,前端不用关心. 字段名对应表字段. |
| status | status=1,2 | 触发"主表筛选动作"时, 添加 status in 1,2 的筛选条件, 即包含条件. 字段名对应表字段. |
| name | name=陈% |  触发"主表筛选动作"时, 添加 name like 陈% 的筛选条件, 即"陈"开头的姓名. 字段名对应表字段. |


#### * 获取-列表-副表扩展查询 "_extend":
~~~
{{base_url}}/demo/sample/index ?_extend=user &user_type=1 &user_status=1 ...
----------------------------------------
_extend=? 副表查询动作: 按需要触发.
~~~
| 字段 | 示例 | 说明 |
| --- | --- | --- |
| _extend | _extend=user | 扩展查询副表-user, 需要服务端定制; |
| user_type,user_status ... | &user_type=1 &user_status=1 ... | 触发 "扩展查询副表-user" 时, 传递的副表查询参数, 进行副表字段查询, 需要服务端定制; |


#### * 获取-列表-排序 "_sort":
~~~
{{base_url}}/demo/sample/index ?_sort=-id
~~~
| 字段 | 示例 | 说明 |
| --- | --- | --- |
| _sort | _sort=-id | 自定义排序, 升序= id , 倒序= -id ; 默认倒序, id可以是其它字段; |
| _sort | _sort=-id,-num | 自定义排序, 升序= id , 倒序= -id ; 默认倒序, id可以是其它字段; 支持多个字段 |


#### * 获取-列表-分组 "_group":
~~~
{{base_url}}/demo/sample/index ?_group=city_id,user_id
~~~
| 字段 | 示例 | 说明 |
| --- | --- | --- |
| _group | _group=city_id | 按"城市"分组, city_id可以是其它字段; |
| _group | _group=city_id,user_id | 按"城市->用户"分组, city_id,user_id可以是其它字段; 支持多个字段. |


---


## 3/示例模块代码 按数据表设计 生成 基本curd业务

~~~

根据示例模块结构, 自动生成需要的子功能接口,
且按照相应功能或数据表结构, 提供CURD基本接口, 并能满足一般的增删查改需要, 取消注释就能使用.

> 创建模块+目录结构
php artisanx module:make test

----------------------------------------

> 以下指令,适用于此参数结构:
[php,thinkex,module:*,'控制器名(数据表名)','模块名','可选生成curd函数(-,c,u,r,d,bc,bu,br,bd,cj,cmd)','指定数据库连接配置','指定数据表前缀']
-可选生成curd函数: "-"-空类, c-增,u-改,r-读(单行),d-删,bc-批量增,bu-批量改,br-批量读(列表),bd-批量删,cj-单增队列,cmd-指令, 按需填, 可传单个值.

> 创建模块-路由 -无数据库
php artisanx module:route TestChild Test c,u,r,d,br

> 创建子接口组 -无数据库
php artisanx module:base TestChild Test c,u,r,d,br

> 创建子接口组 -有数据库
php artisanx module:base-on TestChild Test c,u,r,d,br database -

----------------------------------------

> 以下指令,适用于此参数结构:
[php,thinkex,module:*,'控制器名(数据表名)','模块名','指定数据库连接配置','指定数据表前缀']
-数据库连接配置: 不传值-默认database, 跨库需要另外指定配置名.
-指定数据表前缀: 传"-"-不指定, 不传值-用默认前缀, 传前缀-自定义前缀, 注意,跨库时,看情况需指定前缀.

> 新建-模型 -无数据库
php artisanx module:model TestChild Test   
> 新建-模型 -有数据库
php artisanx module:model-on TestChild Test database -

> 更新-模型 过滤字段 -有数据库
php artisanx module:model-fields TestChild Test database -
> 更新-转化器 过滤字段 -有数据库
php artisanx module:trans-fields TestChild Test database - 

> 创建模块-业务指令 -无数据库
php artisanx module:cmd TestChild Test     
> 创建模块-业务指令 -有数据库
php artisanx module:cmd-on TestChild Test database -

> 创建模块-消息队列 -无数据库
php artisanx module:job TestChild Test
> 创建模块-消息队列 -有数据库
php artisanx module:job-on TestChild Test database -

~~~


---

### 4/单元测试

>目录说明
~~~
根据示例模块结构, 配置 api单元测试和 behavior行为测试.
其中,
api单元测试的CURD测试 对应 同名模块子接口中的CURD, 取消注释就能使用.
api -> url 单元测试 同上, 不同之处在于,是预先登录情况下, 通过url请求接口.
behavior行为测试,默认提供 批量新增数据 到同名模块子接口 对应的表中, 可做综合业务流程测试.
~~~
~~~
tests/Modules 根目录
    |__ Demo  模块目录
         |__ api  接口单元测试目录
         |    |__ SampleTest.php  接口单元测试类
         |__ behavior  行为测试目录
         |  |__ SampleBehavior.php  行为测试类
      |__doc 单元测试生成文档目录
         |__sample_table.md 文档

~~~

>示例模块代码定制
~~~
以上示例模块代码,能够根据项目规范定制,这样就可以把最优测试代码整合起来,作为自动生成测试代码的模板.
~~~

>执行方式
~~~
接口-用例
php think unit test/demo/api/SampleTest.php
php think unit clean test/demo/api/SampleTest.php
php think unit stay test/demo/api/SampleTest.php
php think unit test/demo/api/SampleUrlTest.php
php think unit clean test/demo/api/SampleUrlTest.php
php think unit stay test/demo/api/SampleUrlTest.php

行为-用例
php think unit test/demo/behavior/SampleBehavior.php     //默认 不清除 缓存ID和数据
php think unit clean test/demo/behavior/SampleBehavior.php  //清除 所有 缓存ID和数据
php think unit stay test/demo/behavior/SampleBehavior.php  //清除缓存ID 但保留数据
~~~

>自动生成 基本单元测试
~~~
* php artisanx test:make test                  //创建测试模块+目录结构
* php artisanx test:base TestChild Test             //创建测试接口组 -无数据库
* php artisanx test:base-on TestChildtest            //创建测试接口组 -有数据库
~~~
