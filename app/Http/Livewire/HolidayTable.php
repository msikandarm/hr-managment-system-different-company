<?php

namespace App\Http\Livewire;

use App\Models\Holiday;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class HolidayTable extends DataTableComponent
{
    public function columns(): array
    {
        return [
            Column::make(__('Title'), 'title')
                ->searchable()
                ->sortable(),
            Column::make(__('Date'), 'date')
                ->sortable(),
            Column::make(__('Status'), 'status')
                ->view('datatables.status-switcher'),
            Column::make(__('Created at'), 'created_at')
                ->sortable(),
            Column::make(__('Action'))
                ->label(
                    fn ($row) => view('admin.holidays.actions')->withRow($row)
                ),
        ];
    }

    public function builder(): Builder
    {
        return Holiday::query();
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setAdditionalSelects(['id'])
            ->setDefaultSort('date', 'asc')
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

                if ($column->isField('date')) {
                    return [
                        'width' => '150px',
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
