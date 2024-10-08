<?php

namespace App\DataTables;

use App\Models\Kehadiran;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class MonitoringDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('Maps Datang', function (Kehadiran $kehadiran) {
                return view('admin.monitoring.maps_checkin', ['kehadiran' => $kehadiran]);
            })->addColumn('Maps Pulang', function (Kehadiran $kehadiran) {
                return view('admin.monitoring.maps_checkout', ['kehadiran' => $kehadiran]);
            })
            ->editColumn('NIK', function (Kehadiran $kehadiran) {
                return $kehadiran->user->nik;
            })->editColumn('Nama Karyawan', function (Kehadiran $kehadiran) {
                return $kehadiran->user->name;
            })
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Kehadiran $model): QueryBuilder
    {
        $filterDate = request('filter_date', Carbon::today()->toDateString());

        // Query data kehadiran berdasarkan filter tanggal
        return $model->where('work_date', $filterDate);
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('monitoring-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->responsive(true)
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,  // Untuk memastikan lebar kolom diatur secara otomatis
            ])
            ->selectStyleSingle()
            ->buttons([]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('Maps Datang')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
            Column::computed('Maps Pulang')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
            Column::make('NIK'),
            Column::make('Nama Karyawan'),
            Column::make('check_in_time')->title('Jam Datang'),
            Column::make('check_out_time')->title('Jam Pulang'),
            Column::make('check_in_photo')->title('Foto Datang'),
            Column::make('check_out_photo')->title('Foto Pulang'),
            Column::make('status')

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Monitoring_' . date('YmdHis');
    }
}
