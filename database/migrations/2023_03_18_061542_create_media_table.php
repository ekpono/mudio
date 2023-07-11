<?php

use App\Enums\MediaVisibility;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('file_name');
            $table->string('title');
            $table->string('description');
            $table->ipAddress('uploaded_from_ip');
            $table->string('file_type')->comment('This determines if it is Audio or video');
            $table->string('path');
            $table->string('extension');
            $table->string('poster')->nullable();
            $table->string('source')->comment('This is the the service that handles the upload e.g s3, local etc');
            $table->enum('visibility', [
                MediaVisibility::PRIVATE->value,
                MediaVisibility::PUBLIC->value,
            ])->default(MediaVisibility::PUBLIC->value);
            $table->text('response')->nullable()->comment('If external service is used, the entire response that comes back is stored here');
            $table->foreignId('updated_by')->nullable();
            $table->foreignId('deleted_by')->nullable();
            $table->string('state')->nullable();
            $table->string('country')->nullable();
            $table->string('continent')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media');
    }
};
