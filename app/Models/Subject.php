<?php
namespace App\Models;

use CodeIgniter\Model;

class Subject extends Model
{

  function __construct()
  {
    parent::__construct();
  }

  function getSubjects()
  {
    $sql = "select * from subjects
              where status = 1
              order by seq";
    return $this->db->query($sql)->getResultArray();
  }
}
