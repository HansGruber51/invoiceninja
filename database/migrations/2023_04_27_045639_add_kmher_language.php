<?php

use App\Models\Language;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Language::unguard();

        $language = Language::find(38);

        if (! $language) {
            Language::create(['id' => 38, 'name' => 'Khmer', 'locale' => 'km_KH']);
        }

        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('enable_e_invoice');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};