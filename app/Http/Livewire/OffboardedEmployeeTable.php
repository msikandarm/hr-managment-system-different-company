<?php

namespace App\Http\Livewire;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class OffboardedEmployeeTable extends DataTableComponent
{
    public function columns(): array
    {
        return [
            Column::make(__('Name'), 'name')
                ->searchable()
                ->sortable(),
            Column::make(__('Email'), 'email')
                ->searchable()
                ->sortable(),
            Column::make(__('Department'), 'department.title')
                ->searchable()
                ->sortable(),
            Column::make(__('Position'), 'position')
                ->searchable()
                ->sortable(),
            Column::make(__('Offboarded At'), 'deleted_at')
                ->sortable(),
            Column::make(__('Action'))
                ->label(
                    fn ($row) => view('admin.employees.offboarded-actions')->withRow($row)
                ),
        ];
    }

    public function builder(): Builder
    {
        return Employee::onlyTrashed()
            ->with('department');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setAdditionalSelects(['employees.id', 'employees.department_id', 'employees.deleted_at', 'employees.hire_date'])
            ->setDefaultSort('deleted_at', 'desc')
            ->setPerPageAccepted([25, 50, 100])
            ->setTableAttributes([
                'default' => true,
                'class' => 'table-bordered table-lg',
            ])
            ->setThAttributes(function (Column $column) {
                if ($column->isField('deleted_at')) {
                    return [
                        'width' => '185px',
                    ];
                }

                if ($column->getTitle() === __('Action')) {
                    return [
                        'width' => '200px',
                        'class' => 'text-center',
                    ];
                }

                return [];
            });
    }
}
