<?php

namespace App\Factory;

use App\Entity\Dummy;
use App\Repository\DummyRepository;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;
use Zenstruck\Foundry\RepositoryProxy;

/**
 * @extends ModelFactory<Dummy>
 *
 * @method        Dummy|Proxy                     create(array|callable $attributes = [])
 * @method static Dummy|Proxy                     createOne(array $attributes = [])
 * @method static Dummy|Proxy                     find(object|array|mixed $criteria)
 * @method static Dummy|Proxy                     findOrCreate(array $attributes)
 * @method static Dummy|Proxy                     first(string $sortedField = 'id')
 * @method static Dummy|Proxy                     last(string $sortedField = 'id')
 * @method static Dummy|Proxy                     random(array $attributes = [])
 * @method static Dummy|Proxy                     randomOrCreate(array $attributes = [])
 * @method static DummyRepository|RepositoryProxy repository()
 * @method static Dummy[]|Proxy[]                 all()
 * @method static Dummy[]|Proxy[]                 createMany(int $number, array|callable $attributes = [])
 * @method static Dummy[]|Proxy[]                 createSequence(iterable|callable $sequence)
 * @method static Dummy[]|Proxy[]                 findBy(array $attributes)
 * @method static Dummy[]|Proxy[]                 randomRange(int $min, int $max, array $attributes = [])
 * @method static Dummy[]|Proxy[]                 randomSet(int $number, array $attributes = [])
 */
final class DummyFactory extends ModelFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function getDefaults(): array
    {
        return [
            'dummyText' => self::faker()->text(255),
        ];
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): self
    {
        return $this
            // ->afterInstantiate(function(Dummy $dummy): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Dummy::class;
    }
}
