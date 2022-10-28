<?php

namespace App\Tests\Factory;

use App\Entity\Gut;
use App\Repository\GutRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Gut>
 *
 * @method static Gut|Proxy createOne(array $attributes = [])
 * @method static Gut[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Gut[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Gut|Proxy find(object|array|mixed $criteria)
 * @method static Gut|Proxy findOrCreate(array $attributes)
 * @method static Gut|Proxy first(string $sortedField = 'id')
 * @method static Gut|Proxy last(string $sortedField = 'id')
 * @method static Gut|Proxy random(array $attributes = [])
 * @method static Gut|Proxy randomOrCreate(array $attributes = [])
 * @method static Gut[]|Proxy[] all()
 * @method static Gut[]|Proxy[] findBy(array $attributes)
 * @method static Gut[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Gut[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static GutRepository|RepositoryProxy repository()
 * @method Gut|Proxy create(array|callable $attributes = [])
 */
final class GutFactory extends ModelFactory
{

    public function __construct()
    {
        parent::__construct();

        // TODO inject services if required (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services)
    }

    protected function getDefaults(): array
    {
        return [
            // TODO add your default values here (https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories)
//            'datetime' => self::faker()->dateTimeBetween('-365 days', '-1 days'),
            'description' => ucfirst(self::faker()->unique()->word()),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
        // ->afterInstantiate(function(Gut $gut): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Gut::class;
    }

}
