<?php

namespace App\Validations;

class FormatRules extends \CodeIgniter\Validation\FormatRules
{
	public function alpha_numeric_punct($str)
	{
		return (bool) preg_match('/^[A-Z0-9 ~!#$%\&\*\-_+=|:.,]+$/i', $str);
	}

	/**
	 * validate_email_json_array
	 * Can be used as validate_email_json_array
	 *
	 * @param  string $json_string
	 * @return bool
	 */
	public function validate_email_json_array($json_string, &$error): bool
	{
		//Ignore if empty
		if (empty($json_string)) {
			return true;
		}

		$is_valid_json = $this->valid_json($json_string);

		//Fail if it is not a valid JSON String
		if (!$is_valid_json) {
			return false;
		}

		$email_list = json_decode($json_string, true);

		//Fail if any of the emails are invalid
		foreach ($email_list as $email) {
			if (!$this->valid_email($email)) {
				$error = 'This field contains invalid email(s).';
				return false;
			}
		}

		return true;
	}

	/**
	 * validate_phone_json_array
	 * Can be used as validate_phone_json_array
	 *
	 * @param  string $json_string
	 * @return bool
	 */
	public function validate_phone_json_array($json_string, &$error): bool
	{
		//Ignore if empty
		if (empty($json_string)) {
			return true;
		}

		$is_valid_json = $this->valid_json($json_string);

		//Fail if it is not a valid JSON String
		if (!$is_valid_json) {
			return false;
		}

		$phone_number_list = json_decode($json_string, true);

		//Fail if any of the emails are invalid
		foreach ($phone_number_list as $phone) {

			$is_natural_number = $this->is_natural_no_zero($phone);

			$has_10_digits = strlen((string)$phone) === 10;

			$is_valid_number = $is_natural_number && $has_10_digits;

			if (!$is_valid_number) {
				$error = 'This field contains invalid number(s).';
				return false;
			}
		}

		return true;
	}

	public function validate_integer_json_array($json_string, &$error): bool
	{
		//Ignore if empty
		if (empty($json_string)) {
			return true;
		}

		$is_valid_json = $this->valid_json($json_string);

		//Fail if it is not a valid JSON String
		if (!$is_valid_json) {
			return false;
		}

		$integer_list = json_decode($json_string, true);

		//Fail if any integer is invalid
		foreach ($integer_list as $a_number) {

			$is_natural_number = $this->is_natural_no_zero($a_number);

			if (!$is_natural_number) {
				$error = 'This field contains invalid number(s).';
				return false;
			}
		}

		return true;
	}
}
