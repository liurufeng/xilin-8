<?php namespace App\Models\Admin;
use CodeIgniter\Model;
/**
 *
 * dmp 数据接口
 * @author zhangjian zhangjian1895#outlook.com
 *
 */
class Dmp_model extends Model
{
  /**
   *
   * key
   */
  private $_dmpkey = '6179291af6fa45f03a3887004ed8ba96';
  /**
   *
   * 缓存名称
   * @var
   */
  private $_cachename = 'dmp';

  public function __construct()
  {

    parent::__construct();
  }

  /**
   * 获取经销商数据
   */
  public function getDealers()
  {

    return self::_getc('dealers');
  }

  /**
   * 更新dealers数据
   */
  public function upDealers()
  {

    return self::_getDmp('dealers');
  }

  /**
   * 获取地区数据
   */
  public function getArea()
  {

    return self::_getc('area');
  }

  /**
   * 更新地区数据
   */
  public function upArea()
  {

    return self::_getDmp('area');
  }

  /**
   * 获取地区数据
   */
  public function getCartype()
  {

    $type = 'carsmodels';
    $this->load->driver('cache');
    $_cachename = md5($this->_cachename . $type);

    $info = $this->cache->file->get($_cachename . '_all');

    return $info;

  }

  /**
   * 更新车型数据
   */
  public function upCars()
  {

    $carinfo = self::_getDmp('cars');
    foreach ($carinfo->car_types as $k => &$v) {

      if ($v->id) {
        $v->carmodels = self::_getcarmodelsDmp($v->id);

      }
    }
    //dump($carinfo);exit;
    $type = 'carsmodels';
    $this->load->driver('cache');
    $_cachename = md5($this->_cachename . $type);

    $this->cache->file->save($_cachename . '_all', $carinfo, 100000);

    return true;
  }

  /**
   * 更新车型详细
   */
  public function upCarsconfigJsData()
  {

    $nodes = self::getCartype();
    if (empty($nodes)) {
      return false;
    }
    // dump($nodes);exit;
    // 获取车型数据
    $sql = "SELECT * FROM " . $this->db->dbprefix('cartype') . " WHERE 1";

    $carinfo = $this->db->query($sql)->result_array();
    $this->load->model('info_model');
    foreach ($carinfo as $k => &$v) {
      if ($v['picid']) {
        $v['picid1arr'] = $this->info_model->getAttinfo($v['picid']);
      }
      $carinfo_a[$v['cartypeid']] = $v;
    }

    $tmp = $t1 = $t2 = $t3 = $t4 = '';
    $pro = $city = array();

    foreach ($nodes->car_types as $v1) {

      $t1 .= "car_i++;car_j=-1;\r\n";
      $t1 .= "car[car_i]=['" . $v1->name . "','" . $v1->name . "','" . $carinfo_a[$v1->id]['picid1arr']['filename'] . "','" . $carinfo_a[$v1->id]['url1'] . "','" . $v1->web_site . "',new Array()];\r\n";
      if (count($v1->carmodels) > 0) {

        foreach ($v1->carmodels as $v2) {
          if ($v2->detail_info->carcolor) {
            $colors = array();
            $colors = explode(',', $v2->detail_info->carcolor);

            if (count($colors) > 0) {
              $t3array = array();
              foreach ($colors as $v3) {
                $colors_text = array();
                $colors_text = explode('(', $v3);

                if (!empty($colors_text[0])) {
                  $colors_text[1] = str_replace(")", "", $colors_text[1]);
                  $colors_text[1] = str_replace("#", "", $colors_text[1]);

                  $t3array[] = "$colors_text[1]||$colors_text[0]";

                  //$t3 .= "$colors_text[1]||$colors_text[0]||";
                }
              }

              $t3 = implode('||', $t3array);

            }
          }

          if ($v2->detail_info->innercolor) {
            $colors = array();
            $colors = explode(',', $v2->detail_info->innercolor);
            if (count($colors) > 0) {
              $t2array = array();
              foreach ($colors as $v3) {
                $colors_text = array();
                $colors_text = explode('(', $v3);

                if (!empty($colors_text[0])) {

                  $colors_text[1] = str_replace(")", "", $colors_text[1]);
                  $colors_text[1] = str_replace("#", "", $colors_text[1]);

                  $t2array[] = "$colors_text[1]||$colors_text[0]";
                }
              }
              $t2 = implode('||', $t2array);

            }
          }

          if ($v2->detail_info->select) {
            $selects = explode(',', $v2->detail_info->select);
            if (count($selects) > 0) {
              $t4 = implode('||', $selects);
            }
          }
          $v2->detail_info->main_price = $v2->detail_info->main_price ? $v2->detail_info->main_price : '0';
          $t1 .= "car_j++;car[car_i][5][car_j]=['" . $v2->name . "','" . $v2->name . "','" . $t3 . "','" . $t2 . "','" . $t4 . "'," . $v2->detail_info->main_price . "];\r\n";
          $t3 = '';
          $t2 = '';
          $t4 = '';

        }
      }
      $t1 .= "\r\n";
    }

    $tmp .= "// JavaScript Document\r\n";
    $tmp .= "var car=new Array();var car_i=-1;var car_j=-1;\r\n\r\n";

    $tmp .= "" . $t1 . "\r\n";

    //$tmp = substr($tmp,0,-3);
    $tmp .= "\r\n";

    $file = FCPATH . '/public/dyk/common/car.js';
    file_put_contents($file, $tmp);
    return true;

  }

