<?php

namespace Inkl\GoogleTagManagerLib\Common;

class StringValue
{
	/** @var string */
	private $value;

	/**
	 * @param string $value
	 */
	public function __construct($value)
	{
		$this->value = $value;
	}

	public function getValue()
	{
		return $this->value;
	}

	public function __toString()
	{
		return (string)$this->value;
	}
}
