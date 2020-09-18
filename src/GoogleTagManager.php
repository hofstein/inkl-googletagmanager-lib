<?php

namespace Inkl\GoogleTagManagerLib;

use Inkl\GoogleTagManagerLib\Schema\ContainerId;
use Mustache_Engine;
use Mustache_Loader_FilesystemLoader;

class GoogleTagManager
{
	private static $instance;
	private $mustache;

	private $dataLayer = [];
	private $scripts = [];

	public function __construct()
	{
		$this->mustache = new Mustache_Engine([
			'loader' => new Mustache_Loader_FilesystemLoader(__DIR__ . '/../templates/')
		]);
	}

	public static function getInstance()
	{
		if (!self::$instance)
		{
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Render tag
	 *
	 * @param ContainerId $containerId
	 * @return string
	 */
	public function renderTag(ContainerId $containerId)
	{
		$dataLayer = [];
		foreach ($this->getDataLayerVariables() as $dataLayerVariable)
		{
			$dataLayer[] = $dataLayerVariable;
		}

		return $this->mustache->render('tag', [
			'id' => $containerId,
			'customScripts' => implode(PHP_EOL, $this->getCustomScripts()),
			'dataLayerJson' => json_encode($dataLayer, JSON_PRETTY_PRINT)
		]);
	}

	/**
	 * Add js script which is placed after datalayer and before tag
	 * $index is only used as internal identifier
	 *
	 * @param string $script
	 * @param string $index
	 * @return $this
	 */
	public function addCustomScript($script, $index = null)
	{
		if ($index)
		{
			$this->scripts[$index] = $script;
		} else
		{
			$this->scripts[] = $script;
		}

		return $this;
	}

	/**
	 * Remove script by index
	 *
	 * @param string $index
	 * @return $this
	 */
	public function removeCustomScript($index)
	{
		unset($this->scripts[$index]);

		return $this;
	}

	/**
	 * Retrieve all scripts
	 *
	 * @return array
	 */
	public function getCustomScripts()
	{
		return $this->scripts;
	}

	/**
	 * Remove all scripts
	 */
	public function clearCustomScripts()
	{
		$this->scripts = [];

		return $this;
	}

	/**
	 * Add datalayer variable
	 * $index is only used as internal identifier
	 *
	 * @param string $name
	 * @param mixed $value
	 * @param string $index
	 * @return $this
	 */
	public function addDataLayerVariable($name, $value, $index = null)
	{
		$data = $value;
		if ($name)
		{
			$data = [$name => $value];
		}
		
		if ($index)
		{
			$this->dataLayer[$index] = $data;
		} else {
			$this->dataLayer[] = $data;
		}

		return $this;
	}

	/**
	 * Remove datalayer variable by index
	 *
	 * @param string $index
	 * @return $this
	 */
	public function removeDataLayerVariable($index)
	{
		unset($this->dataLayer[$index]);

		return $this;
	}

	/**
	 * Retrieve all datalayer variables
	 *
	 * @return array
	 */
	public function getDataLayerVariables()
	{
		return $this->dataLayer;
	}

	/**
	 * Remove all datalayer variables
	 *
	 * @return $this
	 */
	public function clearDataLayerVariables()
	{
		$this->dataLayer = [];

		return $this;
	}

}
