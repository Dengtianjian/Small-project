<?php

if (!defined("CORE")) {
  responseJSON(403.8, [
    "error" => "非法访问 (not code:database)"
  ]);
}

include_once(ROOT . "/config.php");

class PDODB
{

  static private function connect()
  {
    $conn = new PDO("mysql:dbname=" . DB['name'] . ";host=" . DB['host'] . ";", DB['user'], DB['password']);
    $conn->query("set names " . DB['charset']);
    return $conn;
  }

  static function responseDBError($result)
  {
    if (isset($result['error'])) {
      switch ($result['code']) {
        case 1:
        case 2:
          responseJSON(500, [
            "error" => "服务器内部错误"
          ]);
          break;
      }
    }
  }

  /**
   * 字符串转 排序语句
   *
   * @param string $orderStr 逗号分割的字符串 包含排序字段和排序模式 id,asc|desc
   * @return string 排序语句
   */
  static function orderSql($orderStr)
  {
    $order = explode(",", $orderStr);
    $orderField = $order[0];
    if (count($order) > 1) {
      $orderMode = $order[1];
      $sql = " ORDER BY $orderField $orderMode";
    } else {
      $sql = " ORDER BY $orderField";
    }
    return $sql;
  }

  /**
   * 参数绑定SQL语句
   * 给表名称加上数据库前缀
   *
   * @param [String] $tableName 数据表名称 &t 作为替换格式化
   * @param String $sql 查询的SQL语句 加上?就是绑定参数
   * @param [Array] $bindParams ? 绑定的参数 对应SQL语句里的 ?数量
   * @return 返回语句对象
   */
  static function sqlBindParam($sql, $tableName, $bindParams = null)
  {
    $pdo = self::connect();
    if ($tableName) {
      $sql = str_replace("&t", "`pre_" . $tableName . "`", $sql);
    }
    $sth = $pdo->prepare($sql);
    if ($bindParams) {
      for ($i = 0; $i < count($bindParams); $i++) {
        $sth->bindParam($i + 1, $bindParams[$i]);
      }
    }
    return $sth;
  }

  /**
   * 执行 Sql 语句
   *
   * @param string $sql sql语句
   * @param [string] $tableName sql语句里替换的数据表名称
   * @param [array] $bindparams sql语句里替换的参数
   * @return object
   */
  static function query($sql, $tableName = null, $bindparams = null)
  {
    $sth = self::sqlBindParam($sql, $tableName, $bindparams);
    $sth->execute();
    return $sth;
  }

  /**
   * 以数组类型返回查找到的全部数据 最好加上 LIMIT
   *
   * @param [String] $tableName 数据表名称 &t 作为替换
   * @param String $sql 查询的SQL语句 加上?就是绑定参数
   * @param [Array] $params ? 绑定的参数 对应SQL语句里的 ?数量
   * @return Array|null 查询结果
   */
  static function fetchAll($sql, $tableName = null, $params = null)
  {
    $sth = self::query($sql, $tableName, $params);
    $result = $sth->fetchAll(PDO::FETCH_ASSOC);
    if ($sth->errorInfo()[1]) {
      return [
        "code" => "2",
        "error" => "Sql execution error.",
        "errorInfo" => $sth->errorInfo()
      ];
    }
    if ($result) {
      return $result;
    } else {
      return 0;
    }
  }

  /**
   * 查询一条数据 最好加上LIMIT
   *
   * @param [string] $tableName 数据表名称 &t 作为替换
   * @param [string] $sql 查询的SQL语句 加上?就是绑定参数
   * @param [array] $params ? 绑定的参数 对应SQL语句里的 ?数量
   * @return Array|null 查询结果
   */
  static function fetchOne($sql, $tableName = null, $params = null)
  {
    $sth = self::query($sql, $tableName, $params);
    $result = $sth->fetch(PDO::FETCH_ASSOC);
    if ($sth->errorInfo()[1]) {
      return [
        "code" => "2",
        "error" => "Sql execution error.",
        "errorInfo" => $sth->errorInfo()
      ];
    }
    if ($result) {
      return $result;
    } else {
      return 0;
    }
  }

