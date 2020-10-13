# swoft mongo db pool

```
composer require yumufeng/swoft-mongodb
```

## config 
在/config/properties目录里面创建文件 mongodb.php
添加以下内容
```php
return [
    'appName'          => 'swoft-mongodb-connection',
    'minActive'        => 5,
    'maxActive'        => 1000,
    'userName'         => '',
    'password'         => '',
    'host'             => '127.0.0.1',
    'port'             => 27017,
    'dbName'           => 'test',
    'authMechanism'    => 'SCRAM-SHA-256',
    //设置复制集,没有不设置
    //'replica'          => 'rs0'
    
];
```

## config/properties/app.php 添加
```
'mongo' => require __DIR__ . DS . 'mongodb.php,
'components' => [
        'custom' => [
            // Your package namespace.
            'SwoftMongo\\',

        ],
    ]
```

# 使用案例

\SwoftMongo\MongoDB\Mongo 下面的静态方法直接使用

#### **tips:** 
查询的值，是严格区分类型，string、int类型的哦

### 新增

单个添加
```php
$insert = [
            'account' => '',
            'password' => ''
];
Mongo::insert('fans',$insert);
```

批量添加
```php
$insert = [
            [
                'account' => '',
                'password' => ''
            ],
            [
                'account' => '',
                'password' => ''
            ]
];
Mongo::insertAll('fans',$insert);
```

### 查询

```php
$where = ['account'=>'1112313423'];
$result = Mongo::fetchAll('fans', $where);
```

### 分页查询
```php
$list = Mongo::fetchPagination('article', 10, 0, ['author' => $author]);
```
```

### 更新
```php
$where = ['account'=>'1112313423'];
$updateData = [];

Mongo::updateColumn('fans', $where,$updateData); // 只更新数据满足$where的行的列信息中在$newObject中出现过的字段
Mongo::updateRow('fans',$where,$updateData);// 更新数据满足$where的行的信息成$newObject
```
### 删除

```php
$where = ['account'=>'1112313423'];
$all = true; // 为false只删除匹配的一条，true删除多条
Mongo::delete('fans',$where,$all);
```

### count统计

```php
$filter = ['isGroup' => "0", 'wechat' => '15584044700'];
$count = Mongo::count('fans', $filter);
```



### Command，执行更复杂的mongo命令

**sql** 和 **mongodb** 关系对比图

|   SQL  | MongoDb |
| --- | --- |
|   WHERE  |  $match (match里面可以用and，or，以及逻辑判断，但是好像不能用where)  |
|   GROUP BY  | $group  |
|   HAVING  |  $match |
|   SELECT  |  $project  |
|   ORDER BY  |  $sort |
|   LIMIT  |  $limit |
|   SUM()  |  $sum |
|   COUNT()  |  $sum |

```php

$pipeline= [
            [
                '$match' => $where
            ], [
                '$group' => [
                    '_id' => [],
                    'groupCount' => [
                        '$sum' => '$groupCount'
                    ]
                ]
            ], [
                '$project' => [
                    'groupCount' => '$groupCount',
                    '_id' => 0
                ]
            ]
];

$count = Mongo::command('fans', $pipeline);
```