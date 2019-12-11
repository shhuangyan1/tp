<?php
/**
 * @Author shhuangyan1@gmail.com
 * @Date 2019/12/9 16:21
 * @Version 1.0
 */


/**
 * 验证签名
 * @param array $data
 * @return string
 */
function checkSign($data)
{
    return md5(serialize($data) . 'huangyan');
}

/**
 * 统一返回信息
 * @param $code
 * @param $data
 * @param $msge
 */
function msg($code, $data, $msg)
{
    return compact('code', 'data', 'msg');
}

/**
 * 对象转换成数组
 * @param $obj
 */
function objToArray($obj)
{
    return json_decode(json_encode($obj), true);
}

/**
 * 权限检测
 * @param $rule
 */
function authCheck($rule)
{
    $control = explode('/', $rule)['0'];

    // print_r(session('role_id'));
    // exit;

    if (in_array($control, ['login', 'index', 'v1.Index'])) {
        return true;
    }

    if (session('role_id') == '1') {
        return true;
    }

    if (in_array($rule, cache(session('role_id')))) {
        return true;
    }

    return false;
}

function check_phone($phone)
{
    $check = '/^(1(([35789][0-9])|(47)))\d{8}$/';
    if (preg_match($check, $phone)) {
        return true;
    } else {
        return false;
    }
}

//地址转为经纬度
function addressTransform($address, $key)
{

    if ($address == '' || $key == '') {
        return false;
    }

    $mapApi = 'https://apis.map.qq.com/ws/geocoder/v1/?';
    $url = $mapApi . 'address=' . $address . '&key=' . $key;
    $res = json_decode(file_get_contents($url), true);

    if ($res['status'] == '0') {
        return $res['result'];
    } else {
        return false;
    }
}

//获取当前月份所在的季度起始月份
function getSeason($month)
{
    if (in_array($month, [1, 2, 3])) {
        $month = 1;
    } elseif (in_array($month, [4, 5, 6])) {
        $month = 4;
    } elseif (in_array($month, [7, 8, 9])) {
        $month = 7;
    } else {
        $month = 10;
    }
    return $month;
}

function downfile($fileurl)
{
    ob_start();
    $filename = $fileurl;
    $date = date("Ymd-H:i:s");
    header("Content-type:  application/octet-stream ");
    header("Accept-Ranges:  bytes ");
    header("Content-Disposition:  attachment;  filename= {$date}.png");
    $size = readfile($filename);
    header("Accept-Length: " . $size);
}


