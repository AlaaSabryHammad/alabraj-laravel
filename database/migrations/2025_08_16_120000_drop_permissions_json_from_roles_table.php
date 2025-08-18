<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Move any JSON stored permissions into the pivot then drop the column to eliminate name collision.
     */
    public function up(): void
    {
        if (Schema::hasColumn('roles', 'permissions')) {
            // Migrate existing JSON permissions (legacy) into role_permissions pivot if missing
            $roles = DB::table('roles')->select('id', 'permissions')->whereNotNull('permissions')->get();
            foreach ($roles as $role) {
                $decoded = json_decode($role->permissions, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $permName) {
                        $permissionId = DB::table('permissions')->where('name', $permName)->value('id');
                        if ($permissionId) {
                            // Ensure unique pair
                            $exists = DB::table('role_permissions')
                                ->where('role_id', $role->id)
                                ->where('permission_id', $permissionId)
                                ->exists();
                            if (!$exists) {
                                DB::table('role_permissions')->insert([
                                    'role_id' => $role->id,
                                    'permission_id' => $permissionId,
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ]);
                            }
                        }
                    }
                }
            }

            Schema::table('roles', function (Blueprint $table) {
                $table->dropColumn('permissions');
            });
        }
    }

    /**
     * Restore the JSON column (best-effort) and repopulate from pivot for rollback.
     */
    public function down(): void
    {
        if (!Schema::hasColumn('roles', 'permissions')) {
            Schema::table('roles', function (Blueprint $table) {
                $table->json('permissions')->nullable();
            });

            // Rebuild JSON from pivot data
            $roles = DB::table('roles')->select('id')->get();
            foreach ($roles as $role) {
                $permNames = DB::table('role_permissions')
                    ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
                    ->where('role_permissions.role_id', $role->id)
                    ->pluck('permissions.name')
                    ->toArray();
                DB::table('roles')->where('id', $role->id)->update([
                    'permissions' => json_encode(array_values($permNames))
                ]);
            }
        }
    }
};
