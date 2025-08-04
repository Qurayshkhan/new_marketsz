<?php

use App\Models\User;
use App\Models\UserAddress;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('shipping_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()->constrained()->onDelete('cascade');
            $table->foreignIdFor(UserAddress::class)->nullable()->constrained()->onDelete('cascade');
            $table->string('preferred_ship_method')->nullable();
            $table->string('international_shipping_option')->nullable();
            $table->string('shipping_preference_option')->nullable();
            $table->string('packing_option')->nullable();
            $table->string('proforma_invoice_options')->nullable();
            $table->string('login_option')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('additional_email')->nullable();
            $table->string('maximum_weight_per_box')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_preferences');
    }
};
