<?php
namespace Styler\WebhookPublisher\Helper;

use Styler\WebhookPublisher\Helper\Data as Helper;
/**
 * Generix Observer
 */
class GenericObserver
{
  protected $_logger;
	/**
	 * @var \Magento\Framework\HTTP\Client\Curl
	 */
	protected $_curl;

	protected $_helper;

	public function __construct(
		\Psr\Log\LoggerInterface $logger,
		\Magento\Framework\HTTP\Client\Curl $curl,
		Helper $helper
	)
	{
		$this->_logger = $logger;
		$this->_curl = $curl;
		$this->_helper = $helper;
	}

  protected function getEvent()
  {
    return 'generic';
  }

	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		$event = $observer->getEvent();
		$object = $event->getData('object');
		$model = $object->getEventPrefix();
		$this->_logger->debug(mb_strtoupper($this->getEvent()) . " observer: start...");
		$this->_logger->debug(json_encode($object->getData()));

		if (!$this->_helper->validModel($model)) {
			$this->_logger->debug("Disregarding model: ".$model);
			return;
		}

		$url = $this->_helper->getEndPoint($this->getEvent());
		if (!$url) {
			$this->_logger->error('URL endpoint is not defined');
			return;
		}

		$params = [
			'event' => $this->getEvent(),
			'model' => $model
		];

		try {
			$this->_curl->addHeader("Content-Type","application/json");
			$this->_curl->addHeader("Accept","application/json");
			$this->_curl->post($url, json_encode($params));

			$response = $this->_curl->getBody();
			$this->_logger->debug(json_encode($response));
		}
		catch(\Exception $e)
		{
			$this->_logger->error($e->getMessage());
		}
		$this->_logger->debug(mb_strtoupper($this->getEvent()) . " observer: end");
	}
}
