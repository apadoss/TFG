<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // ==================== CPUS ====================
        DB::unprepared('
            CREATE TRIGGER after_cpu_insert
                AFTER INSERT ON cpus
                FOR EACH ROW
                BEGIN
                    INSERT INTO price_history (component_id, component_type, vendor, price, created_at, updated_at)
                    VALUES (NEW.id, "App\\\\Models\\\\componentes\\\\Procesador", NEW.vendor, NEW.price, NOW(), NOW());
                END
        ');

        DB::unprepared('
            CREATE TRIGGER after_cpu_update
            AFTER UPDATE ON cpus
            FOR EACH ROW
            BEGIN
                -- Insertar en price_history siempre
                INSERT INTO price_history (component_type, component_id, vendor, price, recorded_at, created_at, updated_at)
                VALUES ("App\\\\Models\\\\componentes\\\\Procesador", NEW.id, NEW.vendor, NEW.price, NOW(), NOW(), NOW());
                
                -- Si el precio bajó, insertar en pending_notifications
                IF NEW.price < OLD.price THEN
                    -- Eliminar notificación pendiente anterior del mismo componente si existe
                    DELETE FROM pending_notifications 
                    WHERE component_type = "App\\\\Models\\\\componentes\\\\Procesador"
                      AND component_id = NEW.id 
                      AND vendor = NEW.vendor 
                      AND processed = 0;
                    
                    -- Insertar nueva notificación pendiente
                    INSERT INTO pending_notifications (component_type, component_id, vendor, old_price, new_price, created_at)
                    VALUES ("App\\\\Models\\\\componentes\\\\Procesador", NEW.id, NEW.vendor, OLD.price, NEW.price, NOW());
                END IF;
            END
        ');

        // ==================== TARJETAS GRAFICAS ====================
        DB::unprepared('
            CREATE TRIGGER after_graphic_card_insert
                AFTER INSERT ON graphic_cards
                FOR EACH ROW
                BEGIN
                    INSERT INTO price_history (component_id, component_type, vendor, price, created_at, updated_at)
                    VALUES (NEW.id, "App\\\\Models\\\\componentes\\\\TarjetaGrafica", NEW.vendor, NEW.price, NOW(), NOW());
                END
        ');

        DB::unprepared('
            CREATE TRIGGER after_graphic_card_update
                AFTER UPDATE ON graphic_cards
                FOR EACH ROW
                BEGIN
                    INSERT INTO price_history (component_id, component_type, vendor, price, created_at, updated_at)
                    VALUES (NEW.id, "App\\\\Models\\\\componentes\\\\TarjetaGrafica", NEW.vendor, NEW.price, NOW(), NOW());
                
                    IF NEW.price < OLD.price THEN
                        DELETE FROM pending_notifications 
                        WHERE component_type = "App\\\\Models\\\\componentes\\\\TarjetaGrafica"
                          AND component_id = NEW.id 
                          AND vendor = NEW.vendor 
                          AND processed = 0;

                        INSERT INTO pending_notifications (component_type, component_id, vendor, old_price, new_price, created_at)
                        VALUES ("App\\\\Models\\\\componentes\\\\TarjetaGrafica", NEW.id, NEW.vendor, OLD.price, NEW.price, NOW());
                    END IF;
                END
        ');

        // ==================== PLACAS BASE ====================
        DB::unprepared('
            CREATE TRIGGER after_motherboard_insert
                AFTER INSERT ON motherboards
                FOR EACH ROW
                BEGIN
                    INSERT INTO price_history (component_id, component_type, vendor, price, created_at, updated_at)
                    VALUES (NEW.id, "App\\\\Models\\\\componentes\\\\PlacasBase", NEW.vendor, NEW.price, NOW(), NOW());
                END
        ');

        DB::unprepared('
            CREATE TRIGGER after_motherboard_update
                AFTER UPDATE ON motherboards
                FOR EACH ROW
                BEGIN
                    INSERT INTO price_history (component_id, component_type, vendor, price, created_at, updated_at)
                    VALUES (NEW.id, "App\\\\Models\\\\componentes\\\\PlacasBase", NEW.vendor, NEW.price, NOW(), NOW());

                    IF NEW.price < OLD.price THEN
                        DELETE FROM pending_notifications 
                        WHERE component_type = "App\\\\Models\\\\componentes\\\\PlacasBase"
                          AND component_id = NEW.id 
                          AND vendor = NEW.vendor 
                          AND processed = 0;

                        INSERT INTO pending_notifications (component_type, component_id, vendor, old_price, new_price, created_at)
                        VALUES ("App\\\\Models\\\\componentes\\\\PlacasBase", NEW.id, NEW.vendor, OLD.price, NEW.price, NOW());
                    END IF;
                END
        ');

        // ==================== FUENTES DE ALIMENTACION ====================
        DB::unprepared('
            CREATE TRIGGER after_power_supply_insert
                AFTER INSERT ON power_supplies
                FOR EACH ROW
                BEGIN
                    INSERT INTO price_history (component_id, component_type, vendor, price, created_at, updated_at)
                    VALUES (NEW.id, "App\\\\Models\\\\componentes\\\\FuenteAlimentacion", NEW.vendor, NEW.price, NOW(), NOW());
                END
        ');

        DB::unprepared('
            CREATE TRIGGER after_power_supply_update
                AFTER UPDATE ON power_supplies
                FOR EACH ROW
                BEGIN
                    INSERT INTO price_history (component_id, component_type, vendor, price, created_at, updated_at)
                    VALUES (NEW.id, "App\\\\Models\\\\componentes\\\\FuenteAlimentacion", NEW.vendor, NEW.price, NOW(), NOW());

                    IF NEW.price < OLD.price THEN
                        DELETE FROM pending_notifications 
                        WHERE component_type = "App\\\\Models\\\\componentes\\\\FuenteAlimentacion"
                          AND component_id = NEW.id 
                          AND vendor = NEW.vendor 
                          AND processed = 0;

                        INSERT INTO pending_notifications (component_type, component_id, vendor, old_price, new_price, created_at)
                        VALUES ("App\\\\Models\\\\componentes\\\\PlacasBase", NEW.id, NEW.vendor, OLD.price, NEW.price, NOW());
                    END IF;
                END
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_cpu_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_cpu_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_graphic_card_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_graphic_card_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_motherboard_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_motherboard_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_power_supply_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_power_supply_update');
    }
};
