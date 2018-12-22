# BBOX WEB SERVICE
Upload file to BBOX from client

New Service Object

```php
require 'vendor/autoload.php';

use Bstar\Bbox\Client;

$bboxClient = new Client();

```

Create Object Upload

```php
$bboxClient->upload();
```