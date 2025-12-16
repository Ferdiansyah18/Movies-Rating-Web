<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::create('reviews', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        
        // Identitas Item (ID dari API Eksternal)
        $table->string('item_id');   // ID Film/TV dari TMDB
        $table->string('item_type'); // 'movie' atau 'tv'

        // Data Snapshot (Disimpan agar tidak perlu request ke API lagi saat load review)
        $table->string('media_title');  // Judul Film
        $table->string('media_poster')->nullable(); // URL Poster
        $table->string('media_year')->nullable();   // Tahun Rilis

        // Isi Review
        $table->string('title'); // Judul Review
        $table->integer('rating'); // 1-10
        $table->text('comment');
        
        $table->timestamps();
    });
}

public function down()
{
    Schema::dropIfExists('reviews');
}
};
