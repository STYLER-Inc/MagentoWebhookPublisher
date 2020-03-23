<?php

namespace Styler\WebhookPublisher\Observer;

use Magento\Framework\Event\ObserverInterface;

class DetectModelSaveAfter implements ObserverInterface
{
	protected $logger;
	public function __construct(\Psr\Log\LoggerInterface $logger)
	{
		$this->logger = $logger;
	}

	public function execute(\Magento\Framework\Event\Observer $observer)
	{
		$event = $observer->getEvent();
		$object = $event->getData('object');
		$this->logger->debug(json_encode($object->getData()));
	}
}