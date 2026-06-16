<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AIConversation extends Model
{
    use HasFactory;

    protected $table = 'ai_conversations';

    protected $fillable = [
        'user_id',
        'conversation_type',
        'title',
        'agent_conversation_id',
        'total_tokens',
        'total_cost',
    ];

    protected $casts = [
        'total_tokens' => 'integer',
        'total_cost' => 'float',
    ];

    public const CONVERSATION_TYPES = [
        'general' => 'عام',
        'subject' => 'موضوع',
        'lesson' => 'درس',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(AIMessage::class, 'conversation_id');
    }

    public function getContext(): ?string
    {
        return null;
    }

    public function addMessage(string $role, string $content, array $metadata = []): AIMessage
    {
        return $this->messages()->create([
            'role' => $role,
            'content' => $content,
            'tokens_used' => $metadata['tokens_used'] ?? 0,
            'prompt_tokens' => $metadata['prompt_tokens'] ?? 0,
            'completion_tokens' => $metadata['completion_tokens'] ?? 0,
            'cost' => $metadata['cost'] ?? 0,
            'response_time' => $metadata['response_time'] ?? null,
        ]);
    }
}