  /**
   * 更新车型详细
   */
  public function upCarsconfigJsDatacar_2()
  {

    $nodes = self::getCartype();
    if (empty($nodes)) {
      return false;
    }
    // dump($nodes);exit;
    // 获取车型数据
    $sql = "SELECT * FROM " . $this->db->dbprefix('cartype') . " WHERE 1";

    $carinfo = $this->db->query($sql)->result_array();
    $this->load->model('info_model');
    foreach ($carinfo as $k => &$v) {
      if ($v['picid']) {
        $v['picid1arr'] = $this->info_model->getAttinfo($v['picid']);
      }
      $carinfo_a[$v['cartypeid']] = $v;
    }

    $tmp = $t1 = $t2 = $t3 = $t4 = '';
    $pro = $city = array();

    foreach ($nodes->car_types as $v1) {

      $t1 .= "car_i++;car_j=-1;\r\n";
      $t1 .= "car[car_i]=['" . $v1->id . "','" . $v1->name . "','" . $carinfo_a[$v1->id]['picid1arr']['filename'] . "','" . $carinfo_a[$v1->id]['url1'] . "','" . $v1->web_site . "',new Array()];\r\n";
      if (count($v1->carmodels) > 0) {

        foreach ($v1->carmodels as $v2) {
          if ($v2->detail_info->carcolor) {
            $colors = array();
            $colors = explode(',', $v2->detail_info->carcolor);

            if (count($colors) > 0) {
              $t3array = array();
              foreach ($colors as $v3) {
                $colors_text = array();
                $colors_text = explode('(', $v3);

                if (!empty($colors_text[0])) {
                  $colors_text[1] = str_replace(")", "", $colors_text[1]);
                  $colors_text[1] = str_replace("#", "", $colors_text[1]);

                  $t3array[] = "$colors_text[1]||$colors_text[0]";

                  //$t3 .= "$colors_text[1]||$colors_text[0]||";
                }
              }

              $t3 = implode('||', $t3array);

            }
          }

          if ($v2->detail_info->innercolor) {
            $colors = array();
            $colors = explode(',', $v2->detail_info->innercolor);
            if (count($colors) > 0) {
              $t2array = array();
              foreach ($colors as $v3) {
                $colors_text = array();
                $colors_text = explode('(', $v3);

                if (!empty($colors_text[0])) {

                  $colors_text[1] = str_replace(")", "", $colors_text[1]);
                  $colors_text[1] = str_replace("#", "", $colors_text[1]);

                  $t2array[] = "$colors_text[1]||$colors_text[0]";
                }
              }
              $t2 = implode('||', $t2array);

            }
          }
          if ($v2->detail_info->select) {
            $selects = explode(',', $v2->detail_info->select);
            if (count($selects) > 0) {
              $t4 = implode('||', $selects);
            }
          }
          $v2->detail_info->main_price = $v2->detail_info->main_price ? $v2->detail_info->main_price : '0';
          $t1 .= "car_j++;car[car_i][5][car_j]=['" . $v2->id . "','" . $v2->name . "','" . $t3 . "','" . $t2 . "','" . $t4 . "'," . $v2->detail_info->main_price . "];\r\n";
          $t3 = '';
          $t2 = '';
          $t4 = '';

        }
      }
      $t1 .= "\r\n";
    }
    $tmp .= "// JavaScript Document\r\n";
    $tmp .= "var car=new Array();var car_i=-1;var car_j=-1;\r\n\r\n";

    $tmp .= "" . $t1 . "\r\n";

    //$tmp = substr($tmp,0,-3);
    $tmp .= "\r\n";

    $file = FCPATH . '/public/dyk/common/car_2.js';
    file_put_contents($file, $tmp);
    return true;

  }

