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
                    INSERT INTO price_history (component_id, component_type, vendor, price, created_at, updated_at)
                    VALUES (NEW.id, "App\\\\Models\\\\componentes\\\\Procesador", NEW.vendor, NEW.price, NOW(), NOW());
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
