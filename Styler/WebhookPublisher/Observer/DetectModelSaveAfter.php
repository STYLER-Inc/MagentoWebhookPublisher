<?php
namespace Styler\WebhookPublisher\Observer;

use Magento\Framework\Event\ObserverInterface;
use Styler\WebhookPublisher\Helper\GenericObserver;

class DetectModelSaveAfter extends GenericObserver implements ObserverInterface
{
	protected function getEvent()
  {
    return 'save';
  }
}
