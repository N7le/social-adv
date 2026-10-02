<?php

namespace App\View\Components;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\View\Component;

class PeopleYouMayKnow extends Component
{
    /**
     * @var Collection<int, array{user: User, mutualFriendsCount: int}>
     */
    public Collection $suggestions;

    public function __construct(public User $user)
    {
        $friendIds = $user->friends()->pluck('id');

        $this->suggestions = $user->peopleYouMayKnow()
            ->map(fn (User $suggestion): array => [
                'user' => $suggestion,
                'mutualFriendsCount' => $suggestion->friends()->whereIn('id', $friendIds)->count(),
            ]);
    }

    public function shouldRender(): bool
    {
        return $this->suggestions->isNotEmpty();
    }

    public function render(): View
    {
        return view('components.people-you-may-know');
    }
}