  /**
   * 生成js地区数据
   */
  public function setJsData()
  {

    $nodes = self::getArea();
    if (empty($nodes)) {
      return false;
    }
    $tmp = $t1 = $t2 = $t3 = '';
    $pro = $city = array();
    foreach ($nodes as $v1) {
      $t1 .= "$v1->city_id:'$v1->name',";
      if (count($v1->cities) > 0) {
        foreach ($v1->cities as $v2) {
          $t2 .= "$v2->city_id:'$v2->name',";
          if (count($v2->districts) > 0) {
            foreach ($v2->districts as $v3) {
              $t3 .= "$v3->city_id:'$v3->name',";
            }
            $city['0,' . $v1->city_id . ',' . $v2->city_id] = $t3;
            $t3 = '';
          }
        }
        $pro['0,' . $v1->city_id] = $t2;
        $t2 = '';
      }
    }

    $tmp .= "var data = {\r\n";
    $tmp .= "'0':{" . trim($t1, ',') . "},\r\n";
    if ($pro) {
      foreach ($pro as $k => $v) {
        $tmp .= "'$k':{" . trim($v, ',') . "},\r\n";
      }
    }
    if ($city) {
      foreach ($city as $k => $v) {
        $tmp .= "'$k':{" . trim($v, ',') . "},\r\n";
      }
    }
    $tmp = substr($tmp, 0, -3);
    $tmp .= "\r\n}";

    $file = FCPATH . '/public/dyk/js/areaData.js';
    file_put_contents($file, $tmp);
    return true;
  }

  /**
   * 生成js车型数据
   */
  public function setCarJsData()
  {

    $nodes = self::getCartype();
    if (empty($nodes)) {
      return false;
    }
    $tmp = $t1 = $t2 = $t3 = '';
    $pro = $city = array();
    foreach ($nodes->car_types as $v1) {

      $t1 .= "$v1->id:'$v1->name',";
      if (count($v1->carmodels) > 0) {
        foreach ($v1->carmodels as $v2) {
          $t2 .= "$v2->id:'$v2->name',";

          $colors = explode(',', $v2->detail_info->carcolor);

          if (count($colors) > 0) {
            foreach ($colors as $v3) {
              $colors_text = explode('(', $v3);
              if (!empty($colors_text[0])) {

                $t3 .= "$colors_text[0]:'$colors_text[0]',";
              }
            }
            $city['0,' . $v1->id . ',' . $v2->id] = $t3;
            $t3 = '';
          }
        }
        $pro['0,' . $v1->id] = $t2;
        $t2 = '';
      }
    }

    $tmp .= "var data = {\r\n";
    $tmp .= "'0':{" . trim($t1, ',') . "},\r\n";
    if ($pro) {
      foreach ($pro as $k => $v) {
        $tmp .= "'$k':{" . trim($v, ',') . "},\r\n";
      }
    }
    if ($city) {
      foreach ($city as $k => $v) {
        $tmp .= "'$k':{" . trim($v, ',') . "},\r\n";
      }
    }
    $tmp = substr($tmp, 0, -3);
    $tmp .= "\r\n}";

    $file = FCPATH . '/public/dyk/js/cartypesData.js';
    file_put_contents($file, $tmp);
    return true;
  }

