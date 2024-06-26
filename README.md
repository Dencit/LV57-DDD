# Laravel-module-5.7-领域设计版本

___
## 项目信息

### 原始项目路径: https://dencit.coding.net/public/php-framework/lv57-ddd/git/files
### 作者: Dencit - 陈鸿扬
### 邮箱: 632112883@qq.com

---
### 项目架构

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

Demo 模块目录内 代码调用顺序
(
业务简单时,业务逻辑写在logic类; 业务复杂 或 跨用户端时, logic类中共性业务,必须抽象为srv服务函数,再由logi类进行组合, 
以促进 "应用层低耦合,领域层高内聚"的条件, 利于后期迭代扩展. 
)

|端口层                        |应用层                               | |领域层                              |基础                        |
|                              |                                     | |                                    |                            |
|-----------MVC_架构-----------|-应用层,logic类代码 高冗余-----------| |-公共代码,业务耦合,没按模块划分-----|-底层对象统一控制-----------|
|                              |-散弹式业务代码,1次迭代n个地方修改---| |-迭代修改,容易产生关联错误          |                            |
|                              |                                     | |                                    |                            |
|                              |                                     | |                                    |                            |
|  route --> demo -->          | controler --> logic -->             | | -->                                | model                      |
|                              |                |                    | | --> pack                           | edoc/es_orm                |
|                              |                |                    | | --> helper                         | entity(curl/RPC/OpenAPI)   |
|                              |                |                    | | --> common                         |                            |
|                              |                |                    | |                                    |                            |
|                              |                |                    | |                                    |                            |
|                              | job -->        |                    | |                                    |                            |
|                              |                |                    | |                                    |                            |
|                              | console -->    |                    | |                                    |                            |
|                              |                                     | |                                    |                            |
|                              |                                     | |                                    |                            |
|------------DDD_架构----------|-应用层只组合领域层对象,低代码冗余---| |-高复用代码,solid原则,按模块划分----|-底层对象统一控制-----------|
|                              |-高复用方法,不怕应用层拷贝修改-------| |-可将MVC业务对象抽出,渐进式重构-----|                            |
|                              |                                     | |                                    |                            |
| route --> demo --> port -->  | controler --> request               | |                                    |                            |
|                              |   ^               |                 | |                                    |                            |
|                              |   |             logic -->           | | -> srv |--> repository -->         | model                      |
|                              |   |               |                 | |    |   |                           |                            |
|                              |   |____________ trans               | |    |   |--> aggr |-->              | edoc/es_orm                |
|                              |                                     | |    |   |         |-->              | entity(curl/RPC/OpenAPI)   |
|                              |                                     | |    |   |                           |                            |
|                              |                                     | |    |   |--> enum                   |                            |
|                              | job -->                             | | ___|   |--> error                  |                            |
|                              |                                     | |    |                               |                            |
|                              | console -->                         | | ___|                               |                            |
|                              |                                     | |                                    |                            |
|                              |                                     | |                                    |                            |

以上示例模块代码,能够根据项目规范定制,这样就可以把最优代码整合起来,作为自动生成代码的模板.

~~~

---
### 项目目录

> 数据迁移工具
~~~
./database/Migrations/*
./modules/[模块名]/Database/Migrations/*
~~~

> artisanx 代码生成工具 文档
~~~
./artisanx.md
~~~

> ES_ORM 查询工具使用说明
~~~
./extend/elastic/reme.md
~~~

> 接口 编码守则参考
~~~
./RE-WORK.md           
~~~

> 接口 文档&注释 命名规范
~~~
./RE_DOC.md
~~~

