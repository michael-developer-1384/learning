<?php

namespace App\Services;

use App\Models\Filter;
use App\Models\FilterCriterion;

class FilterCriteriaService
{
    private function getModelClass($modelName) {
        return "App\\Models\\" . $modelName;
    }

    public function applyFilter($filterId)
    {
        $criteria = FilterCriterion::where('filter_id', $filterId)->orderBy('sort_order')->get();

        $query = \App\Models\User::query();

        foreach ($criteria as $criterion) {
            \Log::info('Criterion:', [
                'model' => $criterion->model,
                'column' => $criterion->column,
                'operator' => $criterion->operator,
                'value' => $criterion->value
            ]);

            $operator = $criterion->operator;
            $value = $criterion->value;

            // Wenn der Operator LIKE ist, fügen Sie die % Platzhalter hinzu
            if ($operator === 'LIKE') {
                $value = '%' . $value . '%';
            }

            if ($criterion->group_start) {
                $query->where(function ($subQuery) use ($criterion, $operator, $value) {
                    $this->applyCriterion($subQuery, $criterion, $operator, $value);
                });
            } else {
                if ($criterion->model === 'User') {
                    $this->applyCriterion($query, $criterion, $operator, $value);
                } else {
                    $relation = \Str::plural(strtolower($criterion->model));
                    $method = $criterion->chain_operator === 'AND' ? 'whereHas' : 'orWhereHas';
                    $query->$method($relation, function ($subQuery) use ($criterion, $operator, $value) {
                        $subQuery->where($criterion->column, $operator, $value);
                    });
                }
            }
        }

        \Log::info('SQL Query:', ['query' => $query->toSql(), 'bindings' => $query->getBindings()]);

        return $query->get();
    }

    private function applyCriterion($query, $criterion, $operator, $value)
    {
        // Für spezielle Operatoren wie IN und NOT IN müssen wir den Wert in ein Array umwandeln
        if (in_array($operator, ['IN', 'NOT IN'])) {
            $value = explode(',', $value);
        }

        // Anwenden des Verkettungsoperators (AND/OR)
        if ($criterion->chain_operator === 'AND') {
            $query->where($criterion->column, $operator, $value);
        } else {
            $query->orWhere($criterion->column, $operator, $value);
        }
    }
}
