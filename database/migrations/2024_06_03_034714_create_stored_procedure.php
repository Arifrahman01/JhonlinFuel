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
        DB::unprepared('
            CREATE PROCEDURE `sp_report_fuel_comsumtion`(
                IN `start_date` DATE,
                IN `end_date` DATE
            )
            BEGIN
                WITH cte AS (
                    SELECT
                        i.equipment_no,
                        i.trans_Date,
                        i.qty,
                        i.meter_value,
                        LAG(i.meter_value) OVER (PARTITION BY i.equipment_no ORDER BY i.trans_Date) AS prev_meter_value,
                        LAG(i.qty) OVER (PARTITION BY i.equipment_no ORDER BY i.trans_Date) AS prev_qty
                    FROM issues i
                    WHERE i.trans_Date BETWEEN start_date AND end_date AND i.deleted_at IS NULL AND i.posting_no IS NOT NULL
                ),
                calc AS (
                    SELECT
                        equipment_no,
                        (meter_value - prev_meter_value) AS distance,
                        (qty - prev_qty) AS fuel_used,
                        CASE 
                            WHEN (qty - prev_qty) > 0 THEN (meter_value - prev_meter_value) / (qty - prev_qty)
                            ELSE NULL
                        END AS km_per_liter
                    FROM cte
                    WHERE prev_meter_value IS NOT NULL AND (qty - prev_qty) > 0
                )
                SELECT
                    calc.equipment_no,
                    e.equipment_description,
                    SUM(distance) AS total_distance,
                    SUM(fuel_used) AS total_fuel_used,
                    CASE 
                        WHEN SUM(fuel_used) > 0 THEN SUM(distance) / SUM(fuel_used)
                        ELSE NULL
                    END AS avg_km_per_liter
                FROM calc
                JOIN equipments AS e ON e.equipment_no = calc.equipment_no AND e.deleted_at IS NULL 
                GROUP BY calc.equipment_no
                ORDER BY calc.equipment_no;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP PROCEDURE IF EXISTS sp_report_fuel_comsumtion');
    }
};
