<?php

namespace Contributte\GopayInline\Api\Entity;

class PreauthorizedPayment extends Payment
{

	/** @var boolean */
	protected $preauthorization = FALSE;

	/**
	 * @return boolean
	 */
	public function isPreauthorization()
	{
		return $this->preauthorization;
	}

	/**
	 * @param boolean $preauth
	 * @return void
	 */
	public function setPreauthorization($preauth)
	{
		$this->preauthorization = boolval($preauth);
	}

	/**
	 * ABSTRACT ****************************************************************
	 */

	/**
	 * @return array
	 */
	public function toArray()
	{
		$payment = parent::toArray();

		$preauth = $this->isPreauthorization();
		if ($preauth) {
			$payment['preauthorization'] = $preauth;
		}

		return $payment;
	}

}
