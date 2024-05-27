<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Rules\IncludeRelationshipRule;
use App\Traits\ApiResponses;
use App\Traits\Fieldable;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;

abstract class ApiController extends Controller
{
    use AuthorizesRequests;
    use ApiResponses;
    use Fieldable;

    public function __construct(private readonly array $relationshipList)
    {
    }

    protected function getValues(Request $request): array
    {
        $values = $request->validate($this->getRules());
        $perPage = $this->getPerPageValue($values);

        if (array_key_exists($this->includeField(), $values)) {
            $values[$this->includeField()] = str($values[$this->includeField()])->lower()
                ->replace(' ', '')
                ->explode(',')
                ->toArray();
        }

        return [$values, $perPage];
    }

    protected function getRules(): array
    {
        return $this->getBaseRules();
    }

    protected function getPerPageValue(array $values): int
    {
        $perPage = (int) ($values['per_page'] ?? 0);

        $options = config("constants.pagination.per_page_options");
        if (!$perPage || !in_array($perPage, $options, true)) {
            $perPage = (int) config("constants.pagination.per_page_default");
        }

        return $perPage;
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
