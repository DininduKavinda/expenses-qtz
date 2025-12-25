<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create Quartz (Tenant/Workplace)
        $quartz = \App\Models\Quartz::create([
            'name' => 'Main Head Office',
            'description' => 'Head Office Tenant'
        ]);

        // 2. Create Roles
        $adminRole = \App\Models\Role::create(['name' => 'Administrator', 'slug' => 'admin', 'description' => 'Admin Access']);
        $managerRole = \App\Models\Role::create(['name' => 'Manager', 'slug' => 'manager', 'description' => 'Manager Access']);
        $viewerRole = \App\Models\Role::create(['name' => 'Viewer', 'slug' => 'viewer', 'description' => 'Read Only']);

        // 3. Create Users linked to Quartz and Role
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@quartz.com',
            'password' => bcrypt('1'),
            'role_id' => $adminRole->id,
            'quartz_id' => $quartz->id,
        ]);

        $manager = User::create([
            'name' => 'Manager User',
            'email' => 'manager@quartz.com',
            'password' => bcrypt('1'),
            'role_id' => $managerRole->id,
            'quartz_id' => $quartz->id,
        ]);

        // 4. Create Metadata (Global or Tenant based on schema - currently Global except Brands linked to Category)

        // Categories
        $catElectronics = \App\Models\Category::create(['name' => 'Electronics']);
        $catGroceries = \App\Models\Category::create(['name' => 'Groceries']);
        $catStationery = \App\Models\Category::create(['name' => 'Stationery']);

        // Brands (Linked to Categories)
        \App\Models\Brand::create(['name' => 'Samsung', 'category_id' => $catElectronics->id]);
        \App\Models\Brand::create(['name' => 'Apple', 'category_id' => $catElectronics->id]);
        \App\Models\Brand::create(['name' => 'Keells', 'category_id' => $catGroceries->id]);
        \App\Models\Brand::create(['name' => 'Atlas', 'category_id' => $catStationery->id]);

        // Units
        \App\Models\Unit::create(['name' => 'kg', 'description' => 'Kilograms']);
        \App\Models\Unit::create(['name' => 'pcs', 'description' => 'Pieces']);
        \App\Models\Unit::create(['name' => 'ltr', 'description' => 'Liters']);
        \App\Models\Unit::create(['name' => 'm', 'description' => 'Meters']);

        // Shops
        \App\Models\Shop::create(['name' => 'Singer Mega', 'location' => 'Colombo']);
        \App\Models\Shop::create(['name' => 'Keells Super', 'location' => 'Kandy']);
        \App\Models\Shop::create(['name' => 'Softlogic', 'location' => 'Galle']);
        \App\Models\Shop::create(['name' => 'Local Hardware', 'location' => 'Nugegoda']);

        // 5. Create Default Bank Account for Quartz
        \App\Models\BankAccount::create([
            'quartz_id' => $quartz->id,
            'name' => 'Main Operations Bank',
            'balance' => 0.00 // Initial Float
        ]);

        \App\Models\BankAccount::create([
            'quartz_id' => $quartz->id,
            'name' => 'Petty Cash',
            'balance' => 0.00
        ]);

        // 6. Create 10 Items for a Shop (Keells Super)
        $keells = \App\Models\Shop::where('name', 'Keells Super')->first();
        $kg = \App\Models\Unit::where('name', 'kg')->first();
        $pcs = \App\Models\Unit::where('name', 'pcs')->first();
        $ltr = \App\Models\Unit::where('name', 'ltr')->first();

        $itemsData = [
            ['name' => 'Rice (Samba)', 'category_id' => $catGroceries->id, 'unit_id' => $kg->id, 'price' => 220.00],
            ['name' => 'Sugar (White)', 'category_id' => $catGroceries->id, 'unit_id' => $kg->id, 'price' => 350.00],
            ['name' => 'Dhal (Mysore)', 'category_id' => $catGroceries->id, 'unit_id' => $kg->id, 'price' => 420.00],
            ['name' => 'Milk Powder (400g)', 'category_id' => $catGroceries->id, 'unit_id' => $pcs->id, 'price' => 950.00],
            ['name' => 'Coconut Oil (1L)', 'category_id' => $catGroceries->id, 'unit_id' => $ltr->id, 'price' => 850.00],
            ['name' => 'Tea Leaves (Leaf)', 'category_id' => $catGroceries->id, 'unit_id' => $kg->id, 'price' => 1200.00],
            ['name' => 'Noodles (Pack)', 'category_id' => $catGroceries->id, 'unit_id' => $pcs->id, 'price' => 180.00],
            ['name' => 'Soap (Bar)', 'category_id' => $catGroceries->id, 'unit_id' => $pcs->id, 'price' => 125.00],
            ['name' => 'Toothpaste', 'category_id' => $catGroceries->id, 'unit_id' => $pcs->id, 'price' => 240.00],
            ['name' => 'Biscuits (Marie)', 'category_id' => $catGroceries->id, 'unit_id' => $pcs->id, 'price' => 150.00],
        ];

        foreach ($itemsData as $data) {
            $item = \App\Models\Item::create([
                'name' => $data['name'],
                'category_id' => $data['category_id'],
                // 'brand_id' can be null or assigned if we had logic, sticking to null for generic
                'unit_id' => $data['unit_id']
            ]);

            // Assign Price for Keells Super
            \App\Models\ItemPrice::create([
                'item_id' => $item->id,
                'shop_id' => $keells->id,
                'unit_id' => $data['unit_id'],
                'price' => $data['price'],
                'date' => now()
            ]);
        }

        // 7. Seed Permissions and assign to roles
        $this->call(PermissionSeeder::class);
    }
}
