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
        if(env('DB_CONNECTION')=="pgsql"){
            DB::unprepared('DROP TRIGGER IF EXISTS add_vigente_insert ON miembros_gobierno');
            DB::unprepared('DROP TRIGGER IF EXISTS add_vigente_update ON miembros_gobierno');
            DB::unprepared('DROP TRIGGER IF EXISTS add_deleted_at_str_insert ON miembros_gobierno');
            DB::unprepared('DROP TRIGGER IF EXISTS add_deleted_at_str_update ON miembros_gobierno');
    
            DB::unprepared("CREATE OR REPLACE FUNCTION add_vigente_insert_func() RETURNS TRIGGER AS $$
                            BEGIN
                                IF NEW.\"fechaCese\" IS NULL
                                THEN
                                    NEW.vigente := TO_CHAR('".Date::maxValue()."'::date, 'yyyy-mm-dd hh24:mi:ss:ms')::text;
                                ELSE 
                                    NEW.vigente := DATEADD(NEW.fechaCese,TO_CHAR(CURRENT_TIMESTAMP::timestamp, 'hh24:mi:ss:ms')::text * INTERVAL '1 second');
                                END IF;
    
                                RETURN NEW;
                            END;
                            
                            $$ LANGUAGE plpgsql;
                            CREATE TRIGGER add_vigente_insert BEFORE INSERT ON miembros_gobierno
                                FOR EACH ROW
                            EXECUTE FUNCTION add_vigente_insert_func();
                            
                            ");
    
           DB::unprepared("CREATE OR REPLACE FUNCTION add_vigente_update_func() RETURNS TRIGGER AS $$ 
                            BEGIN
                                IF NEW.\"fechaCese\" IS NULL
                                    THEN
                                        NEW.vigente := TO_CHAR('".Date::maxValue()."'::date, 'yyyy-mm-dd hh24:mi:ss:ms')::text;
                                    ELSE
                                        NEW.vigente := DATEADD(NEW.fechaCese,TO_CHAR(CURRENT_TIMESTAMP::timestamp, 'hh24:mi:ss:ms')::text * INTERVAL '1 second');
                                    END IF;
    
                                    RETURN NEW;
                                END;
    
                                $$ LANGUAGE plpgsql;
    
                                CREATE TRIGGER add_vigente_update BEFORE UPDATE ON miembros_gobierno
                                                        FOR EACH ROW
                                EXECUTE FUNCTION add_vigente_update_func();
                            ");
    
            DB::unprepared("CREATE OR REPLACE FUNCTION add_activo_insert_func() RETURNS TRIGGER AS $$ 
                            BEGIN
                                IF NEW.\"deleted_at\" IS NULL
                                THEN
                                    NEW.activo := TO_CHAR('".Date::maxValue()."'::date, 'yyyy-mm-dd hh24:mi:ss:ms')::text;
                                ELSE
                                    NEW.activo := DATEADD(NEW.deleted_at,TO_CHAR(CURRENT_TIMESTAMP::timestamp, 'hh24:mi:ss:ms')::text * INTERVAL '1 second');
                                END IF;
    
                                RETURN NEW;
                            END;
    
                            $$ LANGUAGE plpgsql;
    
                            CREATE TRIGGER add_activo_insert BEFORE INSERT ON miembros_gobierno
                                                    FOR EACH ROW
                            EXECUTE FUNCTION add_activo_insert_func();
                            ");
    
            DB::unprepared("CREATE OR REPLACE FUNCTION add_activo_str_update_func() RETURNS TRIGGER AS $$ 
                            BEGIN
                                IF NEW.\"deleted_at\" IS NULL
                                THEN
                                    NEW.activo := TO_CHAR('".Date::maxValue()."'::date, 'yyyy-mm-dd hh24:mi:ss:ms')::text;
                                ELSE
                                    NEW.activo := DATEADD(NEW.deleted_at,TO_CHAR(CURRENT_TIMESTAMP::timestamp, 'hh24:mi:ss:ms')::text * INTERVAL '1 second');
                                END IF;
    
                                RETURN NEW;
                            END;
    
                            $$ LANGUAGE plpgsql;
    
                            CREATE TRIGGER add_activo_str_update BEFORE UPDATE ON miembros_gobierno
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
            DB::unprepared('DROP TRIGGER IF EXISTS add_vigente_insert ON miembros_gobierno');
            DB::unprepared('DROP TRIGGER IF EXISTS add_vigente_update ON miembros_gobierno');
            DB::unprepared('DROP TRIGGER IF EXISTS add_deleted_at_str_insert ON miembros_gobierno');
            DB::unprepared('DROP TRIGGER IF EXISTS add_deleted_at_str_update ON miembros_gobierno');
        }
    }
};