  static function fetchCount($tableName, $attachSql = null, $bindParams = null)
  {
    $sql = "SELECT count(1) FROM &t $attachSql";

    $sth = self::query($sql, $tableName, $bindParams);
    if ($sth->errorInfo()[1]) {
      return [
        "code" => "2",
        "error" => "Sql execution error.",
        "errorInfo" => $sth->errorInfo()
      ];
    }
    $result = $sth->fetch(PDO::FETCH_NUM);
    if ($result) {
      return $result[0];
    }
    return 0;
  }

  /**
   * 插入数据处理
   *
   * @param String? $tableName 数据表名称
   * @param Array $fieldValue 字段以及值
   * @param boolean $isIGNORE 是否加入 INGORE 不存在就插入 存在就返回0
   * @return Statement 语句对象
   */
  static function insertHandle($fieldValue, $tableName = null, $isIGNORE = false)
  {
    $pdo = self::connect();
    $field = "";
    $paramFormat = "";
    $fieldIndex = 1;
    $params = [];
    $fields = [];
    foreach ($fieldValue as $key => $fv) {
      array_push($params, $fv);
      array_push($fields, $key);
      if ($fieldIndex == count($fieldValue)) {
        $field .= $key;
        $paramFormat .= sprintf("?", $key);
        break;
      }
      $paramFormat .= sprintf("?,", $key);
      $field .= sprintf("%s, ", $key);
      $fieldIndex++;
    }
    if ($isIGNORE) {
      $sql = sprintf("INSERT IGNORE INTO %s_%s (%s) VALUES (%s)", DB['tablePre'], $tableName, $field, $paramFormat);
    } else {
      $sql = sprintf("INSERT INTO %s_%s (%s) VALUES (%s)", DB['tablePre'], $tableName, $field, $paramFormat);
    }

    $sth = $pdo->prepare($sql);

    for ($i = 0; $i < count($fieldValue); $i++) {
      $sth->bindParam($i + 1, $fieldValue[$fields[$i]]);
    }

    return [
      "sth" => $sth,
      "pdo" => $pdo
    ];
  }

  /**
   * 插入单条数据
   *
   * @param Array $fieldValue 插入的字段和值
   * @param String $tableName 插入的表名称
   * @param string $primaryKey 返回数据所在列
   * @return String 插入的ID
   */
  static function insert($fieldValue, $tableName = null, $primaryKey = null)
  {
    $pdoObj = self::insertHandle($fieldValue, $tableName, true);

    $result = $pdoObj['sth']->execute();
    if (!$result) {
      return [
        "code" => "1",
        "error" => "The insertion failed and there is a duplicate.",
        "info" => $result
      ];
    }
    if ($pdoObj['sth']->errorInfo()[1]) {
      return [
        "code" => "2",
        "error" => "Sql execution error.",
        "errorInfo" => $pdoObj['sth']->errorInfo()
      ];
    } else {
      if ($primaryKey) {
        return $pdoObj['pdo']->lastInsertId($primaryKey);
      } else {
        return $pdoObj['pdo']->lastInsertId();
      }
    }
  }

  /**
   * 删除数据
   *
   * @param [string] $tableName 操作的数据库表名称
   * @param [string] $condition 条件语句 加上 WHERE
   * @param [array] $bindParams 条件语句绑定的参数 纯值数组
   * @return array|boolean 有错误就返回error 否侧就是true
   */
  static function delete($tableName = null, $condition, $bindParams = null)
  {
    $sql = "DELETE FROM &t $condition";
    $sth = self::sqlBindParam($sql, $tableName, $bindParams);
    $sth->execute();
    if ($sth->errorInfo()[1]) {
      return [
        "code" => "2",
        "error" => "Sql execution error.",
        "errorInfo" => $sth->errorInfo()
      ];
    } else {
      return true;
    }
  }

  static function update($tableName = null, $attachSql = null, $params)
  {
    $sql = "UPDATE &t SET ";
    $forIndex = 1;
    foreach ($params as $key => $value) {
      if ($forIndex == count($params)) {
        $sql .= "$key=? ";
        break;
      }
      $sql .= "$key=?, ";
      $forIndex++;
    }
    $sql .= $attachSql;
    $bindParams = array_values($params);
    $sth = self::sqlBindParam($sql, $tableName, $bindParams);
    $result = $sth->execute();
    if ($sth->errorInfo()[1]) {
      return [
        "code" => "2",
        "error" => "Sql execution error.",
        "errorInfo" => $sth->errorInfo()
      ];
    } else {
      if ($result && $sth->rowCount() > 0) {
        return 1;
      } else {
        return 0;
      }
    }
  }
};