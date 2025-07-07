<?php 

namespace Src\admin\user\infrastructure\repositories;


use App\Models\User as EloquentUser;
use Src\admin\user\domain\entities\User;
use Src\admin\user\domain\value_objects\UserName;
use Src\admin\user\domain\value_objects\UserEmail;
use Src\admin\user\domain\value_objects\UserPassword;
use Src\admin\user\domain\contracts\UserRepositoryInterface;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?User
    {
        $user = EloquentUser::find($id);
        if (!$user) {
            return null;
        }
        return new User(
            $user->id,
            new UserName($user->name),
            new UserEmail($user->email),
            new UserPassword($user->password)
        );
    }

    public function save(User $user): void
    {
       EloquentUser::updateOrCreate(
            ['id' => $user->id()],
            [
                'name' => $user->name()->value(),
                'email' => $user->email()->value(),
                'password' => bcrypt($user->password()->value())
            ]
        );
    }
}