<?php

namespace App\Models;

use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'size'];

    public function add($users)
    {
        $this->guardAgainstTooManyMember($users);

        $method = $users instanceof User ? 'save' : 'saveMany';

        return $this->members()->$method($users);
    }

    public function remove($userIds)
    {
        if (is_array($userIds)) {
            return $this->removeMany($userIds);
        }

        $user = User::findOrFail($userIds);

        $user->team_id = null;
        $user->save();
    }

    public function removeMany($userIds = null)
    {
        User::where('team_id', $this->id)
            ->whereIn('id', $userIds)
            ->update([
                'team_id' => null,
            ]);
    }

    public function removeAll()
    {
        User::where('team_id', $this->id)
            ->update([
                'team_id' => null,
            ]);
    }

    public function members()
    {
        return $this->hasMany(User::class);
    }

    public function count()
    {
        return $this->members()->count();
    }

    protected function guardAgainstTooManyMember($users)
    {
        $numUsersToAdd = ($users instanceof User) ? 1 : $users->count();
        $newTeamCount = $this->count() + $numUsersToAdd;

        if ($newTeamCount > $this->size) {
            throw new Exception();
        }
    }
}
