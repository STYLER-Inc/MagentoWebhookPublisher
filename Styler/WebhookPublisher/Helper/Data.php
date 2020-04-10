<?php
namespace Styler\WebhookPublisher\Helper;
use \Magento\Framework\App\Helper\AbstractHelper;

class Data extends AbstractHelper
{
	// Environment variable where endpoint is defined
	const ENVIRONMENT_VARIABLE = 'STYLER_EVENT_WEBHOOK_URL';

	// Filename where includes/excludes are defined. File should be under /etc folder in the module
	const INCLUDE_EXCLUDE_FILE = 'includes_excludes.xml';

	protected $_logger;
	/**
	 * @var \Magento\Framework\Module\Dir\Reader
	 */
	protected $_fileReader;
	/**
	 * @var \Magento\Framework\Xml\Parser
	 */
	protected $_parser;

	public function __construct(
		\Psr\Log\LoggerInterface $logger,
		\Magento\Framework\Module\Dir\Reader $_fileReader,
		\Magento\Framework\Xml\Parser $parser
	)
	{
		$this->_logger = $logger;
		$this->_fileReader = $_fileReader;
		$this->_parser = $parser;
	}

	private function isInclude($xml)
	{
		return (strcasecmp($xml['config']['events']['_attribute']['action'],'include') == 0);
	}

	private function isExclude($xml)
	{
		return (strcasecmp($xml['config']['events']['_attribute']['action'],'exclude') == 0);
	}

	private function getEventList($xml)
	{
		$list = [];
		$events = $xml['config']['events']['_value']['event'];
		foreach($events as $event) {
			$list[] = $event['_attribute']['name'];
		}
		return $list;
	}

	public function getEndPoint($event)
	{
		$url = (array_key_exists(self::ENVIRONMENT_VARIABLE, $_ENV)?$_ENV[self::ENVIRONMENT_VARIABLE]:'');
		return ($url?$url.'/'.$event:'');
	}

	public function validModel($model)
	{
		try {
			$filePath = $this->_fileReader->getModuleDir('etc', 'Styler_WebhookPublisher') . '/' . self::INCLUDE_EXCLUDE_FILE;
			$parsedArray = $this->_parser->load($filePath)->xmlToArray();
		}
		catch(\Exception $e) {
			$this->_logger->error($e->getMessage());
			return false;
		}

		$events = $this->getEventList($parsedArray);
		if ($this->isInclude($parsedArray)) {
			if (!in_array($model,$events)) return false;
		}
		elseif ($this->isExclude($parsedArray)) {
			if (in_array($model,$events)) return false;
		}

		return true;
	}

}
