<?php

namespace App\Validators\V1;

use App\Rules\IncludeRelationshipRule;
use App\Traits\Fieldable;
use Illuminate\Http\Request;

abstract class RequestValidator
{
    use Fieldable;

    protected int $userId;
    protected int $perPage = 0;

    protected array $includes = [];
    protected array $validated = [];
    protected array $relationshipList = [];

    public function __construct(protected readonly Request $request)
    {
        $this->userId = $this->request->user()->id ?? auth()->id() ?? 0;
    }

    abstract public function cacheKey(string $baseKey): string;

    abstract protected function getRules(): array;

    abstract protected function loadRelationships(): void;

    public function userId(): int
    {
        return $this->userId;
    }

    public function includes(): array
    {
        return $this->includes;
    }

    public function includesString(): string
    {
        return implode(',', $this->includes);
    }

    public function validate(): void
    {
        $this->loadRelationships();

        $rules = array_merge(
            $this->getBaseRules(),
            $this->getRules(),
        );

        $this->validated = $this->request->validate($rules);

        if (array_key_exists($this->includeField(), $this->validated )) {
            $this->includes = str($this->validated[$this->includeField()])->lower()
                ->replace(' ', '')
                ->explode(',')
                ->toArray();
        }

        $this->perPage();
    }

    public function validated(): array
    {
        return $this->validated;
    }

    public function perPage(): int
    {
        if (!empty($this->perPage)) {
            return $this->perPage;
        }

        $this->perPage = (int) ($this->validated['per_page'] ?? 0);

        $options = config("constants.pagination.per_page_options");
        if (!$this->perPage || !in_array($this->perPage, $options, true)) {
            $this->perPage = (int) config("constants.pagination.per_page_default");
        }

        return $this->perPage;
    }

    protected function getBaseRules(): array
    {
        return [
            'page' => ['nullable', 'integer'],
            'per_page' => ['nullable', 'integer'],
            $this->includeField() => [
                'nullable',
                'string',
                new IncludeRelationshipRule($this->relationshipList),
            ]
        ];
    }
}
