<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'username', 'email', 'phone', 'password', 'bio', 'work', 'education', 'city', 'website', 'birthday', 'profile_photo', 'cover_photo', 'posts_audience', 'request_audience'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    // Relationships
    public function friendRequestsSent(): HasMany
    {
        return $this->hasMany(FriendRequest::class, 'sender_id');
    }

    public function friendRequestsReceived(): HasMany
    {
        return $this->hasMany(FriendRequest::class, 'receiver_id');
    }

    public function friends()
    {
        $friendsIds = $this->friendRequestsSent()
            ->where('status', 'accepted')
            ->pluck('receiver_id')
            ->merge($this->friendRequestsReceived()->where('status', 'accepted')->pluck('sender_id'));

        return User::whereIn('id', $friendsIds);
    }

    public function isFriendsWith(User $user): bool
    {
        return $this->friends()->where('id', $user->id)->exists();
    }

    /**
     * Determine whether this user shares at least one friend with the given user.
     */
    public function hasMutual(User $user): bool
    {
        return $this->friends()
            ->whereIn('id', $user->friends()->select('id'))
            ->exists();
    }

    public function peopleYouMayKnow()
    {
        $friendsIds = $this->friends()->pluck('id');
        $pendingRequestUserIds = $this->friendRequestsSent()
            ->where('status', 'pending')
            ->pluck('receiver_id')
            ->merge($this->friendRequestsReceived()->where('status', 'pending')->pluck('sender_id'));
        $excludedUserIds = $friendsIds->merge($pendingRequestUserIds);
        $untriedFriendIds = $friendsIds->shuffle();
        $friends = collect();
        $tries = 0;
        while ($friends->count() < 3 && $tries < 3 && $untriedFriendIds->isNotEmpty()) {
            $tries++;
            $randomFriend = User::find($untriedFriendIds->shift());
            $friends = $randomFriend->friends()->where('id', '!=', $this->id)->whereNotIn('id', $excludedUserIds)->limit(3)->get();
        }

        return $friends;
    }

    public function getFriendRequest(User $user)
    {
        return $this->friendRequestsSent()->where('receiver_id', $user->id)->first() ?? $this->friendRequestsReceived()->where('sender_id', $user->id)->first();
    }

    public function isRequestSentTo(User $user): bool
    {
        return $this->friendRequestsSent()->where('receiver_id', $user->id)->where('status', 'pending')->exists();
    }

    public function isRequestReceivedFrom(User $user): bool
    {
        return $this->friendRequestsReceived()->where('sender_id', $user->id)->where('status', 'pending')->exists();
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function likedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'likes')->withTimestamps();
    }

    public function sharedPosts(): BelongsToMany
    {
        return $this->belongsToMany(Post::class, 'post_shares')->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birthday' => 'date',
        ];
    }
}
