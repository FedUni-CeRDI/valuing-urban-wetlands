<?php

use App\Traits\ViewMigration;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    use ViewMigration;

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $views = [
            '2023_11_10_161500_ala_snipe_records_cleaned',
            '2023_11_10_162300_capad_greater_melbourne',
            '2023_11_10_162700_protection_status',
            '2023_11_10_163000_snipe_all_site_data_cleaned',
            '2023_11_10_163200_vluis_3857',
            '2023_11_10_163400_wetlands',
        ];

        self::runViews($views);
        self::refreshViews($views);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropAllViews();
    }
};
