<?php

class AddressesCache
{
  protected $redis;
  protected $data=[];
  protected $rlist;
  protected $key;
  protected $connection = false;

  public function __construct(Predis\Client $redis)
  {
    try {
      $redis->ping();
      $this->redis = $redis;
      $this->connection = true;
    } catch (Exception $e) {
      $this->connection = false;
    }
  }

  public function connect()
  {
    return $this->connection;
  }

  public function createListKey(int $cityId, string $keyword)
  {
    if (mb_strlen($keyword)<10) {
      $keyword = base64_encode($keyword);
    } else {
      $keyword = md5($keyword);
    }

    $this->key = 'c:'.$cityId.':a:'.$keyword;

    return $this;
  }

  public function getListKey()
  {
    return $this->key;
  }

  public function setData(array $data)
  {
    $this->data = $data;

    return $this;
  }

  public function getData() : array
  {
    return $this->data;
  }

  public function saveData(array $data = NULL)
  {
    $redis = $this->redis;
    if($data == NULL) {
      $data = $this->data;
    }

    foreach ($data as $item) {
      $rlist=$redis->lrange($this->key, 0, -1);
      $key ="a:".$item['id'];
      $search = array_search($key,$rlist);
      if (!is_int($search)) {
        $redis->rpush($this->key, $key);
      }

      $redis->hmset($key, $item);
    }

    return $this;
  }

  public function requestList()
  {
    $this->rlist=$this->redis->lrange($this->key, 0, -1);

    return $this;
  }

  public function requestData()
  {
    $data = [];
    $rlist = $this->rlist;
    if (count($rlist)>0) {
      foreach ($rlist as $item) {
        $data[] = $this->redis->hgetall($item);
      }
    }
    $this->data = $data;

    return $this;
  }
}
