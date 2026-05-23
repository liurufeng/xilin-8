<?php
namespace App\Models;

use CodeIgniter\Model;
class Classes extends Model
{

  function __construct()
  {
    parent::__construct();
  }

  function getClasses()
  {
    $sql = "select c.*, t.en_name, t.email, t.desc_link, (select count(*) from studentclasses sc where sc.class_id = c.class_id and
     sc.deleted = 0) as enrolled from classes c
            join teachers t on t.teacher_id = c.teacher_id
            where c.status = 1
            and c.class_id in (select cc.class_id from classes cc where cc.semester_id = " . session()->get('current_semester')['semester_id'] . ")
            order by seq, class_code";

    return $this->db->query($sql)->getResultArray();
  }
}