  /**
   * 生成js地区数据
   */
  public function setDealerJsData()
  {

    $dealersnodes = self::getDealers();
    $nodes = self::getArea();

    if (empty($dealersnodes) || empty($nodes)) {
      return false;
    }
    foreach ($dealersnodes as $k => $v) {

      $dealersnodes_array[$v->c_id][] = $v;
    }


    $tmp = $t1 = $t2 = $t3 = '';
    $pro = $city = array();
    foreach ($nodes as $v1) {
      $t1 .= "$v1->city_id:'$v1->name',";
      if (count($v1->cities) > 0) {
        foreach ($v1->cities as $v2) {
          $t2 .= "$v2->city_id:'$v2->name',";

          if (count($dealersnodes_array[$v2->city_id]) > 0) {

            foreach ($dealersnodes_array[$v2->city_id] as $v3) {
              $t3 .= "$v3->id:'$v3->name',";
            }
            $city['0,' . $v1->city_id . ',' . $v2->city_id] = $t3;
            $t3 = '';
          }
        }
        $pro['0,' . $v1->city_id] = $t2;
        $t2 = '';
      }
    }

    $tmp .= "var data = {\r\n";
    $tmp .= "'0':{" . trim($t1, ',') . "},\r\n";
    if ($pro) {
      foreach ($pro as $k => $v) {
        $tmp .= "'$k':{" . trim($v, ',') . "},\r\n";
      }
    }
    if ($city) {
      foreach ($city as $k => $v) {
        $tmp .= "'$k':{" . trim($v, ',') . "},\r\n";
      }
    }
    $tmp = substr($tmp, 0, -3);
    $tmp .= "\r\n}";

    $file = FCPATH . '/public/dyk/js/dealersData.js';
    file_put_contents($file, $tmp);
    return true;
  }

  /**
   * 生成js经销商数据
   */
  public function setDealerDatas()
  {

    $dealersnodes = self::getDealers();
    $nodes = self::getArea();

    if (empty($dealersnodes) || empty($nodes)) {
      return false;
    }
    foreach ($dealersnodes as $k => $v) {

      $dealersnodes_array[$v->c_id][] = $v;
    }

    $tmp = $t1 = $t2 = $t3 = '';
    $pro = $city = array();
    $codenum = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');
    foreach ($nodes as $v1) {
      //$t1 .= "$v1->city_id:'$v1->name',";
      if (count($v1->cities) > 0) {
        foreach ($v1->cities as $v2) {
          //$t2 .= "$v2->city_id:'$v2->name',";
          //[{id:110000,datas:[{id:1,name:""},{id:1,name:""}]}]
          if (count($dealersnodes_array[$v2->city_id]) > 0) {

            foreach ($dealersnodes_array[$v2->city_id] as $k => $v3) {
              //dump($k);exit;
              if ($k % 2) {
                $v3->class_dyk = "bg1";
              } else {
                $v3->class_dyk = "bg2";
              }
              $v3->sort = $codenum[$k];
              $v3->url2 = site_url_buy("map/mapone");
              $v3->url1 = site_url_buy("testdrive");
              if (empty($t3)) {
                $t3 = '{';

              } else {
                $t3 .= ',{';
              }
              foreach ($v3 as $key => $val) {

                $t3 .= "\"" . $key . "\":\"" . $val . "\",";

                if ($key == '类别') {
                  $types = array();
                  //$val = '经销商,K5专卖店';
                  $types = explode(',', $val);
                  if (!empty($types)) {

                    if (in_array('经销商', $types)) {
                      $t3 .= "dealer_1:\"<span class=\'is4s\' title=\'4S店\'>4S店</span>\",";
                    } else {
                      $t3 .= "dealer_1:\"\",";
                    }

                    if (in_array('K5专卖店', $types)) {

                      $t3 .= "dealer_2:\"<span class=\'isk5\' title=\'K5专卖店\'>K5专卖店</span>\",";
                    } else {
                      $t3 .= "dealer_2:\"\",";
                    }
                    if (in_array('服务店', $types)) {
                      $t3 .= "dealer_3:\"<span class=\'isservice\' title=\'服务店\'>服务店</span>\",";
                    } else {
                      $t3 .= "dealer_3:\"\",";
                    }
                  }
                }

              }
              $t3 .= "\"dyk_lastone\":\"0\"";
              //$t3 = '{id:'.$v3->id.',name:"'.$v3->name.'",class:"'.$v3->class.'",address:"'.$v3->address.'",place:"'.$v3->place.'",district:"'.$v3->district.'",
              //	p_id:"'.$v3->p_id.'",c_id:"'.$v3->c_id.'",d_id:"'.$v3->d_id.'",sale_phone:"'.$v3->sale_phone.'"}';
              $t3 .= '}';
              /*	$t3 .= "$v3->id:'$v3->name',";
              $t3 .= "$v3->id:'$v3->name',";*/
            }
            //$city['0,'.$v1->city_id.','.$v2->city_id] = $t3;
            $city[$v2->city_id] = $t3;
            $t3 = '';
          }
        }
        //$pro['0,'.$v1->city_id] = $t2;
        //$t2 = '';
      }
    }

    $tmp .= "var dealersData = {\r\n";
    $childval = "";
    //$tmp .= "'0':{".trim($t1,',')."},\r\n";
//		if ($pro) {
//			foreach ($pro as $k=>$v){
//				$tmp .= "'$k':{".trim($v,',')."},\r\n";
//			}
//		}

    if ($city) {
      foreach ($city as $k => $v) {
//				if(empty($childval)){
//					$childval .= "{id:$k,datas:[".trim($v,',')."]}";
//				}else{
//					$childval .= "\r\n,{id:$k,datas:[".trim($v,',')."]}";
//				}
        //$tmp .="{id}"

        $childval .= "'$k':'[" . trim($v, ',') . "]',\r\n";
      }
      $childval .= "'dyk_last_two':'[]'\r\n";
    }

    //$childval = substr($childval,0,-1);
    $tmp .= $childval;
    $tmp .= "}";
    //dump($tmp);
    $file = FCPATH . '/public/dyk/js/allDealersData.js';
    file_put_contents($file, $tmp);
    return true;
  }