function http_request($url, $data = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    // 以文件流形式返回
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    if (!empty($data)) {
        // POST请求
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    $output = curl_exec($ch);
    curl_close($ch);

    // 返回数组
    return json_decode($output, true);
}


function postCurl($url, $data, $type)
{
    if ($type == 'json') {
        $data = json_encode($data);//对数组进行json编码
        $header = array("Content-type: application/json;charset=UTF-8", "Accept: application/json",
            "Cache-Control: no-cache", "Pragma: no-cache");
    } else {
        $header = array("charset=UTF-8", "Accept: application/json",
            "Cache-Control: no-cache", "Pragma: no-cache");
    }
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
    if (!empty($data)) {
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
    $res = curl_exec($curl);
    if (curl_errno($curl)) {
        echo 'Error+' . curl_error($curl);
    }
    curl_close($curl);
    return $res;
}

function request_by_curl($remote_server, $post_string)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $remote_server);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=utf-8'));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // 线下环境不用开启curl证书验证, 未调通情况可尝试添加该代码
    // curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
    // curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

function downtxt($url, $path)
{
    $arr = parse_url($url);
    $fileName = basename($arr['path']);
    $file = file_get_contents($url);
    file_put_contents($path . $fileName, $file);
}

function exportExcel($expTitle, $expCellName, $expTableData)
{
    $xlsTitle = iconv('utf-8', 'gb2312', $expTitle);//文件名称
    $fileName = date('_YmdHis');//or $xlsTitle 文件名称可根据自己情况设定
    $cellNum = count($expCellName);
    $dataNum = count($expTableData);
    vendor("PHPExcel.PHPExcel");

    $objPHPExcel = new PHPExcel();
    $cellName = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z', 'AA', 'AB', 'AC', 'AD', 'AE', 'AF', 'AG', 'AH', 'AI', 'AJ', 'AK', 'AL', 'AM', 'AN', 'AO', 'AP', 'AQ', 'AR', 'AS', 'AT', 'AU', 'AV', 'AW', 'AX', 'AY', 'AZ');

    $objPHPExcel->getActiveSheet(0)->mergeCells('A1:' . $cellName[$cellNum - 1] . '1');//合并单元格
    // $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', $expTitle.' Export time:'.date('Y-m-d H:i:s'));
    for ($i = 0; $i < $cellNum; $i++) {
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue($cellName[$i] . '2', $expCellName[$i][1]);
    }
    // Miscellaneous glyphs, UTF-8
    for ($i = 0; $i < $dataNum; $i++) {
        for ($j = 0; $j < $cellNum; $j++) {
            $objPHPExcel->getActiveSheet(0)->setCellValue($cellName[$j] . ($i + 3), $expTableData[$i][$expCellName[$j][0]]);
        }
    }

    header('pragma:public');
    header('Content-type:application/vnd.ms-excel;charset=utf-8;name="' . $xlsTitle . '.xls"');
    header("Content-Disposition:attachment;filename=$fileName.xls");//attachment新窗口打印inline本窗口打印
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter->save('php://output');
    exit;
}

function sendTcp($name, $card, $id, $money, $charge_sn)
{


    $data['number'] = '955990000310';
    $data['name'] = $name;
    $data['card'] = $id;
    $data['id'] = $card;
    $data['money'] = $money;
    $data['charge_sn'] = $charge_sn;
    $tmp = json_encode($data);

    $res = http_post('http://260jp02698.zicp.vip:56799/bank.php', $tmp);
    return $res;
}

function http_post($url, $data = null)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    // 以文件流形式返回
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    if (!empty($data)) {
        // POST请求
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    $output = curl_exec($ch);
    curl_close($ch);

    // 返回数组
    return $output;
}

/**
 * 简单加密函数
 * @param $str
 * @return bool|string
 */
function php_encrypt($str, $key)
{
    $enstr = '';
    $str = $str . $key;
    $encrypt_key = '.abcdefghijklmnopqrstuvwxyz1234567890,ABCDEFGHIJKLMNOPQRSTUVWXYZ-_~!@#$%^&*()|?{}[]';
    $decrypt_key = '.ngzqtcobmuhelkpdawxfyivrsj2469873105,NGZQTCOBMUHELKPDAWXFYIVRSJ-_~|?{}[]!@#$%^&*()';
    if (strlen($str) == 0) return false;
    for ($i = 0; $i < strlen($str); $i++) {
        for ($j = 0; $j < strlen($encrypt_key); $j++) {
            if ($str[$i] == $encrypt_key [$j]) {
                $enstr .= $decrypt_key[$j];
                break;
            }
        }
    }
    return $enstr;
}

/**
 * 简单解密函数
 * @param $str
 * @return bool|string
 */
function php_decrypt($str, $key)
{
    $enstr = '';
    $encrypt_key = '.abcdefghijklmnopqrstuvwxyz1234567890,ABCDEFGHIJKLMNOPQRSTUVWXYZ-_~!@#$%^&*()|?{}[]';
    $decrypt_key = '.ngzqtcobmuhelkpdawxfyivrsj2469873105,NGZQTCOBMUHELKPDAWXFYIVRSJ-_~|?{}[]!@#$%^&*()';
    if (strlen($str) == 0) return false;
    for ($i = 0; $i < strlen($str); $i++) {
        for ($j = 0; $j < strlen($decrypt_key); $j++) {
            if ($str[$i] == $decrypt_key [$j]) {
                $enstr .= $encrypt_key[$j];
                break;
            }
        }
    }
    $tmp = strlen($key);
    $enstr = substr($enstr, 0, -$tmp);
    return $enstr;
}


/**
 * md5加密
 */
function md5_encrypt($str)
{
    $token = 'quciknew*888';
    $tmp = md5($str . $token);
    return $tmp;
}

/**
 * 数据签名
 * @param $data
 * @return string
 */
function sign($data)
{
    $tmp = '';

    if (isset($data['sign'])) {
        unset($data['sign']);
    }

    foreach ($data as $k => $v) {
        $tmp .= $v;
    }

    return md5_encrypt($tmp);
}

/**
 * 获取节点树 参考layUI
 * @param $pInfo
 * @param bool $spread
 * @return array
 */
function getTree($pInfo, $spread = true)
{

    $res = [];
    $tree = [];
    //整理数组
    foreach($pInfo as $key=>$vo){

        if($spread){
            $vo['spread'] = true;  //默认展开
        }
        $res[$vo['id']] = $vo;
        $res[$vo['id']]['children'] = [];
    }
    unset($pInfo);

    //查找子孙
    foreach($res as $key=>$vo){
        if(0 != $vo['parent_id']){
            $res[$vo['parent_id']]['children'][] = &$res[$key];
        }
    }

    //过滤杂质
    foreach( $res as $key=>$vo ){
        if(0 == $vo['parent_id']){
            $tree[] = $vo;
        }
    }
    unset( $res );

    return $tree;
}

