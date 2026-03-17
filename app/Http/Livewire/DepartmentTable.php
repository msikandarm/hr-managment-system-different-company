<?php

namespace App\Http\Livewire;

use App\Models\Department;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DepartmentTable extends DataTableComponent
{
    public function columns(): array
    {
        return [
            Column::make(__('Title'), 'title')
                ->searchable()
                ->sortable(),
            Column::make(__('Status'), 'status')
                ->view('datatables.status-switcher'),
            Column::make(__('Created at'), 'created_at')
                ->sortable(),
            Column::make(__('Action'))
                ->label(
                    fn ($row) => view('admin.departments.actions')->withRow($row)
                ),
        ];
    }

    public function builder(): Builder
    {
        return Department::query();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setAdditionalSelects(['id'])
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

                if ($column->isField('status')) {
                    return [
                        'width' => '110px',
                    ];
                }

                if ($column->getTitle() === __('Action')) {
                    return [
                        'class' => 'text-center',
                        'width' => '150px',
                    ];
                }

                return [];
            });
    }
}
