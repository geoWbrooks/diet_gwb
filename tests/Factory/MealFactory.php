<?php

namespace App\Tests\Factory;

use App\Entity\Meal;
use App\Repository\MealRepository;
use Zenstruck\Foundry\RepositoryProxy;
use Zenstruck\Foundry\ModelFactory;
use Zenstruck\Foundry\Proxy;

/**
 * @extends ModelFactory<Meal>
 *
 * @method static Meal|Proxy createOne(array $attributes = [])
 * @method static Meal[]|Proxy[] createMany(int $number, array|callable $attributes = [])
 * @method static Meal[]|Proxy[] createSequence(array|callable $sequence)
 * @method static Meal|Proxy find(object|array|mixed $criteria)
 * @method static Meal|Proxy findOrCreate(array $attributes)
 * @method static Meal|Proxy first(string $sortedField = 'id')
 * @method static Meal|Proxy last(string $sortedField = 'id')
 * @method static Meal|Proxy random(array $attributes = [])
 * @method static Meal|Proxy randomOrCreate(array $attributes = [])
 * @method static Meal[]|Proxy[] all()
 * @method static Meal[]|Proxy[] findBy(array $attributes)
 * @method static Meal[]|Proxy[] randomSet(int $number, array $attributes = [])
 * @method static Meal[]|Proxy[] randomRange(int $min, int $max, array $attributes = [])
 * @method static MealRepository|RepositoryProxy repository()
 * @method Meal|Proxy create(array|callable $attributes = [])
 */
final class MealFactory extends ModelFactory
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
            'meal_type' => self::faker()->text(),
            'date' => null, // TODO add DATETIME ORM type manually
        ];
    }

    protected function initialize(): self
    {
        // see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
        return $this
            // ->afterInstantiate(function(Meal $meal): void {})
        ;
    }

    protected static function getClass(): string
    {
        return Meal::class;
    }
}
