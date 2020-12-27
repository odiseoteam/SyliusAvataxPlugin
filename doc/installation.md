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

6. Include traits and override the models

```php
<?php
// src/Entity/Product/Product.php

// ...
use Doctrine\ORM\Mapping as ORM;
use Odiseo\SyliusAvataxPlugin\Entity\AvataxAwareInterface;
use Odiseo\SyliusAvataxPlugin\Entity\AvataxTrait;
use Sylius\Component\Core\Model\Product as BaseProduct;

/**
 * @ORM\Table(name="sylius_product")
 * @ORM\Entity
 */
class Product extends BaseProduct implements AvataxAwareInterface
{
    use AvataxTrait;

    // ...
}
```

7. Add the avatax code field to the product form page. So, you need to run `mkdir -p templates/bundles/SyliusAdminBundle/Product/Tab` then `cp vendor/sylius/sylius/src/Sylius/Bundle/AdminBundle/Resources/views/Product/Tab/_details.html.twig templates/bundles/SyliusAdminBundle/Product/Tab/_details.html.twig` and then add the form widget

```twig
{# ... #}
{{ form_row(form.code) }}
{{ form_row(form.enabled) }}
{{ form_row(form.avataxCode) }}
{# ... #}
```

8. Finish the installation updating the database schema and installing assets

```
php bin/console doctrine:schema:update --force
php bin/console sylius:theme:assets:install
php bin/console cache:clear
```
