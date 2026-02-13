<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Adds missing indexes on frequently queried columns and soft deletes
     * to key tables for improved query performance and data retention.
     */
    public function up(): void
    {
        // ---------------------------------------------------------------
        // 1. EQUIPMENT TABLE - Indexes
        // ---------------------------------------------------------------
        Schema::table('equipment', function (Blueprint $table) {
            try {
                $table->index('status', 'equipment_status_index');
            } catch (\Exception $e) {
                // Index may already exist
            }

            try {
                $table->index('location_id', 'equipment_location_id_index');
            } catch (\Exception $e) {
                // Index may already exist
            }

            try {
                $table->index('driver_id', 'equipment_driver_id_index');
            } catch (\Exception $e) {
                // Index may already exist
            }

            try {
                $table->index('type_id', 'equipment_type_id_index');
            } catch (\Exception $e) {
                // Index may already exist
            }

            try {
                $table->index('user_id', 'equipment_user_id_index');
            } catch (\Exception $e) {
                // Index may already exist
            }
        });

        // Soft deletes for equipment
        if (!Schema::hasColumn('equipment', 'deleted_at')) {
            Schema::table('equipment', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // ---------------------------------------------------------------
        // 2. EMPLOYEES TABLE - Indexes
        // ---------------------------------------------------------------
        Schema::table('employees', function (Blueprint $table) {
            try {
                $table->index('status', 'employees_status_index');
            } catch (\Exception $e) {
                // Index may already exist
            }

            try {
                $table->index('location_id', 'employees_location_id_index');
            } catch (\Exception $e) {
                // Index may already exist
            }

            try {
                $table->index('user_id', 'employees_user_id_index');
            } catch (\Exception $e) {
                // Index may already exist
            }

            try {
                $table->index('department', 'employees_department_index');
            } catch (\Exception $e) {
                // Index may already exist
            }

            try {
                $table->index('role', 'employees_role_index');
            } catch (\Exception $e) {
                // Index may already exist
            }
        });

        // Soft deletes for employees
        if (!Schema::hasColumn('employees', 'deleted_at')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // ---------------------------------------------------------------
        // 3. PROJECTS TABLE - Indexes
        // ---------------------------------------------------------------
        Schema::table('projects', function (Blueprint $table) {
            try {
                $table->index('status', 'projects_status_index');
            } catch (\Exception $e) {
                // Index may already exist
            }

            try {
                $table->index('project_manager_id', 'projects_project_manager_id_index');
            } catch (\Exception $e) {
                // Index may already exist
            }

            try {
                $table->index('start_date', 'projects_start_date_index');
            } catch (\Exception $e) {
                // Index may already exist
            }

            try {
                $table->index('end_date', 'projects_end_date_index');
            } catch (\Exception $e) {
                // Index may already exist
            }
        });

        // Soft deletes for projects
        if (!Schema::hasColumn('projects', 'deleted_at')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // ---------------------------------------------------------------
        // 4. EXPENSE VOUCHERS TABLE - Indexes
        // ---------------------------------------------------------------
        Schema::table('expense_vouchers', function (Blueprint $table) {
            try {
                $table->index('status', 'expense_vouchers_status_index');
            } catch (\Exception $e) {
                // Index may already exist
            }

            try {
                $table->index('created_by', 'expense_vouchers_created_by_index');
            } catch (\Exception $e) {
                // Index may already exist
            }

            try {
                $table->index('approved_by', 'expense_vouchers_approved_by_index');
            } catch (\Exception $e) {
                // Index may already exist
            }

            try {
                $table->index('project_id', 'expense_vouchers_project_id_index');
            } catch (\Exception $e) {
                // Index may already exist
            }
        });

        // Soft deletes for expense_vouchers
        if (!Schema::hasColumn('expense_vouchers', 'deleted_at')) {
            Schema::table('expense_vouchers', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // ---------------------------------------------------------------
        // 5. REVENUE VOUCHERS TABLE - Indexes
        // ---------------------------------------------------------------
        Schema::table('revenue_vouchers', function (Blueprint $table) {
            try {
                $table->index('status', 'revenue_vouchers_status_index');
            } catch (\Exception $e) {
                // Index may already exist
            }

            try {
                $table->index('created_by', 'revenue_vouchers_created_by_index');
            } catch (\Exception $e) {
                // Index may already exist
            }

            try {
                $table->index('approved_by', 'revenue_vouchers_approved_by_index');
            } catch (\Exception $e) {
                // Index may already exist
            }
        });

        // Soft deletes for revenue_vouchers
        if (!Schema::hasColumn('revenue_vouchers', 'deleted_at')) {
            Schema::table('revenue_vouchers', function (Blueprint $table) {
                $table->softDeletes();
            });
        }

        // ---------------------------------------------------------------
        // 6. LOCATIONS TABLE - Indexes
        // ---------------------------------------------------------------
        Schema::table('locations', function (Blueprint $table) {
            try {
                $table->index('manager_id', 'locations_manager_id_index');
            } catch (\Exception $e) {
                // Index may already exist
            }

            try {
                $table->index('project_id', 'locations_project_id_index');
            } catch (\Exception $e) {
                // Index may already exist
            }

            try {
                $table->index('status', 'locations_status_index');
            } catch (\Exception $e) {
                // Index may already exist
            }
        });

        // ---------------------------------------------------------------
        // 7. SPARE PARTS TABLE - Indexes
        // ---------------------------------------------------------------
        Schema::table('spare_parts', function (Blueprint $table) {
            try {
                $table->index('spare_part_type_id', 'spare_parts_spare_part_type_id_index');
            } catch (\Exception $e) {
                // Index may already exist
            }

            try {
                $table->index('is_active', 'spare_parts_is_active_index');
            } catch (\Exception $e) {
                // Index may already exist
            }

            // Note: 'category' index already exists from the original migration,
            // but we wrap in try/catch for safety.
            try {
                $table->index('category', 'spare_parts_category_standalone_index');
            } catch (\Exception $e) {
                // Index may already exist
            }
        });

        // ---------------------------------------------------------------
        // 8. CORRESPONDENCES TABLE - Soft Deletes
        // ---------------------------------------------------------------
        if (!Schema::hasColumn('correspondences', 'deleted_at')) {
            Schema::table('correspondences', function (Blueprint $table) {
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // ---------------------------------------------------------------
        // 1. EQUIPMENT TABLE - Drop indexes and soft deletes
        // ---------------------------------------------------------------
        Schema::table('equipment', function (Blueprint $table) {
            try {
                $table->dropIndex('equipment_status_index');
            } catch (\Exception $e) {
            }

            try {
                $table->dropIndex('equipment_location_id_index');
            } catch (\Exception $e) {
            }

            try {
                $table->dropIndex('equipment_driver_id_index');
            } catch (\Exception $e) {
            }

            try {
                $table->dropIndex('equipment_type_id_index');
            } catch (\Exception $e) {
            }

            try {
                $table->dropIndex('equipment_user_id_index');
            } catch (\Exception $e) {
            }
        });

        if (Schema::hasColumn('equipment', 'deleted_at')) {
            Schema::table('equipment', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        // ---------------------------------------------------------------
        // 2. EMPLOYEES TABLE - Drop indexes and soft deletes
        // ---------------------------------------------------------------
        Schema::table('employees', function (Blueprint $table) {
            try {
                $table->dropIndex('employees_status_index');
            } catch (\Exception $e) {
            }

            try {
                $table->dropIndex('employees_location_id_index');
            } catch (\Exception $e) {
            }

            try {
                $table->dropIndex('employees_user_id_index');
            } catch (\Exception $e) {
            }

            try {
                $table->dropIndex('employees_department_index');
            } catch (\Exception $e) {
            }

            try {
                $table->dropIndex('employees_role_index');
            } catch (\Exception $e) {
            }
        });

        if (Schema::hasColumn('employees', 'deleted_at')) {
            Schema::table('employees', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        // ---------------------------------------------------------------
        // 3. PROJECTS TABLE - Drop indexes and soft deletes
        // ---------------------------------------------------------------
        Schema::table('projects', function (Blueprint $table) {
            try {
                $table->dropIndex('projects_status_index');
            } catch (\Exception $e) {
            }

            try {
                $table->dropIndex('projects_project_manager_id_index');
            } catch (\Exception $e) {
            }

            try {
                $table->dropIndex('projects_start_date_index');
            } catch (\Exception $e) {
            }

            try {
                $table->dropIndex('projects_end_date_index');
            } catch (\Exception $e) {
            }
        });

        if (Schema::hasColumn('projects', 'deleted_at')) {
            Schema::table('projects', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        // ---------------------------------------------------------------
        // 4. EXPENSE VOUCHERS TABLE - Drop indexes and soft deletes
        // ---------------------------------------------------------------
        Schema::table('expense_vouchers', function (Blueprint $table) {
            try {
                $table->dropIndex('expense_vouchers_status_index');
            } catch (\Exception $e) {
            }

            try {
                $table->dropIndex('expense_vouchers_created_by_index');
            } catch (\Exception $e) {
            }

            try {
                $table->dropIndex('expense_vouchers_approved_by_index');
            } catch (\Exception $e) {
            }

            try {
                $table->dropIndex('expense_vouchers_project_id_index');
            } catch (\Exception $e) {
            }
        });

        if (Schema::hasColumn('expense_vouchers', 'deleted_at')) {
            Schema::table('expense_vouchers', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        // ---------------------------------------------------------------
        // 5. REVENUE VOUCHERS TABLE - Drop indexes and soft deletes
        // ---------------------------------------------------------------
        Schema::table('revenue_vouchers', function (Blueprint $table) {
            try {
                $table->dropIndex('revenue_vouchers_status_index');
            } catch (\Exception $e) {
            }

            try {
                $table->dropIndex('revenue_vouchers_created_by_index');
            } catch (\Exception $e) {
            }

            try {
                $table->dropIndex('revenue_vouchers_approved_by_index');
            } catch (\Exception $e) {
            }
        });

        if (Schema::hasColumn('revenue_vouchers', 'deleted_at')) {
            Schema::table('revenue_vouchers', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }

        // ---------------------------------------------------------------
        // 6. LOCATIONS TABLE - Drop indexes
        // ---------------------------------------------------------------
        Schema::table('locations', function (Blueprint $table) {
            try {
                $table->dropIndex('locations_manager_id_index');
            } catch (\Exception $e) {
            }

            try {
                $table->dropIndex('locations_project_id_index');
            } catch (\Exception $e) {
            }

            try {
                $table->dropIndex('locations_status_index');
            } catch (\Exception $e) {
            }
        });

        // ---------------------------------------------------------------
        // 7. SPARE PARTS TABLE - Drop indexes
        // ---------------------------------------------------------------
        Schema::table('spare_parts', function (Blueprint $table) {
            try {
                $table->dropIndex('spare_parts_spare_part_type_id_index');
            } catch (\Exception $e) {
            }

            try {
                $table->dropIndex('spare_parts_is_active_index');
            } catch (\Exception $e) {
            }

            try {
                $table->dropIndex('spare_parts_category_standalone_index');
            } catch (\Exception $e) {
            }
        });

        // ---------------------------------------------------------------
        // 8. CORRESPONDENCES TABLE - Drop soft deletes
        // ---------------------------------------------------------------
        if (Schema::hasColumn('correspondences', 'deleted_at')) {
            Schema::table('correspondences', function (Blueprint $table) {
                $table->dropSoftDeletes();
            });
        }
    }
};
