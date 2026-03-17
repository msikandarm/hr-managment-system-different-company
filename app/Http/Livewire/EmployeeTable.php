<?php

namespace App\Http\Livewire;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class EmployeeTable extends DataTableComponent
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
            Column::make(__('Status'), 'hire_date')
                ->label(
                    fn ($row) => view('admin.employees.probation-badge')->withRow($row)
                ),
            Column::make(__('Created at'), 'created_at')
                ->sortable(),
            Column::make(__('Action'))
                ->label(
                    fn ($row) => view('admin.employees.actions')->withRow($row)
                ),
        ];
    }

    public function builder(): Builder
    {
        return Employee::query()
            ->with('department');
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setAdditionalSelects(['employees.id', 'employees.department_id', 'employees.hire_date'])
            ->setDefaultSort('id', 'desc')
            ->setPerPageAccepted([25, 50, 100])
            ->setTableAttributes([
                'default' => true,
                'class' => 'table-bordered table-lg',
            ])
            ->setThAttributes(function (Column $column) {
                if ($column->isField('created_at')) {
                    return [
                        'width' => '185px',
                    ];
                }

                if ($column->getTitle() === __('Status')) {
                    return [
                        'class' => 'text-center',
                        'width' => '140px',
                    ];
                }

                if ($column->isField('status')) {
                    return [
                        'width' => '110px',
                    ];
                }

                if ($column->getTitle() === __('Action')) {
                    return [
                        'class' => 'text-center',
                        'width' => '250px',
                    ];
                }

                return [];
            });
    }
}
