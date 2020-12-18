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

4. Add the admin routes

```yml
# config/routes.yaml
odiseo_sylius_avatax_plugin_admin:
    resource: "@OdiseoSyliusAvataxPlugin/Resources/config/routing/admin.yaml"
    prefix: /admin
```

5. This plugin includes an API version. If you want to use it you have to add the route

```yml
# config/routes.yaml
odiseo_sylius_avatax_plugin_api:
    resource: "@OdiseoSyliusAvataxPlugin/Resources/config/routing/api.yaml"
    prefix: /api
```

6. Finish the installation updating the database schema and installing assets

```
php bin/console doctrine:schema:update --force
php bin/console sylius:theme:assets:install
php bin/console cache:clear
```
