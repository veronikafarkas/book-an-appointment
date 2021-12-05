<?php
include_once('storage.php');

class DataStorage extends Storage {
  public function __construct() {
    parent::__construct(new JsonIO('datas.json'));
  }
}
?>