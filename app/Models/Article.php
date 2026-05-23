<?php
namespace App\Models;

use CodeIgniter\Model;
class Article extends Model
{

  function __construct()
  {
    parent::__construct();
  }

  function getArticle($id)
  {
    $sql = "select la.title,lac.body
        from archives la
				join addonarticle lac on la.id = lac.aid
				where la.status = 1
				and unitid = $id
				order by id desc
				limit 1";
    return $this->db->query($sql)->getResultArray();

  }

}
