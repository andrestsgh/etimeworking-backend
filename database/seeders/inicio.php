<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Contract;
use App\Models\Record;
use App\Models\User;
use Carbon\Carbon;
//use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class inicio extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Instancia faker para crear datos de prueba generados aleatoriamente
        $faker = Faker::create('es_ES');

        User::create([
            'name' => 'Andrés',
            'email' => 'admin@uoc.edu',
            'password' => bcrypt('admin1'),
            'first_surname' => 'Terol',
            'second_surname' => 'Sánchez',
            'birthdate' => '1979-06-16',
            'register_date' => now(),
            'phone' => '123456789',
            'dni' => '12345678A',
            'address' => 'Calle Limoneros, 12',
            'city' => 'Alicante',
            'url_picture' => 'storage/images/user-default.png', 
            'role' => 'admin',
        ]);
        
        $maria_employee = User::create([
            'name' => 'María',
            'email' => 'maria@uoc.edu',
            'password' => bcrypt('admin1'),
            'first_surname' => 'Hernández',
            'second_surname' => 'Sánchez',
            'birthdate' => '1990-01-01',
            'register_date' => now(),
            'phone' => '123456781',
            'dni' => '11111110A',
            'address' => 'Calle Naranjos, 34',
            'city' => 'Murcia',
            'url_picture' => 'storage/images/user-default.png', 
            'role' => 'employee',
        ]);

        $joan_employee = User::create([
            'name' => 'Joan',
            'email' => 'joan@uoc.edu',
            'password' => bcrypt('admin1'),
            'first_surname' => 'Martorell',
            'second_surname' => 'Cipres',
            'birthdate' => '1988-07-01',
            'register_date' => now(),
            'phone' => '123456782',
            'dni' => '11111111A',
            'address' => 'Calle Manzanas, 56',
            'city' => 'Barcelona',
            'url_picture' => 'storage/images/user-default.png', 
            'role' => 'employee',
        ]);

        // Crea 50 usuarios aleatorios
        foreach (range(1, 50) as $index) {
            User::create([
                'name' => $faker->firstName,
                'first_surname' => $faker->lastName,
                'second_surname' => $faker->lastName,
                'birthdate' => $faker->dateTimeBetween('-70 years', '-25 years'),
                'register_date' => now(),
                'phone' => $faker->phoneNumber,
                'email' => $faker->unique()->email,
                'city' => $faker->city,
                'password' => bcrypt('admin1'),
                'address' => $faker->address,
                'url_picture' => 'storage/images/face_' . $index . '.jpg', 
                'role' => 'employee',
                'dni' => (($index+11111111).'A'),
            ]);
        }

        $uoc_company = Company::create([
            'name' => 'UOC',
            'cif' => '22222220B',
            'city' => 'Barcelona',
            'country' => 'España',
            'email' => 'uoc@uoc.edu',
            'phone' => '654987321',
            'address' => 'Avenida Limoneros, 12',
        ]);
        
        $stark_company = Company::create([
            'name' => 'Stark Industries',
            'cif' => '22222221B',
            'city' => 'Alicante',
            'country' => 'España',
            'email' => 'marvel@uoc.edu',
            'phone' => '98372612',
            'address' => 'Avenida Ironman, 34',
        ]);
        
        // Crea 25 empresas aleatorias
        foreach (range(1, 25) as $index) {
            Company::create([
                'name' => $faker->company,
                'cif' => (($index+22222221).'B'),
                'city' => $faker->city,
                'country' => $faker->randomElement(['España','Estados Unidos','Francia','Alemania','Italia']),
                'email' => $faker->unique()->email,
                'phone' => $faker->phoneNumber,
                'address' => $faker->address,
            ]);
        }
        // Contrato 1 para Maria
        Contract::create([
            'user_id' => $maria_employee->id,
            'user_dni' => $maria_employee->dni,
            'type' => $faker->randomElement(['Temporal', 'Indefinido', 'Discontinuo']),
            'begin_date' => $faker->dateTimeBetween('-10 years', '-6 years'),
            'end_date' => $faker->dateTimeBetween('-5 years', '-1 years'),
            'company_cif' => $uoc_company->cif,
            'company_id' => $uoc_company->id,
            'hours' => $faker->numberBetween(5, 40),
            'periodicity' => $faker->randomElement(['daily', 'weekly', 'monthly']),
            'job_position' => $faker->jobTitle,
        ]);
        // Contrato 2 para Maria
        Contract::create([
            'user_id' => $maria_employee->id,
            'user_dni' => $maria_employee->dni,
            'type' => $faker->randomElement(['Temporal', 'Indefinido', 'Discontinuo']),
            'begin_date' => $faker->dateTimeBetween('-10 years', '-6 years'),
            'end_date' => $faker->dateTimeBetween('-5 years', '-1 years'),
            'company_cif' => $stark_company->cif,
            'company_id' => $stark_company->id,
            'hours' => $faker->numberBetween(5, 40),
            'periodicity' => $faker->randomElement(['daily', 'weekly', 'monthly']),
            'job_position' => $faker->jobTitle,
        ]);
        // Crea 100 contratos
        foreach (range(1, 100) as $index) {
            // Selecciona un usuario y empresa aleatorios
            $user = User::inRandomOrder()->first();
            $company = Company::inRandomOrder()->first();

            Contract::create([
                'user_id' => $user->id,
                'user_dni' => $user->dni,
                'type' => $faker->randomElement(['Temporal', 'Indefinido', 'Discontinuo']),
                'begin_date' => $faker->dateTimeBetween('-10 years', '-6 years'),
                'end_date' => $faker->dateTimeBetween('-5 years', '-1 years'),
                'company_cif' => $company->cif,
                'company_id' => $company->id,
                'hours' => $faker->numberBetween(5, 40),
                'periodicity' => $faker->randomElement(['daily', 'weekly', 'monthly']),
                'job_position' => $faker->jobTitle,
            ]);
        }

        // Crea 1000 registros
        foreach (range(1, 500) as $index) {
            // Selecciona un contrato aleatorio
            $contract = Contract::inRandomOrder()->first(); 

            $startSignTime = $faker->dateTimeBetween('-5 years', '-1 years');
            
            $endSignTime = clone $startSignTime;
            $endSignTime->modify('+' . rand(1, 5) . ' minutes');

            // Crea el registro de entrada y de salida
            Record::insert([
                [
                    'contract_id' => $contract->id,
                    'sign_time' => $startSignTime,
                    'latitude' => $faker->randomFloat(6, -90, 90),
                    'longitude' => $faker->randomFloat(6, -180, 180),
                    'finished' => 0,
                ],
                [
                    'contract_id' => $contract->id,
                    'sign_time' => $endSignTime,
                    'latitude' => $faker->randomFloat(6, -90, 90),
                    'longitude' => $faker->randomFloat(6, -180, 180),
                    'finished' => 1,
                ],
            ]);
        }
    }
}