  /**
   * 获取数据的方法
   */
  private function _getDmp($type)
  {

    $config = array();
    $this->load->library('REST_Client', $config, 'REST_Client');

    switch ($type) {

      case 'dealers':

        $url = "http://dealer.dmp.cig.com.cn/api/get_all_dealers";
        break;
      case 'area':

        $url = "http://base.dmp.cig.com.cn/api/get_all_area";
        break;
      case 'cars':

        $url = "http://car.dmp.cig.com.cn/api/get_car_types";
        break;
    }

    $url = $url . "?key=" . $this->_dmpkey;

    $info = $this->REST_Client->get($url);
    if (empty($info)) {
      return false;
    }
    $this->load->driver('cache');
    $_cachename = md5($this->_cachename . $type);

    $this->cache->file->save($_cachename . '_all', $info, 100000);

    return self::_getc($type);
  }

  /**
   * 通过dmp 获取车款信息
   */
  private function _getcarmodelsDmp($cartypeid)
  {

    $config = array();
    $this->load->library('REST_Client', $config, 'REST_Client');

    $url = "http://car.dmp.cig.com.cn/api/get_sct_by_ct";
    $url = $url . "?key=" . $this->_dmpkey . "&car_type_id=" . $cartypeid;

    $info = $this->REST_Client->get($url);
    $data = json_decode($info);
    if ($data->data) {
      foreach ($data->data as $k => &$v) {
        $v->detail_info = json_decode($v->detail_info);
      }
    }
    return $data->data;
  }

