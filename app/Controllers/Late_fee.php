<?php namespace App\Controllers;

use App\Models\Admin\Payment_model;

/**
 * Late fee job: charges the late fee to parents who still owe for the current semester.
 * Run by cron once a year: php public/index.php late_fee, or late_fee?p=<cron_token>
 */
class Late_fee extends BaseController
{
  public function index()
  {
    if (!xilin_cron_allowed()) {
      return $this->response->setStatusCode(403)->setBody('no access');
    }
    $payment = new Payment_model();
    $result = $payment->applyLateFees();
    echo 'Semester ' . $result['semester_id'] . ': late fee applied to ' . count($result['charged']) . ' parents.';
  }
}
