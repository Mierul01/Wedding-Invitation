<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wedding_settings', function (Blueprint $table) {
            $table->id();
            $table->string('groom_name');
            $table->string('bride_name');
            $table->string('groom_parents')->nullable();
            $table->string('bride_parents')->nullable();
            $table->dateTime('wedding_datetime');
            $table->string('ceremony_title')->default('Akad Nikah');
            $table->string('ceremony_time')->nullable();
            $table->string('ceremony_venue')->nullable();
            $table->text('ceremony_address')->nullable();
            $table->string('reception_title')->default('Majlis Resepsi');
            $table->string('reception_time')->nullable();
            $table->string('reception_venue')->nullable();
            $table->text('reception_address')->nullable();
            $table->string('venue_name')->nullable();
            $table->text('venue_address')->nullable();
            $table->string('map_embed_url')->nullable();
            $table->string('map_link')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('contact_phone')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_name_2')->nullable();
            $table->string('contact_phone_2')->nullable();
            $table->string('dress_code')->nullable();
            $table->text('gift_info')->nullable();
            $table->text('additional_notes')->nullable();
            $table->text('welcome_message')->nullable();
            $table->json('gallery_images')->nullable();
            $table->unsignedInteger('max_guests')->default(200);
            $table->boolean('rsvp_open')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wedding_settings');
    }
};