  /**
   * 更新车型车款配置信息
   */
  public function updateCarConfigs()
  {

    $carinfo = $this->_getcarConfigs('carsConfigs');
//		dump($carinfo);exit;
    foreach ($carinfo as $k => $v) {
      // 单一车型
      foreach ($v['data_2'] as $key => $val) {
        // 某车款
        foreach ($val as $key4 => $val4) {

          // 车型配置标识信息
          foreach ($v['data'] as $key2 => $val2) {
            $configs = $v['data'];
            // 某车款
            foreach ($val2->field as $key3 => $val3) {
              // field lists
              if ($key4 == $key3) {

                //$info[$key][$key2]['name'] = $val2->name;
                //$info[$key][$key2]['list'][$key3]['name'] = $val3;
                //$info[$key][$key2]['list'][$key3]['val'] = $val4;
                $info[$key][$val2->name]['name'] = $val2->name;
                $info[$key][$val2->name]['list'][$val3]['name'] = $val3;
                $info[$key][$val2->name]['list'][$val3]['val'] = $val4;
              }
            }
          }
        }
      }
    }
    //dump($info);exit;
    $info['configs'] = $configs;
    $type = 'carsConfigs_lists';
    $this->load->driver('cache');
    $_cachename = md5($this->_cachename . $type);
    $this->cache->file->save($_cachename . '_all', $info, 100000);
    return true;

    // ???
    $tk0 = '0';
    foreach ($info as $k => $v) {
      $t1 = '';
      $tk1 = '0';
      foreach ($v as $key => $val) {
        $t2 = '';
        $tk2 = '0';
        foreach ($val['list'] as $k2 => $v2) {

          if ($tk2 == '0') {
            $t2 .= '{name:\'' . $v2['name'] . '\',val:\'' . $v2['val'] . '\'}';
          } else {
            $t2 .= ',{name:\'' . $v2['name'] . '\',val:\'' . $v2['val'] . '\'}';
          }
          $tk2++;
        }
        if ($tk1 == '0') {
          $t1 .= "{id:'" . $key . "',lists:[{name:'" . $val['name'] . "',list:[" . $t2 . "]}]}";
        } else {
          $t1 .= ",{id:'" . $key . "',lists:[{name:'" . $val['name'] . "',list:[" . $t2 . "]}]}";
        }
        $tk1++;
      }

      $tmp .= "'$k':{id:'" . $k . "',lists:[$t1]},\r\n";
      $tk0++;
    }
    //dump($tmp);exit;
    $tmp .= $childval;
    $tmp .= "\r\n}";
    $file = FCPATH . '/public/dyk/common/carConfigs.js';
    file_put_contents($file, $tmp);
    return true;

  }

  /**
   * 通过dmp 获取车款信息 未使用
   */
  public function _getcarconfigDmp()
  {

    $nodes = self::getCartype();
    if (empty($nodes)) {
      return false;
    }

    foreach ($nodes->car_types as $v1) {

      $cartypeid = $v1->id;
      $config = array();
      $this->load->library('REST_Client', $config, 'REST_Client');

      $url = "http://car.dmp.cig.com.cn/api/get_config";
      $url = $url . "?key=" . $this->_dmpkey . "&car_type_id=" . $cartypeid;
      $info = $this->REST_Client->get($url);
      $data = json_decode($info);
      if ($data->data) {
        $info_1 = $data->data;
      }

      $url = "http://car.dmp.cig.com.cn/api/get_config_info";
      $url = $url . "?key=" . $this->_dmpkey . "&car_type_id=" . $cartypeid;
      $info = $this->REST_Client->get($url);
      $data_2 = json_decode($info);

      if ($data_2->data) {
        $info_2 = $data_2->data;
      }

      $msg['data'] = $info_1;
      $msg['data_2'] = $info_2;
      $carinfo[$v1->id] = $msg;
    }
    $type = 'carsConfigs';
    $this->load->driver('cache');
    $_cachename = md5($this->_cachename . $type);
    $this->cache->file->save($_cachename . '_all', $carinfo, 100000);
    return true;
  }

  /**
   *
   * 获取
   */
  private function _getcarConfigs($type)
  {

    $this->load->driver('cache');
    $_cachename = md5($this->_cachename . $type);

    $info = $this->cache->file->get($_cachename . '_all');

    return $info;
  }

  /**
   *
   * 获取
   */
  private function _getc($type)
  {

    $this->load->driver('cache');
    $_cachename = md5($this->_cachename . $type);

    $info = $this->cache->file->get($_cachename . '_all');
    $data = json_decode($info);

    return $data->data;
  }

  /**
   *
   * 获取
   */
  private function _getParentList($areaid)
  {

    $data = self::getArea();
    foreach ($data as $k => $v) {
      foreach ($v->cities as $k1 => $v1) {
        if ($v1->city_id == $areaid) {
          return $v1->parent_id;
          break;
        }
      }
    }
    return $areaid;
  }

