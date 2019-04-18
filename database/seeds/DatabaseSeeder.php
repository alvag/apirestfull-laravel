<?php

use App\Category;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        $cantUsuarios = 1000;
        $cantCategorias = 30;
        $cantProductos = 1000;
        $cantTransacciones = 1000;

        factory(User::class, $cantUsuarios)->create();
        factory(Category::class, $cantCategorias)->create();
        factory(Product::class, $cantProductos)->create()->each(
            function ($product) {
                $categorias = Category::all()->random(mt_rand(1, 5))->pluck('id');
                $product->categories()->attach($categorias);
            }
        );
        factory(Transaction::class, $cantTransacciones)->create();
    }
}
