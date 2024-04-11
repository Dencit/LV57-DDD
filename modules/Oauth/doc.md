### 模块规划内容
~~~
此模块,适合存放 XXX 等, 各种 XXX 数据表的接口.
假如未来 XXX 越来越多,集中存放便于管理.
~~~

### 错误码区间
~~~
> 每个模块 间隔1000位,模块内每个数据单元 间隔100位, 需自定义.
> 200000 - 200999
~~~

### 模块结构说明
~~~
modules 根目录
    |__ Demo  模块目录
       |__ Providers 模块配置目录
       |__ Resources 模块资源目录
       |__ Routes 模块路由目录
       |__ Databse  数据迁移目录
       |__ Config  状态设置目录
       |   |__ SampleStatus.php  状态设置类
       |__ Exceptions  异常设置目录
       |   |__ SampleErrorCode.php  错误码设置类
       |__ Consoles 业务指令目录
       |   |__ SampleCmd.php  业务指令类
       |__ Validates 输入过滤目录
       |   |__ SampleValidate.php  输入过滤类
       |__ Controllers  控制器设置目录
       |   |__SampleController.php  控制器设置类
       |__ Models 数据模型目录
       |   |__ SampleModel.php 数据模型类
       |__ Services 业务逻辑目录
       |   |__ SampleService.php 业务逻辑类
       |__ Logics 子业务逻辑目录
       |   |__ SampleLogic.php 子业务逻辑类
       |__ Transformers 输出过滤目录
       |   |__ SampleTransformer.php 输出过滤类

~~~

### 主要原则
~~~
从面向资源设计的resful路由单元，到模块单元，到数据表单元一一对应，统一成一个个单元的数据资源，再也不是重业务逻辑的设计。
每个数据资源提供 - 增/删/改/查detail/查list 标准类型接口.
[复合权限,权限和业务分离,权限不同数据相同的接口,放权限路由组里就可以],
可通过url控制的_where表达式，进行数据资源的范围查询[不必再开发],
可通过url控制的_include关联模型，进行非jion联表查询[需要设置模型],
实现最大化解耦。
~~~

### 备注
~~~

~~~
