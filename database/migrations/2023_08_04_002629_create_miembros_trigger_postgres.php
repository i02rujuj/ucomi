<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Date;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if(env('DB_CONNECTION')=="pgsql"){

            DB::unprepared('DROP TRIGGER IF EXISTS add_vigente_insert_miembros_gobierno ON miembros_gobierno');
            DB::unprepared('DROP TRIGGER IF EXISTS add_vigente_update_miembros_gobierno ON miembros_gobierno');
            DB::unprepared('DROP TRIGGER IF EXISTS add_activo_insert_miembros_gobierno ON miembros_gobierno');
            DB::unprepared('DROP TRIGGER IF EXISTS add_activo_str_update_miembros_gobierno ON miembros_gobierno');

            DB::unprepared('DROP TRIGGER IF EXISTS add_vigente_insert_miembros_junta ON miembros_junta');
            DB::unprepared('DROP TRIGGER IF EXISTS add_vigente_update_miembros_junta ON miembros_junta');
            DB::unprepared('DROP TRIGGER IF EXISTS add_activo_insert_miembros_junta ON miembros_junta');
            DB::unprepared('DROP TRIGGER IF EXISTS add_activo_str_update_miembros_junta ON miembros_junta');

            DB::unprepared('DROP TRIGGER IF EXISTS add_vigente_insert_miembros_comision ON miembros_comision');
            DB::unprepared('DROP TRIGGER IF EXISTS add_vigente_update_miembros_comision ON miembros_comision');
            DB::unprepared('DROP TRIGGER IF EXISTS add_activo_insert_miembros_comision ON miembros_comision');
            DB::unprepared('DROP TRIGGER IF EXISTS add_activo_str_update_miembros_comision ON miembros_comision');
    
            DB::unprepared("CREATE OR REPLACE FUNCTION add_vigente_insert_func() RETURNS TRIGGER AS $$
                            BEGIN
                                IF NEW.\"fechaCese\" IS NULL
                                THEN
                                    NEW.vigente := TO_CHAR('".Date::maxValue()."'::timestamp, 'yyyy-mm-dd hh24:mi:ss:ms')::text;
                                ELSE 
                                    NEW.vigente := (NEW.\"fechaCese\"::date + TO_CHAR('".Date::now()."'::timestamp, 'hh24:mi:ss')::time)::text;
                                END IF;
    
                                RETURN NEW;
                            END;
                            
                            $$ LANGUAGE plpgsql;
                            CREATE TRIGGER add_vigente_insert_miembros_gobierno BEFORE INSERT ON miembros_gobierno
                                FOR EACH ROW
                            EXECUTE FUNCTION add_vigente_insert_func();

                            CREATE TRIGGER add_vigente_insert_miembros_junta BEFORE INSERT ON miembros_junta
                                FOR EACH ROW
                            EXECUTE FUNCTION add_vigente_insert_func();

                            CREATE TRIGGER add_vigente_insert_miembros_comision BEFORE INSERT ON miembros_comision
                                FOR EACH ROW
                            EXECUTE FUNCTION add_vigente_insert_func();
                            
                            ");
    
           DB::unprepared("CREATE OR REPLACE FUNCTION add_vigente_update_func() RETURNS TRIGGER AS $$ 
                            BEGIN
                                IF NEW.\"fechaCese\" IS NULL
                                    THEN
                                        NEW.vigente := TO_CHAR('".Date::maxValue()."'::timestamp, 'yyyy-mm-dd hh24:mi:ss:ms')::text;
                                    ELSE
                                        NEW.vigente := (NEW.\"fechaCese\"::date + TO_CHAR('".Date::now()."'::timestamp, 'hh24:mi:ss')::time)::text;
                                    END IF;
    
                                    RETURN NEW;
                                END;
    
                                $$ LANGUAGE plpgsql;
    
                                CREATE TRIGGER add_vigente_update_miembros_gobierno BEFORE UPDATE ON miembros_gobierno
                                                        FOR EACH ROW
                                EXECUTE FUNCTION add_vigente_update_func();

                                CREATE TRIGGER add_vigente_update_miembros_junta BEFORE UPDATE ON miembros_junta
                                                        FOR EACH ROW
                                EXECUTE FUNCTION add_vigente_update_func();

                                CREATE TRIGGER add_vigente_update_miembros_comision BEFORE UPDATE ON miembros_comision
                                                        FOR EACH ROW
                                EXECUTE FUNCTION add_vigente_update_func();
                            ");
    
            DB::unprepared("CREATE OR REPLACE FUNCTION add_activo_insert_func() RETURNS TRIGGER AS $$ 
                            BEGIN
                                IF NEW.\"deleted_at\" IS NULL
                                THEN
                                    NEW.activo := TO_CHAR('".Date::maxValue()."'::timestamp, 'yyyy-mm-dd hh24:mi:ss:ms')::text;
                                ELSE
                                    NEW.activo := TO_CHAR(NEW.deleted_at::timestamp, 'yyyy-mm-dd hh24:mi:ss:ms')::text;
                                END IF;
    
                                RETURN NEW;
                            END;
    
                            $$ LANGUAGE plpgsql;
    
                            CREATE TRIGGER add_activo_insert_miembros_gobierno BEFORE INSERT ON miembros_gobierno
                                                    FOR EACH ROW
                            EXECUTE FUNCTION add_activo_insert_func();

                            CREATE TRIGGER add_activo_insert_miembros_junta BEFORE INSERT ON miembros_junta
                                                    FOR EACH ROW
                            EXECUTE FUNCTION add_activo_insert_func();

                            CREATE TRIGGER add_activo_insert_miembros_comision BEFORE INSERT ON miembros_comision
                                                    FOR EACH ROW
                            EXECUTE FUNCTION add_activo_insert_func();
                            ");
    
            DB::unprepared("CREATE OR REPLACE FUNCTION add_activo_str_update_func() RETURNS TRIGGER AS $$ 
                            BEGIN
                                IF NEW.\"deleted_at\" IS NULL
                                THEN
                                    NEW.activo := TO_CHAR('".Date::maxValue()."'::timestamp, 'yyyy-mm-dd hh24:mi:ss:ms')::text;
                                ELSE
                                    NEW.activo := TO_CHAR(NEW.deleted_at::timestamp, 'yyyy-mm-dd hh24:mi:ss:ms')::text;
                                END IF;
    
                                RETURN NEW;
                            END;
    
                            $$ LANGUAGE plpgsql;
    
                            CREATE TRIGGER add_activo_str_update_miembros_gobierno BEFORE UPDATE ON miembros_gobierno
                                                    FOR EACH ROW
                            EXECUTE FUNCTION add_activo_str_update_func();

                            CREATE TRIGGER add_activo_str_update_miembros_junta BEFORE UPDATE ON miembros_junta
                                                    FOR EACH ROW
                            EXECUTE FUNCTION add_activo_str_update_func();

                            CREATE TRIGGER add_activo_str_update_miembros_comision BEFORE UPDATE ON miembros_comision
                                                    FOR EACH ROW
                            EXECUTE FUNCTION add_activo_str_update_func();
                            ");
        } 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if(env('DB_CONNECTION')=="pgsql"){
            DB::unprepared('DROP TRIGGER IF EXISTS add_vigente_insert_miembros_gobierno ON miembros_gobierno');
            DB::unprepared('DROP TRIGGER IF EXISTS add_vigente_update_miembros_gobierno ON miembros_gobierno');
            DB::unprepared('DROP TRIGGER IF EXISTS add_activo_insert_miembros_gobierno ON miembros_gobierno');
            DB::unprepared('DROP TRIGGER IF EXISTS add_activo_str_update_miembros_gobierno ON miembros_gobierno');

            DB::unprepared('DROP TRIGGER IF EXISTS add_vigente_insert_miembros_junta ON miembros_junta');
            DB::unprepared('DROP TRIGGER IF EXISTS add_vigente_update_miembros_junta ON miembros_junta');
            DB::unprepared('DROP TRIGGER IF EXISTS add_activo_insert_miembros_junta ON miembros_junta');
            DB::unprepared('DROP TRIGGER IF EXISTS add_activo_str_update_miembros_junta ON miembros_junta');

            DB::unprepared('DROP TRIGGER IF EXISTS add_vigente_insert_miembros_comision ON miembros_comision');
            DB::unprepared('DROP TRIGGER IF EXISTS add_vigente_update_miembros_comision ON miembros_comision');
            DB::unprepared('DROP TRIGGER IF EXISTS add_activo_insert_miembros_comision ON miembros_comision');
            DB::unprepared('DROP TRIGGER IF EXISTS add_activo_str_update_miembros_comision ON miembros_comision');
        }
    }
};
