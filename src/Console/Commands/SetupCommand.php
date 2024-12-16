<?php

namespace Magicbox\LaraQuick\Console\Commands;

use Illuminate\Console\Command;

class SetupCommand extends Command {
    
    protected $signature = 'laraquick:setup';
    protected $description = 'Interactive Setup For LaraQuick';

    public function handle(){
        $this->info('Welcome to LaraQuick Setup!');

        //Step 1 : Pilih Modul
        $modules = $this->choice(
            'whice modules would you like to enable ?',
            ['Inventory', 'Sales','CRM','None'],
            0,
            null,
            true
        );

        $this->info('Selected Modules:' . implode(',', $modules));

        //Step 2: Pilih Framework UI
        $uiFramework = $this->choice(
            'whice UI framework modules would you like to use ?',
            ['Bootstrap', 'Tailwind','Vue.js'],
            0
        );

        $this->info('Selected UI Framework:' . implode(',', $uiFramework));

        //Step 3: Pilih login system and roles

        $enableLogin = $this->confirm('would you like to enable authentication with user roles?', true);

        if($enableLogin){
            $this->info('Configuring authentication system.....');
            //menambahkan login dan registrasi
            Artisan::call('make:auth'); //membuat auth jika belum tersedia
            $this->info('Authentication system created!');

            //Step 4: Pilih roles dan permissions
            $roles = $this->choice(
                'Choose the roles you want to create for the system (Admin, User, etc.)',
                ['Admin', 'User', 'Editor'],
                0,
                null,
                true
            );
            
            $this->info('Roles:' . implode(',', $roles));

            //menambahkan seeder untuk roles dan permission
            $this->call('db:seed', ['--class' => 'RoleSeeder']);
        }

        //Step 5: Pilih untuk menambahkan data dummy
        $addDummyData = $this->confirm('would you like to generate some dummy data?', true);

        if($addDummyData){
            $this->info('Generating dummy data.....');
            //Jalankan seeder untuk data dummy
            Artisan::call('db:seed'); 
            $this->info('Dummy data generated!');
        }

        //Step 6: Membuat database structure

        $this->info('Setting up datbase structure........');

        if(in_array('Inventory', $modules)){
            $this->call('migrate',['--path' => 'database/migrations/inventory']);
        }

        if(in_array('Sales', $modules)){
            $this->call('migrate',['--path' => 'database/migrations/sales']);
        }
        
        if(in_array('CRM', $modules)){
            $this->call('migrate',['--path' => 'database/migrations/crm']);
        }
        
        if(in_array('Company Profile', $modules)){
            $this->call('migrate',['--path' => 'database/migrations/company']);
        }

        //menyelesaikan proses setup
        $this->info('Setup Complete!');
    }
}
