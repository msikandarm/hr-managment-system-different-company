<?php

namespace App\Http\Livewire;

use App\Models\LeaveRequest;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class LeaveRequestTable extends DataTableComponent
{
    public function columns(): array
    {
        return [
            Column::make(__('Employee'), 'employee.name')
                ->searchable()
                ->sortable(),
            Column::make(__('Leave Type'), 'leaveType.title')
                ->searchable()
                ->sortable(),
            Column::make(__('Start Date'), 'start_date')
                ->sortable(),
            Column::make(__('End Date'), 'end_date')
                ->sortable(),
            Column::make(__('Days'), 'total_days')
                ->sortable(),
            Column::make(__('Remaining'), 'employee_id')
                ->label(
                    fn ($row) => view('admin.leave-requests.remaining-leaves')->withRow($row)
                ),
            Column::make(__('Status'), 'status')
                ->view('admin.leave-requests.status-badge'),
            Column::make(__('Created at'), 'created_at')
                ->sortable(),
            Column::make(__('Action'))
                ->label(
                    fn ($row) => view('admin.leave-requests.actions')->withRow($row)
                ),
        ];
    }

    public function builder(): Builder
    {
        return LeaveRequest::query()
            ->with(['employee', 'leaveType']);
    }

    public function configure(): void
    {
        $this->setPrimaryKey('id')
            ->setAdditionalSelects(['leave_requests.id', 'leave_requests.employee_id', 'leave_requests.leave_type_id'])
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

                if ($column->isField('start_date') || $column->isField('end_date')) {
                    return [
                        'width' => '120px',
                    ];
                }

                if ($column->isField('total_days')) {
                    return [
                        'width' => '80px',
                    ];
                }

                if ($column->getTitle() === __('Remaining')) {
                    return [
                        'class' => 'text-center',
                        'width' => '100px',
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
                        'width' => '200px',
                    ];
                }

                return [];
            });
    }
}
