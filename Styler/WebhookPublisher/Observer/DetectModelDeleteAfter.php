<?php
namespace Styler\WebhookPublisher\Observer;

use Magento\Framework\Event\ObserverInterface;
use Styler\WebhookPublisher\Helper\GenericObserver;

class DetectModelDeleteAfter extends GenericObserver implements ObserverInterface
{
	protected function getEvent()
  {
    return 'delete';
  }
}
