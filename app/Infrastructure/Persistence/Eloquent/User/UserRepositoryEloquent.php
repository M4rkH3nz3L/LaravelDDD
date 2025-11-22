<?php

namespace App\Infrastructure\Persistence\Eloquent\User;

use App\Domain\Shared\AggregateRoot;
use App\Domain\Shared\Contracts\DomainEventDispatcher;
use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Email;
use App\Infrastructure\Shared\EloquentRepository;
use Illuminate\Database\Eloquent\Model;
use ReflectionClass;

class UserRepositoryEloquent extends EloquentRepository implements UserRepositoryInterface
{
    public function __construct(
        UserModel $model,
        DomainEventDispatcher $eventDispatcher,
    ) {
        parent::__construct($model, $eventDispatcher);
    }

    public function find(string $id): ?User
    {
        return $this->findById($id);
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
        return $this->saveAggregate($entity);
    }

    public function delete(string $id): bool
    {
        return $this->deleteById($id);
    }

    protected function toDomain(Model $model): User
    {
        $reflection = new ReflectionClass(User::class);
        $constructor = $reflection->getConstructor();
        $constructor->setAccessible(true);

        $user = $reflection->newInstanceWithoutConstructor();
        $constructor->invoke($user, $model->id, $model->name, new Email($model->email));

        return $user;
    }

    protected function toModel(AggregateRoot $aggregate): Model
    {
        assert($aggregate instanceof User);

        return $this->model->updateOrCreate(
            ['id' => $aggregate->id()],
            [
                'name' => $aggregate->name(),
                'email' => $aggregate->email()->value(),
            ]
        );
    }
}
