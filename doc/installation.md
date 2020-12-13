## Installation

1. Run `composer require odiseoteam/sylius-avatax-plugin --no-scripts`

2. Enable the plugin in bundles.php

```php
<?php
// config/bundles.php

return [
    // ...
    Odiseo\SyliusAvataxPlugin\OdiseoSyliusAvataxPlugin::class => ['all' => true],
];
```

3. Import the plugin configurations

```yml
# config/packages/_sylius.yaml
imports:
    ...

    - { resource: "@OdiseoSyliusAvataxPlugin/Resources/config/config.yaml" }
```

4. Finish the installation

```
php bin/console sylius:theme:assets:install
php bin/console cache:clear
```
