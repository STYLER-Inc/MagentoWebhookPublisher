# WebhookPublisher

Generic publisher for raising model save events to a webhook.

USAGE:

1. Define include/exclude models in /etc/includes_excludes.xml following the existing xml structure
2. Define an environment variable STYLER_EVENT_WEBHOOK_URL with the endpoint (do not end with a slash)
3. Upload module to magento app/code folder

NOTES:

- Include/exclude filename can be changed in Helper/Data.php
- Endpoint environment variable name can be changed in Helper/Data.php

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)
