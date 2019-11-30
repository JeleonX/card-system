<?php
namespace App\Library\Geetest; class Lib { const GT_SDK_VERSION = 'php_3.2.0'; public static $connectTimeout = 1; public static $socketTimeout = 1; private $response; public $captcha_id; public $private_key; public function __construct($sp38ee9d, $spa59199) { $this->captcha_id = $sp38ee9d; $this->private_key = $spa59199; } public function pre_process($sp1e2a07 = null) { $spdcad38 = 'http://api.geetest.com/register.php?gt=' . $this->captcha_id; if ($sp1e2a07 != null and is_string($sp1e2a07)) { $spdcad38 = $spdcad38 . '&user_id=' . $sp1e2a07; } $spbb96d2 = $this->send_request($spdcad38); if (strlen($spbb96d2) != 32) { $this->failback_process(); return 0; } $this->success_process($spbb96d2); return 1; } private function success_process($spbb96d2) { $spbb96d2 = md5($spbb96d2 . $this->private_key); $sp08ce12 = array('success' => 1, 'gt' => $this->captcha_id, 'challenge' => $spbb96d2); $this->response = $sp08ce12; } private function failback_process() { $sp3c5849 = md5(rand(0, 100)); $spc23413 = md5(rand(0, 100)); $spbb96d2 = $sp3c5849 . substr($spc23413, 0, 2); $sp08ce12 = array('success' => 0, 'gt' => $this->captcha_id, 'challenge' => $spbb96d2); $this->response = $sp08ce12; } public function get_response_str() { return json_encode($this->response); } public function get_response() { return $this->response; } public function success_validate($spbb96d2, $spe73c8a, $spbce951, $sp1e2a07 = null) { if (!$this->check_validate($spbb96d2, $spe73c8a)) { return 0; } $sp6db6e6 = array('seccode' => $spbce951, 'sdk' => self::GT_SDK_VERSION); if ($sp1e2a07 != null and is_string($sp1e2a07)) { $sp6db6e6['user_id'] = $sp1e2a07; } $spdcad38 = 'http://api.geetest.com/validate.php'; $sp403db4 = $this->post_request($spdcad38, $sp6db6e6); if ($sp403db4 == md5($spbce951)) { return 1; } else { if ($sp403db4 == 'false') { return 0; } else { return 0; } } } public function fail_validate($spbb96d2, $spe73c8a, $spbce951) { if ($spe73c8a) { $sp4a6d49 = explode('_', $spe73c8a); try { $sp9df27c = $this->decode_response($spbb96d2, $sp4a6d49['0']); $sp1864a2 = $this->decode_response($spbb96d2, $sp4a6d49['1']); $spc4dc42 = $this->decode_response($spbb96d2, $sp4a6d49['2']); $sp9fbecb = $this->get_failback_pic_ans($sp1864a2, $spc4dc42); $spd75567 = abs($sp9df27c - $sp9fbecb); } catch (\Exception $sp4b79b8) { return 1; } if ($spd75567 < 4) { return 1; } else { return 0; } } else { return 0; } } private function check_validate($spbb96d2, $spe73c8a) { if (strlen($spe73c8a) != 32) { return false; } if (md5($this->private_key . 'geetest' . $spbb96d2) != $spe73c8a) { return false; } return true; } private function send_request($spdcad38) { if (function_exists('curl_exec')) { $spd5d7db = curl_init(); curl_setopt($spd5d7db, CURLOPT_URL, $spdcad38); curl_setopt($spd5d7db, CURLOPT_CONNECTTIMEOUT, self::$connectTimeout); curl_setopt($spd5d7db, CURLOPT_TIMEOUT, self::$socketTimeout); curl_setopt($spd5d7db, CURLOPT_RETURNTRANSFER, 1); $sp6db6e6 = curl_exec($spd5d7db); if (curl_errno($spd5d7db)) { $spf325c7 = sprintf('curl[%s] error[%s]', $spdcad38, curl_errno($spd5d7db) . ':' . curl_error($spd5d7db)); $this->triggerError($spf325c7); } curl_close($spd5d7db); } else { $sp19022e = array('http' => array('method' => 'GET', 'timeout' => self::$connectTimeout + self::$socketTimeout)); $spdccab0 = stream_context_create($sp19022e); $sp6db6e6 = file_get_contents($spdcad38, false, $spdccab0); } return $sp6db6e6; } private function post_request($spdcad38, $sp3551b7 = '') { if (!$sp3551b7) { return false; } $sp6db6e6 = http_build_query($sp3551b7); if (function_exists('curl_exec')) { $spd5d7db = curl_init(); curl_setopt($spd5d7db, CURLOPT_URL, $spdcad38); curl_setopt($spd5d7db, CURLOPT_RETURNTRANSFER, 1); curl_setopt($spd5d7db, CURLOPT_CONNECTTIMEOUT, self::$connectTimeout); curl_setopt($spd5d7db, CURLOPT_TIMEOUT, self::$socketTimeout); if (!$sp3551b7) { curl_setopt($spd5d7db, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); } else { curl_setopt($spd5d7db, CURLOPT_POST, 1); curl_setopt($spd5d7db, CURLOPT_POSTFIELDS, $sp6db6e6); } $sp6db6e6 = curl_exec($spd5d7db); if (curl_errno($spd5d7db)) { $spf325c7 = sprintf('curl[%s] error[%s]', $spdcad38, curl_errno($spd5d7db) . ':' . curl_error($spd5d7db)); $this->triggerError($spf325c7); } curl_close($spd5d7db); } else { if ($sp3551b7) { $sp19022e = array('http' => array('method' => 'POST', 'header' => 'Content-type: application/x-www-form-urlencoded
' . 'Content-Length: ' . strlen($sp6db6e6) . '
', 'content' => $sp6db6e6, 'timeout' => self::$connectTimeout + self::$socketTimeout)); $spdccab0 = stream_context_create($sp19022e); $sp6db6e6 = file_get_contents($spdcad38, false, $spdccab0); } } return $sp6db6e6; } private function decode_response($spbb96d2, $sp669925) { if (strlen($sp669925) > 100) { return 0; } $sp39bba2 = array(); $sp476438 = array(); $sp66271b = array('0' => 1, '1' => 2, '2' => 5, '3' => 10, '4' => 50); $sp2150fd = 0; $spe90c16 = 0; $sp2dc156 = str_split($spbb96d2); $sp4d7189 = str_split($sp669925); for ($spdb14d1 = 0; $spdb14d1 < strlen($spbb96d2); $spdb14d1++) { $sp64913d = $sp2dc156[$spdb14d1]; if (in_array($sp64913d, $sp476438)) { continue; } else { $sp4a6d49 = $sp66271b[$sp2150fd % 5]; array_push($sp476438, $sp64913d); $sp2150fd++; $sp39bba2[$sp64913d] = $sp4a6d49; } } for ($sp46b1b9 = 0; $sp46b1b9 < strlen($sp669925); $sp46b1b9++) { $spe90c16 += $sp39bba2[$sp4d7189[$sp46b1b9]]; } $spe90c16 = $spe90c16 - $this->decodeRandBase($spbb96d2); return $spe90c16; } private function get_x_pos_from_str($spa47023) { if (strlen($spa47023) != 5) { return 0; } $spfaabcc = 0; $spb63b49 = 200; $spfaabcc = base_convert($spa47023, 16, 10); $sp08ce12 = $spfaabcc % $spb63b49; $sp08ce12 = $sp08ce12 < 40 ? 40 : $sp08ce12; return $sp08ce12; } private function get_failback_pic_ans($sp87a69e, $spf6e519) { $spe3abdc = substr(md5($sp87a69e), 0, 9); $sp0f0fb2 = substr(md5($spf6e519), 10, 9); $sp08f62a = ''; for ($spdb14d1 = 0; $spdb14d1 < 9; $spdb14d1++) { if ($spdb14d1 % 2 == 0) { $sp08f62a = $sp08f62a . $spe3abdc[$spdb14d1]; } elseif ($spdb14d1 % 2 == 1) { $sp08f62a = $sp08f62a . $sp0f0fb2[$spdb14d1]; } } $spc0d7cb = substr($sp08f62a, 4, 5); $sp9fbecb = $this->get_x_pos_from_str($spc0d7cb); return $sp9fbecb; } private function decodeRandBase($spbb96d2) { $sp4a3390 = substr($spbb96d2, 32, 2); $sp9758d9 = array(); for ($spdb14d1 = 0; $spdb14d1 < strlen($sp4a3390); $spdb14d1++) { $sp6f1b23 = ord($sp4a3390[$spdb14d1]); $sp08ce12 = $sp6f1b23 > 57 ? $sp6f1b23 - 87 : $sp6f1b23 - 48; array_push($sp9758d9, $sp08ce12); } $sp05b403 = $sp9758d9['0'] * 36 + $sp9758d9['1']; return $sp05b403; } private function triggerError($spf325c7) { } }