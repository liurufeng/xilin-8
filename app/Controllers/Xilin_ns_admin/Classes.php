<?php namespace App\Controllers\Xilin_ns_admin;

use App\Models\{Semester, Subject};

class Classes extends MY_Controller
{

  function __construct()
  {

    $this->_classname = 'Classes';
    $this->_methods = array(
      'index' => 'list',
      'add' => 'add',
      'edit' => 'edit',
      'del' => 'del',
    );
    $this->_issystem = TRUE;
    parent::_Mycontroller();
    parent::_check_login();
  }

  /**
   *
   *
   */
  function index()
  {
    $semester = new Semester();
    $data['semesters'] = $semester->getSemesters();
    $semester->getCurrentSemester();

    $semester_id = $this->request->getVar('semester_id'); //$this->request->getVar('semester_id');
    $semester_id = str_replace('.php','',$semester_id);
    if(isset($semester_id) && !empty($semester_id)) {
      session()->set(array('semester_id' => $semester_id));
      session()->set(array('semester_id' => $semester_id));
      $where =' and se.semester_id='.$semester_id;
    } else {
      $current_semester = session()->get('current_semester');
      $current_semester_id = $current_semester['semester_id'];
      session()->set(array('semester_id' => $current_semester_id));
      $where =' and se.semester_id='.$current_semester_id;
    }

    $sql = "select c.*,s.subject_name subject,t.en_name teacher,concat(se.semester_name,' ',se.semester_year) semester
				from classes c
				join subjects s on c.subject_id=s.subject_id
				join teachers t on t.teacher_id=c.teacher_id
				join semester se on se.semester_id=c.semester_id
				where c.status <= 1 " . $where .
        " order by s.seq, c.class_code";

    //$this->load->model('subject');
    $subjects = new Subject();
    $data['subjects'] = $subjects->getSubjects();
    $data['list'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/classes/index.php', $data);
  }

  /**
   * Add new class
   */
  function add()
  {
    if ($this->request->getVar('dopost') == 'add') {
      // 判断class是否存在
      if ($this->request->getVar('class_code')) {
        $class_code = trim($this->request->getVar('class_code'));
        $sql = " SELECT 1 FROM  classes WHERE class_code = '$class_code' and status=1 and semester_id = ".$this->request->getVar('semester_id');
         $rst = $this->db->query($sql)->getResultArray();
        if (is_array($rst) && count($rst) > 0) {
          ShowMsg('Classes Code already exists', $_SESSION['admin_path'].'classes/add');
        }
        $insertdata['subject_id'] = $this->request->getVar('subject_id');
        $insertdata['semester_id'] = $this->request->getVar('semester_id');
        $insertdata['teacher_id'] = $this->request->getVar('teacher_id');
        $insertdata['class_code'] = $this->request->getVar('class_code');
        $insertdata['class_name'] = $this->request->getVar('class_name');
        $insertdata['notes'] = $this->request->getVar('notes');
        $insertdata['syl_link'] = $this->request->getVar('syl_link');
        $insertdata['tuition'] = $this->request->getVar('tuition');
        $insertdata['late_tuition'] = $this->request->getVar('late_tuition');
        $insertdata['book_fee'] = $this->request->getVar('book_fee');
        $insertdata['late_book_fee'] = $this->request->getVar('late_book_fee');
        $insertdata['material_fee'] = $this->request->getVar('material_fee');
        $insertdata['meeting_time'] = $this->request->getVar('meeting_time');
        $insertdata['classroom'] = $this->request->getVar('classroom');
        $insertdata['student_amount_limit'] = $this->request->getVar('student_amount_limit');
        $insertdata['new_class_flag'] = 0; //$this->request->getVar('new_class_flag');
        $insertdata['seq'] = $this->request->getVar('seq');
        //$this->db->insert('classes', $insertdata);
        $this->db->table('classes')
          ->insert($insertdata);
        ShowMsg("add success!", $_SESSION['admin_path'].'classes/index?semester_id='.$this->request->getVar('semester_id'));
      }
    }
    $sql = "select subject_id,subject_name
				from  subjects
				where status = 1";
    $data['subject'] = $this->db->query($sql)->getResultArray();

    $sql = "select semester_id,concat(semester_name,semester_year) semester_name
				from  semester
				where status = 1";
    $data['semester'] = $this->db->query($sql)->getResultArray();

    $sql = "select teacher_id,en_name
				from  teachers
				where status = 1";
    $data['teachers'] = $this->db->query($sql)->getResultArray();
    echo view($_SESSION['tm'].'admin/classes/add.php', $data);
  }

  /**
   * 编辑class
   */
  function edit()
  {
    if ($this->request->getVar('dopost') == 'save') {
      if ($this->request->getVar('class_code')) {
        //$class_code = trim($this->request->getVar('class_code']);

        $insertdata['subject_id'] = $this->request->getVar('subject_id');
        $insertdata['semester_id'] = $this->request->getVar('semester_id');
        $insertdata['teacher_id'] = $this->request->getVar('teacher_id');
        $insertdata['class_code'] = $this->request->getVar('class_code');
        $insertdata['class_name'] = $this->request->getVar('class_name');
        $insertdata['notes'] = $this->request->getVar('notes');
        $insertdata['syl_link'] = $this->request->getVar('syl_link');
        $insertdata['tuition'] = $this->request->getVar('tuition');
        $insertdata['late_tuition'] = $this->request->getVar('late_tuition');
        $insertdata['book_fee'] = $this->request->getVar('book_fee');
        $insertdata['late_book_fee'] = $this->request->getVar('late_book_fee');
        $insertdata['material_fee'] = $this->request->getVar('material_fee');
        $insertdata['meeting_time'] = $this->request->getVar('meeting_time');
        $insertdata['classroom'] = $this->request->getVar('classroom');
        $insertdata['student_amount_limit'] = $this->request->getVar('student_amount_limit');
        $insertdata['new_class_flag'] = 0; //$this->request->getVar('new_class_flag');
        $insertdata['status'] = $this->request->getVar('status');
        $insertdata['seq'] = $this->request->getVar('seq');
        $wheredata['class_id'] = $this->request->getVar('class_id');

        //$this->db->update('classes', $insertdata, $wheredata);
        $this->db->table('classes')
          ->where($wheredata)
          ->update($insertdata);
        ShowMsg('edit success!', $_SESSION['admin_path'].'classes/index?semester_id='.$this->request->getVar('semester_id'));
      }
    }
    $sql = "select subject_id,subject_name
				from subjects
				where status = 1";
    $data['subject'] = $this->db->query($sql)->getResultArray();

    $sql = "select semester_id,concat(semester_name,semester_year) semester_name
				from semester
				where status = 1";
    $data['semester'] = $this->db->query($sql)->getResultArray();

    $sql = "select teacher_id,en_name
				from teachers
				where status = 1";
    $data['teachers'] = $this->db->query($sql)->getResultArray();
    $class_id = (int)$this->request->getVar('class_id');
    if (empty($class_id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'classes/index');
    }
    $sql = " SELECT * FROM classes WHERE class_id = $class_id";
    $data['info'] = $this->db->query($sql)->getRowArray();
    echo view($_SESSION['tm'].'admin/classes/edit.php', $data);
  }

  /**
   *  删除class
   */
  function del()
  {

    $class_id = (int)$this->request->getVar('class_id');
    if (empty($class_id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'classes/index');
    }
    $sql = " update   classes set status = '2' WHERE class_id = $class_id";
    $this->db->query($sql);
    ShowMsg('delete success!', $_SESSION['admin_path'].'classes/index?semester_id='.session()->get('semester_id'));
  }

  /**
   *  copy
   */
  function copy()
  {
    $class_id = (int)$this->request->getVar('class_id');
    if (empty($class_id)) {
      ShowMsg('param error', $_SESSION['admin_path'].'classes/index');
    }
    $sql = "insert  classes (subject_id,semester_id,teacher_id,class_code,class_name,notes,syl_link,
        tuition,late_tuition,book_fee,late_book_fee,material_fee,meeting_time,classroom,student_amount_limit,new_class_flag,seq)
				select subject_id,semester_id,teacher_id,class_code, CONCAT(class_name, ' - COPY'),notes,syl_link,
				tuition,late_tuition,book_fee,late_book_fee,material_fee,meeting_time,classroom,student_amount_limit,new_class_flag,seq from 
        classes where class_id = $class_id";
    $this->db->query($sql);
    ShowMsg('copy success!', $_SESSION['admin_path'].'classes/index?semester_id='.session()->get('semester_id'));
  }

  /**
   *  copy all semester's classes
   */
  function copyall()
  {
    $from_semester_id = (int)$this->request->getVar('from_semester_id');
    $semester_id = (int)$this->request->getVar('semester_id');
    if (empty($from_semester_id) || empty($semester_id) || $from_semester_id == $semester_id) {
      ShowMsg('param error', $_SESSION['admin_path'].'classes/index');
    }

    $sql = " SELECT * FROM classes WHERE semester_id = $from_semester_id and status = 1";
    $all_classes = $this->db->query($sql)->getResultArray();;

    foreach($all_classes as $class) {
      $sql = "insert classes (subject_id,semester_id,teacher_id,class_code,class_name,notes,syl_link,
          tuition,late_tuition,book_fee,late_book_fee,material_fee,meeting_time,classroom,student_amount_limit,new_class_flag,seq)
          select subject_id,$semester_id,teacher_id,class_code, class_name, notes,syl_link,
          tuition,late_tuition,book_fee,late_book_fee,material_fee,meeting_time,classroom,student_amount_limit,new_class_flag,50
          from classes  where class_id = " . $class['class_id'];
      $this->db->query($sql);
    }
    ShowMsg('copy success!', $_SESSION['admin_path'].'classes/index');
  }
}

