<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class OAuthClient extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'oauth_clients';

    protected $fillable = [
        'application_id',
        'name',
        'client_id',
        'client_secret',
        'scopes',
        'is_active',
        'last_used_at'
    ];

    protected $casts = [
        'scopes' => 'array',
        'is_active' => 'boolean',
        'last_used_at' => 'datetime'
    ];

    protected $hidden = [
        'client_secret'
    ];

    /**
     * Generate a new OAuth client
     */
    public static function generate(string $name, array $scopes = ['*']): self
    {
        return self::create([
            'name' => $name,
            'client_id' => 'pdex_' . Str::random(32),
            'client_secret' => Hash::make(Str::random(64)),
            'scopes' => $scopes,
            'is_active' => true
        ]);
    }

    /**
     * Verify client credentials
     */
    public function verifySecret(string $secret): bool
    {
        return Hash::check($secret, $this->client_secret);
    }

    /**
     * Update last used timestamp
     */
    public function markAsUsed(): void
    {
        $this->update(['last_used_at' => now()]);
    }

    /**
     * Check if client has specific scope
     */
    public function hasScope(string $scope): bool
    {
        return in_array('*', $this->scopes) || in_array($scope, $this->scopes);
    }

    /**
     * Get the application that owns this OAuth client
     */
    public function application()
    {
        return $this->belongsTo(\App\Models\Application::class);
    }
}
