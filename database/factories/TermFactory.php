<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Category;
use App\Models\Term;
use App\User;
use Faker\Generator as Faker;

$factory->define(Term::class, function (Faker $faker) {
    return [
        'user_id' => null,
        'category_id' => null,
        'slug' => $faker->slug,
        'origin' => $faker->word,
        'locale' => $faker->word,
        'source' => $faker->url,
    ];
});
