<?php

use App\Enums\JoinRequestStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('community_join_requests', function (Blueprint $table) {
            $table->id();

            // Basic identity information
            $table->string('name');
            $table->string('email')->index(); // not unique since invited users may re-request later
            $table->string('phone')->nullable()->index(); // not unique since invited users may re-request later
            $table->longText('other_means_of_contact')->nullable();

            $table->text('message')->nullable();

            // Status of the request or invitation
            $table->string('status')->default(JoinRequestStatus::PENDING->value);

            // Who initiated the record (null = user requested it)
            $table->foreignId('initiated_by')->nullable()->constrained('users')->nullOnDelete();

            // Approval and review tracking
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();

            // Token for invitation link flow
            $table->string('invitation_token', 64)->nullable()->unique();

            // Optional audit data (GDPR-conscious)
            $table->string('ip_address', 45)->nullable(); // collected only on self-request
            $table->string('user_agent')->nullable();     // collected only on self-request

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('community_join_requests');
    }
};
