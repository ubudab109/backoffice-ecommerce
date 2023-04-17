<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use Validator;
use Request;

class ValidatorController extends Controller
{
    /*
	|--------------------------------------------------------------------------
	| Home Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders your application's "dashboard" for users that
	| are authenticated. Of course, you are free to change or remove the
	| controller as you wish. It is just here to get your app started!
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */

	private $messages = array(
		"required" => "Mohon isi kolom ini.",
		"size" => "Isi sepanjang :size huruf", 
		"min" => "Isi dengan minimum :min",
		"max" => "Isi dengan maximum :max",
		"numeric" => "Mohon isi hanya dengan nomor",
		"alpha" => "Mohon isi hanya dengan A-Z",
		"between" => 'Isi dengan antara :min - :max.',
		"digits" => 'Nomor harus sama dengan :digits.',
		"digits_between" => 'Isi dengan antara :min - :max nomor',
		"email" => 'Email yang diberi tidak valid',
		"image" => 'Mohon upload hanya gambar',
		"digits_between" => 'Isi dengan antara :min - :max nomor.',
		"name_check"=>"Nama hanya boleh mengandung A-Z dan '",
		"date_check"=>"Format Tanggal Salah",
		"rtrw_check"=>"Isi hanya dengan 0-9 dan /",
		"pw_check"=>"Input antara 8-14 karakter dan harus merupakan kombinasi antara angka dan huruf",
		"same" => ":attribute dan :other harus cocok",
		"captcha" => "Captcha tidak sesuai",
	);

	public function validateTry($data, $rules) {
		$validator = Validator::make($data,$rules,$this->messages);
		if ($validator->fails())
		{
			if (Request::ajax()) {
				return $validator->messages();
			}
			else {
				return $validator->messages();
			}
			// The given data did not pass validation
		}
		return 'pass';
	}
}