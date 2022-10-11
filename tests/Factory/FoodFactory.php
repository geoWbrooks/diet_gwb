<?php

namespace App\Tests\Factory;

use App\Entity\Food;
use App\Repository\FoodRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Food>
 *
 * @method static Food|Proxy createOne(array $attributes = [])
 * @method static Food[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Food[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Food|Proxy find(object|array|mixed $criteria)
 * @method static Food|Proxy findOrCreate(array $attributes)
 * @method static Food|Proxy first(string $sortedField = 'id')
 * @method static Food|Proxy last(string $sortedField = 'id')
 * @method static Food|Proxy random(array $attributes = [])
 * @method static Food|Proxy randomOrCreate(array $attributes = [])
 * @method static Food[]|Proxy[] all()
 * @method static Food[]|Proxy[] findBy(array $attributes)
 * @method static Food[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Food[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static FoodRepository|RepositoryProxy repository()
 * @method Food|Proxy create(array|callable $attributes = [])
 */
final class FoodFactory extends ModelFactory
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
            'food_name' => ucfirst(self::faker()->unique()->word()),
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
        // ->afterInstantiate(function(Food $food): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Food::class;
    }

}
