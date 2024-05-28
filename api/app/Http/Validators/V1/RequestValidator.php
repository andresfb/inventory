<?php

namespace App\Http\Validators\V1;

use App\Rules\IncludeRelationshipRule;
use App\Traits\Fieldable;
use Exception;
use Illuminate\Http\Request;

abstract class RequestValidator
{
    use Fieldable;

    private bool $valid = false;

    protected int $userId;
    protected int $identifier = 0;
    protected int $perPage = 0;

    protected array $includes = [];
    protected array $validated = [];
    protected array $relationshipList = [];

    public function __construct(protected readonly Request $request)
    {
        $this->userId = $this->request->user()->id ?? auth()->id() ?? 0;
        $this->setRelationships();
    }

    abstract public function cacheKey(): string;

    abstract public function tagCacheKey(): string;

    abstract protected function getRules(): array;

    abstract protected function setRelationships(): void;

    public function userId(): int
    {
        return $this->userId;
    }

    public function identifier(): int
    {
        return $this->identifier;
    }

    public function setIdentifier(int $identifier): void
    {
        $this->identifier = $identifier;
        $this->validated['identifier'] = $identifier;
    }

    public function includes(): array
    {
        return $this->includes;
    }

    public function validated(): array
    {
        if ($this->valid) {
            return $this->validated;
        }

        $this->validate();

        return $this->validated;
    }

    public function perPage(): int
    {
        return $this->perPage;
    }

    protected function validate(): void
    {
        $rules = array_merge(
            $this->getBaseRules(),
            $this->getRules(),
        );

        $this->validated = $this->request->validate($rules);

        $this->valid = true;
        $this->validated['user_id'] = $this->userId;

        $this->loadIncludes();
        $this->loadPerPage();
    }

    protected function isValid(): void
    {
        if ($this->valid) {
            return;
        }

        throw new \RuntimeException('Validation failed');
    }

    protected function loadIncludes(): void
    {
        if (!array_key_exists($this->includeField(), $this->validated)) {
            return;
        }

        $this->includes = str($this->validated[$this->includeField()])->lower()
            ->replace(' ', '')
            ->explode(',')
            ->toArray();
    }

    protected function loadPerPage(): void
    {
        $this->perPage = (int) ($this->validated['per_page'] ?? 0);

        $options = config("constants.pagination.per_page_options");
        if (!$this->perPage || !in_array($this->perPage, $options, true)) {
            $this->perPage = (int) config("constants.pagination.per_page_default");
        }
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

    /**
     * @throws Exception
     */
    protected function baseCacheKey(string $uniqueId): string
    {
        $this->isValid();

        return md5(sprintf(
            '%s:%s:%s:%s',
            $uniqueId,
            json_encode($this->validated(), JSON_THROW_ON_ERROR),
            json_encode($this->includes(), JSON_THROW_ON_ERROR),
            $this->perPage(),
        ));
    }
}
