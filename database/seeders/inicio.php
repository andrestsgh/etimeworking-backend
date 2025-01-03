<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Contract;
use App\Models\Record;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class inicio extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        User::create([
            'name' => 'Andrés',
            'email' => 'admin@uoc.edu',
            'password' => bcrypt('admin1'),
            'first_surname' => 'Terol',
            'second_surname' => 'Sánchez',
            'birthdate' => '2000-01-01',
            'register_date' => now(),
            'phone' => '123456789',
            'dni' => '12345678C',
            'address' => '123 Calle Limoneros',
            'city' => 'Alicante',
            'url_picture' => 'http://laravel.local:8000/assets/images/user-default.png', 
            'role' => 'admin',
        ]);
        User::create([
            'name' => 'usu1',
            'email' => 'usu1@uoc.edu',
            'password' => bcrypt('admin1'),
            'first_surname' => 'Terol',
            'second_surname' => 'Sánchez',
            'birthdate' => '2000-01-01',
            'register_date' => now(),
            'phone' => '123456789',
            'dni' => '11111111A',
            'address' => '123 Calle Limoneros',
            'city' => 'Alicante',
            'url_picture' => 'http://laravel.local:8000/assets/images/user-default.png', 
            'role' => 'employee',
        ]);
        // Crea 10 usuarios
        foreach (range(1, 10) as $index) {
            User::create([
                'name' => $faker->name,
                'first_surname' => 'Terol',
                'second_surname' => 'Sánchez',
                'birthdate' => '2000-01-01',
                'register_date' => now(),
                'phone' => '123456789',
                'email' => $faker->unique()->email,
                'city' => $faker->city,
                'password' => bcrypt('admin1'),
                'address' => '123 Calle Limoneros',
                'url_picture' => 'http://laravel.local:8000/assets/images/user-default.png', 
                'role' => $faker->randomElement(['admin', 'employee']),
                'dni' => ((10000000+$index).'A'),
            ]);
        }
        Company::create([
            'name' => 'Empresa1',
            'cif' => '87654321B',
            'city' => 'Otra Ciudad',
            'country' => 'España',
            'email' => 'empresa1@ejemplo.com',
            'phone' => '654987321',
            'address' => 'Avenida Imaginaria, 456',
        ]);
        // Crea 5 empresas
        foreach (range(1, 5) as $index) {
            Company::create([
                'name' => $faker->company,
                'cif' => ((10000000+$index).'B'),
                'city' => $faker->city,
                'country' => $faker->country,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
            ]);
        }
        // Crea 10 contratos
        foreach (range(1, 10) as $index) {
            // Selecciona un usuario y empresa aleatorios
            $user = User::inRandomOrder()->first();
            $company = Company::inRandomOrder()->first();

            Contract::create([
                'user_id' => $user->id,
                'user_dni' => $user->dni,
                'type' => $faker->randomElement(['Temporal', 'Indefinido', 'Discontinuo']),
                'begin_date' => $faker->date(),
                'end_date' => $faker->date(),
                'company_cif' => $company->cif,
                'company_id' => $company->id,
                'hours' => $faker->numberBetween(20, 40),
                'periodicity' => $faker->randomElement(['daily', 'weekly', 'monthly']),
                'job_position' => $faker->jobTitle,
            ]);
        }

        // Crea 20 registros
        foreach (range(1, 20) as $index) {
            // Selecciona un contrato aleatorio
            $contract = Contract::inRandomOrder()->first(); 

            Record::create([
                'contract_id' => $contract->id,
                'sign_time' => $faker->datetime(),
                'finished' => $faker->numberBetween(0, 1),
            ]);
        }
    }
}
