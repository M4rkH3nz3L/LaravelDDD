<?php

namespace App\Infrastructure\Persistence\Eloquent\User;

use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Email;

class UserRepositoryEloquent implements UserRepositoryInterface
{
    public function __construct(
        private readonly UserModel $model,
    ) {}

    public function find(string $id): ?User
    {
        $model = $this->model->find($id);

        return $model ? $this->toDomain($model) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $model = $this->model->where('email', $email)->first();

        return $model ? $this->toDomain($model) : null;
    }

    public function all(): array
    {
        return $this->model->all()
            ->map(fn ($model) => $this->toDomain($model))
            ->toArray();
    }

    public function save(mixed $entity): User
    {
        if ($entity instanceof UserModel) {
            $entity->save();

            return $this->toDomain($entity);
        }

        if ($entity instanceof User) {
            $model = $this->model->updateOrCreate(
                ['id' => $entity->id()],
                [
                    'name' => $entity->name(),
                    'email' => $entity->email()->value(),
                ]
            );

            return $this->toDomain($model);
        }

        return $entity;
    }

    public function delete(string $id): bool
    {
        return $this->model->destroy($id) > 0;
    }

    private function toDomain(UserModel $model): User
    {
        return new User(
            id: $model->id,
            name: $model->name,
            email: new Email($model->email),
        );
    }
}
