<?php

namespace App\Infrastructure\Shared;

use App\Domain\Shared\AggregateRoot;
use App\Domain\Shared\Contracts\DomainEventDispatcher;
use Illuminate\Database\Eloquent\Model;

abstract class EloquentRepository
{
    public function __construct(
        protected readonly Model $model,
        protected readonly DomainEventDispatcher $eventDispatcher,
    ) {}

    abstract protected function toDomain(Model $model): AggregateRoot;

    abstract protected function toModel(AggregateRoot $aggregate): Model;

    protected function saveAggregate(AggregateRoot $aggregate): AggregateRoot
    {
        $model = $this->toModel($aggregate);
        $model->save();

        if ($aggregate->hasDomainEvents()) {
            $this->eventDispatcher->dispatchAll($aggregate->pullDomainEvents());
        }

        return $this->toDomain($model);
    }

    protected function findById(string $id): ?AggregateRoot
    {
        $model = $this->model->find($id);

        return $model ? $this->toDomain($model) : null;
    }

    protected function deleteById(string $id): bool
    {
        return $this->model->destroy($id) > 0;
    }
}
