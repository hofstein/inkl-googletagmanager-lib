<?php

namespace Inkl\GoogleTagManagerLib;

use Inkl\GoogleTagManagerLib\Schema\ContainerId;

class GoogleTagManagerTest extends \PHPUnit_Framework_TestCase
{
	/** @var GoogleTagManager */
	private $googleTagManager;

	protected function setUp()
	{
		parent::setUp();

		$this->googleTagManager = GoogleTagManager::getInstance();
	}

	protected function tearDown()
	{
		parent::tearDown();

		$this->googleTagManager->clearDataLayerVariables();
	}

	public function testRenderTag()
	{
		$containerId = 123;

		$expectedResult = <<<EOF
<script>
var dataLayer = [];
</script>

<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{$containerId}');</script>
EOF;

		$result = $this->googleTagManager
			->renderTag(new ContainerId($containerId));

		$this->assertSame($expectedResult, $result);
	}

	public function testRenderTagWithAllFeatures()
	{
		$containerId = 123;
		$customScript = "<script>console.log('custom_script')</script>";
		$dataLayerVariableName = 'testName';
		$dataLayerVariableValue = 'testValue';
		$dataLayerVariableEventName = 'testEvent';
		$dataLayerVariableEventValue = 'testEventValue';

		$expectedResult = <<<EOF
<script>
var dataLayer = [
    {
        "{$dataLayerVariableName}": "{$dataLayerVariableValue}"
    },
    {
        "{$dataLayerVariableEventName}": "{$dataLayerVariableEventValue}"
    }
];
</script>
{$customScript}
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','{$containerId}');</script>
EOF;

		$result = $this->googleTagManager
			->addDataLayerVariable($dataLayerVariableName, $dataLayerVariableValue)
			->addDataLayerVariable($dataLayerVariableEventName, $dataLayerVariableEventValue)
			->addCustomScript($customScript)
			->renderTag(new ContainerId($containerId));

		$this->assertSame($expectedResult, $result);
	}

}
