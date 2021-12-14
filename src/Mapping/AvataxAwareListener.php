<?php

declare(strict_types=1);

namespace Odiseo\SyliusAvataxPlugin\Mapping;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Mapping\ClassMetadata;
use Odiseo\SyliusAvataxPlugin\Entity\AvataxAwareInterface;
use Sylius\Component\Product\Model\ProductInterface;

final class AvataxAwareListener implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::loadClassMetadata,
        ];
    }

    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs): void
    {
        $classMetadata = $eventArgs->getClassMetadata();
        $reflection = $classMetadata->reflClass;

        /**
         * @phpstan-ignore-next-line
         */
        if ($reflection === null || $reflection->isAbstract()) {
            return;
        }

        if (
            $reflection->implementsInterface(ProductInterface::class) &&
            $reflection->implementsInterface(AvataxAwareInterface::class)
        ) {
            $this->mapAvataxAware($classMetadata);
        }
    }

    private function mapAvataxAware(ClassMetadata $metadata): void
    {
        if (!$metadata->hasField('avataxCode')) {
            $metadata->mapField([
                'fieldName' => 'avataxCode',
                'columnName' => 'avatax_code',
                'type' => 'string',
                'nullable' => true
            ]);
        }
    }
}
