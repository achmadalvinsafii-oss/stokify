<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const LEGACY_USER_ID = 1;

    private const INVENTORY_TABLES = [
        'products',
        'stock_entries',
        'stock_exits',
    ];

    public function up(): void
    {
        $hasLegacyData = collect(self::INVENTORY_TABLES)
            ->contains(fn (string $table) => DB::table($table)->exists());

        $legacyUserExists = DB::table('users')
            ->where('id', self::LEGACY_USER_ID)
            ->where('username', 'adminstokify')
            ->exists();

        if ($hasLegacyData && ! $legacyUserExists) {
            throw new RuntimeException(
                'Data inventaris lama tidak dapat dikaitkan: users.id=1 harus merupakan akun adminstokify.'
            );
        }

        foreach (self::INVENTORY_TABLES as $tableName) {
            if (! Schema::hasColumn($tableName, 'user_id')) {
                DB::statement(
                    'ALTER TABLE "'.$tableName.'" ADD COLUMN "user_id" INTEGER NULL REFERENCES "users" ("id") ON DELETE RESTRICT'
                );
            }

            DB::table($tableName)
                ->whereNull('user_id')
                ->update(['user_id' => self::LEGACY_USER_ID]);
        }
    }

    public function down(): void
    {
        if (DB::connection()->getDriverName() === 'sqlite') {
            throw new RuntimeException(
                'Rollback otomatis dinonaktifkan untuk SQLite agar tidak membangun ulang tabel dan mengubah foreign key.'
            );
        }

        foreach (array_reverse(self::INVENTORY_TABLES) as $tableName) {
            if (Schema::hasColumn($tableName, 'user_id')) {
                Schema::table($tableName, function (Blueprint $table): void {
                    $table->dropConstrainedForeignId('user_id');
                });
            }
        }
    }
};
