<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(env('DB_CONNECTION')=="mysql"){

            DB::unprepared('DROP TRIGGER IF EXISTS add_vigente_insert_miembros_junta');
            DB::unprepared('DROP TRIGGER IF EXISTS add_vigente_update_miembros_junta');
            DB::unprepared('DROP TRIGGER IF EXISTS add_deleted_at_str_insert_miembros_junta');
            DB::unprepared('DROP TRIGGER IF EXISTS add_deleted_at_str_update_miembros_junta');

            DB::unprepared("CREATE TRIGGER add_vigente_insert_miembros_junta BEFORE INSERT ON miembros_junta
                        FOR EACH ROW
                        BEGIN
                            IF NEW.fechaCese IS NULL
                            THEN
                                SET NEW.vigente = DATE_FORMAT('".Date::maxValue()."', '%Y-%m-%d %H:%i:%s.%f');
                            ELSE
                                SET NEW.vigente = ADDTIME(NEW.fechaCese, DATE_FORMAT(CURRENT_TIMESTAMP(6), '%H:%i:%s.%f'));
                            END IF;
                        END
                        ");

            DB::unprepared("CREATE TRIGGER add_vigente_update_miembros_junta BEFORE UPDATE ON miembros_junta
                        FOR EACH ROW
                        BEGIN
                            IF NEW.fechaCese IS NULL
                                THEN
                                    SET NEW.vigente = DATE_FORMAT('".Date::maxValue()."', '%Y-%m-%d %H:%i:%s.%f');
                                ELSE
                                    SET NEW.vigente = ADDTIME(NEW.fechaCese, DATE_FORMAT(CURRENT_TIMESTAMP(6), '%H:%i:%s.%f'));
                                END IF;
                            END
                        ");

            DB::unprepared("CREATE TRIGGER add_activo_insert_miembros_junta BEFORE INSERT ON miembros_junta
                        FOR EACH ROW
                        BEGIN
                            IF NEW.deleted_at IS NULL
                            THEN
                                SET NEW.activo = DATE_FORMAT('".Date::maxValue()."', '%Y-%m-%d %H:%i:%s.%f');
                            ELSE
                                SET NEW.activo = ADDTIME(NEW.deleted_at, DATE_FORMAT(CURRENT_TIMESTAMP(6), '.%f'));
                            END IF;
                        END
                        ");

            DB::unprepared("CREATE TRIGGER add_activo_str_update_miembros_junta BEFORE UPDATE ON miembros_junta
                        FOR EACH ROW
                        BEGIN
                            IF NEW.deleted_at IS NULL
                            THEN
                                SET NEW.activo = DATE_FORMAT('".Date::maxValue()."', '%Y-%m-%d %H:%i:%s.%f');
                            ELSE
                                SET NEW.activo = ADDTIME(NEW.deleted_at, DATE_FORMAT(CURRENT_TIMESTAMP(6), '.%f'));
                            END IF;
                        END
                        ");
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(env('DB_CONNECTION')=="mysql"){
            DB::unprepared('DROP TRIGGER IF EXISTS add_vigente_insert_miembros_junta');
            DB::unprepared('DROP TRIGGER IF EXISTS add_vigente_update_miembros_junta');
            DB::unprepared('DROP TRIGGER IF EXISTS add_deleted_at_str_insert_miembros_junta');
            DB::unprepared('DROP TRIGGER IF EXISTS add_deleted_at_str_update_miembros_junta');
        }
    }
};
