<?php

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    public function run()
    {
        $centerid = SystemCenterDistribution::select(['centerid'])->first();
        $warehouse = SystemWarehouse::select(['warehouseid'])
            ->whereCenterid($centerid->centerid)->first();

        User::create([
            'name' => 'EDGAR PRISCILIANO LOPEZ',
            'email' => 'edgar.lopez@gruposid.com.mx',
            'password' => bcrypt('hosqkx91'),
            'username' => 'priscedg',
            'password2' => 'hosqkx91',
            'schemaid' => 'STS',
            'statusid' => 'E'
        ]);

        SecurityAccessWarehouse::create([
            'centerid' => $centerid->centerid,
            'warehouseid' => $warehouse->warehouseid,
            'username' => 'priscedg',
        ]);
        
    }
}
