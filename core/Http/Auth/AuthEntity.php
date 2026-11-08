<?php

/**
 * @copyright 2019-2026 N'Guessan Kouadio Elisée <eliseekn@gmail.com>
 * @license MIT (https://opensource.org/licenses/MIT)
 *
 * @link https://github.com/eliseekn/tinymvc
 */

declare(strict_types=1);

namespace Core\Http\Auth;

use Core\Database\Entity;

abstract class AuthEntity extends Entity implements Authenticable
{
    public function getProviderName(): string
    {
        foreach (config('security.auth.providers') as $name => $provider) {
            if ($name !== 'default' && ($provider['entity'] ?? null) === static::class) {
                return $name;
            }
        }

        return config('security.auth.providers.default');
    }

    public function getProviderEntity(): string
    {
        return config('security.auth.providers.'.$this->getProviderName().'.entity');
    }

    public function getProviderIdentifier(): string
    {
        return config('security.auth.providers.'.$this->getProviderName().'.identifier');
    }

    public function getIdentifierValue(): string
    {
        return (string) $this->{'get'.ucfirst($this->getProviderIdentifier())}();
    }

    public function getEntity(string $identifier): ?Entity
    {
        $entity = $this->getProviderEntity();

        return (new $entity)
            ->toModel()
            ->findBy($this->getProviderIdentifier(), $identifier)
            ?->toEntity($entity);
    }
}
