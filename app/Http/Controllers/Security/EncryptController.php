<?php

namespace App\Http\Controllers\Security;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Security\SecurityController;
use Session;

class EncryptController extends Controller
{
	function getPass()
	{
		try {
			$Security = new SecurityController;
			$password = array();
			array_push($password,  $Security->generateRandomString(32));
			array_push($password,  $Security->generateRandomString(32));
			array_push($password,  $Security->generateRandomString(32));
			Session::put('s', $password);
			$ret = array('password' => $password, 'error' => false);
			return $ret;
		} catch (\Exception $e) {
			$error = 'Caught exception: ' . $e->getMessage() . "\n";
			$ret = array('password' => env('ENC_PASS'), 'error' => $error);
			return $ret;
		}
	}

	function fnEncrypt($sValue, $sSecretKey)
	{
		return rtrim(
			base64_encode(
				mcrypt_encrypt(
					MCRYPT_RIJNDAEL_256,
					$sSecretKey,
					$sValue,
					MCRYPT_MODE_ECB,
					mcrypt_create_iv(
						mcrypt_get_iv_size(
							MCRYPT_RIJNDAEL_256,
							MCRYPT_MODE_ECB
						),
						MCRYPT_RAND
					)
				)
			),
			"\0"
		);
	}

	public static function fnDecrypt($enc_data, $toArray = null)
	{
		usleep(5 * 1000);
		$pass = Session::get('s');
		$encryption = new EncryptController();
		$data = explode($pass[0], $enc_data);
		$encrypted = base64_decode($data[0]); // data_base64 from JS
		if (isset($pass[1])) {
			$data = explode($pass[1], $data[1]);
		}
		$iv        = base64_decode($data[0]);   // iv_base64 from JS
		$data = explode($pass[2], $data[1]);
		$key       = base64_decode($data[0]);  // key_base64 from JS

		/* Change mcrypt to openssl */
		$plaintext = rtrim(openssl_decrypt($encrypted, "AES-256-CBC", $key, true, $iv), "\t\0");

		if ($toArray) {
			return $encryption->toArray($plaintext);
		}
		return $plaintext;
	}

	public static function toArray($data)
	{
		$array_data = explode('&', $data);
		$length = sizeof($array_data);
		$array_result = array();
		for ($i = 0; $i < $length; $i++) {
			$data_index = $array_data[$i];
			$result_index = explode('=', $data_index);
			$result_key = str_replace('%5B%5D', '', $result_index[0]);
			$result_value =  urldecode($result_index[1]);
			if (!isset($array_result[$result_key])) {
				$array_result[$result_key] = $result_value;
			} else {
				if (is_array($array_result[$result_key])) {
					array_push($array_result[$result_key], $result_value);
				} else {
					$existing_value = $array_result[$result_key];
					$array_result[$result_key] = array();
					array_push($array_result[$result_key], $existing_value, $result_value);
				}
			}
		}
		return $array_result;
	}
}