  /**
   *
   * 得到地区select数据
   */
  public function getSelect($areaid = '0', $other = '')
  {

    // 如果存在的获取他的父级id
    $provinceid = self::_getParentList($areaid);

    $province = 'province';
    $html .= "<select name='$province' id='$province' $other></select>\r\n";
    $js .= "areaSel.bind('#$province',$provinceid);\r\n";

    $city = 'city';
    $html .= "<select name='$city' id='$city' $other></select>\r\n";
    $js .= "areaSel.bind('#$city',$areaid);\r\n";

    $tmp = "<script type=\"text/javascript\" src=\"/public/dyk/js/select.js\"></script>\r\n" . $html . "
				<script type=\"text/javascript\" src=\"/public/dyk/js/areaData.js\"></script>
				<script type=\"text/javascript\">
				var areaOptions = {
					data : data
				}	
				var areaSel = new select(areaOptions);
				$js
				</script>
				";
    return $tmp;
  }

  /**
   *
   * 获取
   */
  private function _getDealerParent($dealerid)
  {

    $data = self::getDealers();

    foreach ($data as $k => $v) {
      if ($v->id == $dealerid) {
        $array['provinceid'] = $v->p_id;
        $array['cityid'] = $v->c_id;
        return $array;
        break;
      }
    }
    return $array;
  }

  /**
   *
   * 得到经销商select数据
   */
  public function getDealerSelect($dealerid, $other = '', $ids = array())
  {

    // 如果存在的获取他的父级id
    $area_array = self::_getDealerParent($dealerid);
    $provinceid = $area_array['provinceid'] ? $area_array['provinceid'] : '0';
    $cityid = $area_array['cityid'] ? $area_array['cityid'] : '0';
    if (empty($ids)) {
      $ids['province'] = 'buyProvince';
      $ids['city'] = 'buyCity';
      $ids['dealers'] = 'dealers';
    }
    $province = $ids['province'];
    $html .= "<select name='$province' id='$province' $other></select>\r\n";
    $js .= "areaSel.bind('#$province',$provinceid);\r\n";

    $city = $ids['city'];
    $html .= "<select name='$city' id='$city' $other></select>\r\n";
    $js .= "areaSel.bind('#$city',$cityid);\r\n";

    $dealers = $ids['dealers'];
    $html .= "</dd><dd><select name='$dealers' id='$dealers' $other></select>\r\n";
    $js .= "areaSel.bind('#$dealers',$dealerid);\r\n";

    $tmp = "<script type=\"text/javascript\" src=\"/public/dyk/js/select.js\"></script>\r\n" . $html . "
				<script type=\"text/javascript\" src=\"/public/dyk/js/dealersData.js\"></script>
				<script type=\"text/javascript\">
				var dealerOptions = {
					data : data
				}	
				var areaSel = new select(dealerOptions);
				$js
				</script>
				";
    return $tmp;
  }

  /**
   *
   * 得到车型车款select数据
   */
  public function getCarsSelect($carid = '0', $carModelid = '0', $colors = '', $other = '', $ids = array())
  {

    if (empty($ids)) {
      $ids['carModel'] = 'carModel';
      $ids['carType'] = 'carType';
      $ids['carcolor'] = 'carcolor';
    }
    $carid = empty($carid) ? '0' : $carid;
    $carModelid = empty($carModelid) ? '0' : $carModelid;
    $carModel = $ids['carModel'];
    $html .= "<select name='$carModel' id='$carModel' $other></select>\r\n";
    $js .= "carsSel.bind('#$carModel',$carModelid);\r\n";

    $carType = $ids['carType'];
    $html .= "<select name='$carType' id='$carType' $other></select>\r\n";
    $js .= "carsSel.bind('#$carType',$carid);\r\n";

    $carcolor = $ids['carcolor'];
    $html .= "</dd><dt>车身颜色</dt><dd><select name='$carcolor' id='$carcolor' $other></select>\r\n";
    $js .= "carsSel.bind('#$carcolor','$colors');\r\n";
    //$html = '';
    $tmp = "<script type=\"text/javascript\" src=\"/public/dyk/js/select.js\"></script>\r\n" . $html . "
				<script type=\"text/javascript\" src=\"/public/dyk/js/cartypesData.js\"></script>
				<script type=\"text/javascript\">
				var cartypesOptions = {
					data : data
				}	
				var carsSel = new select(cartypesOptions);
				$js
				</script>
				";
    return $tmp;
  }
}