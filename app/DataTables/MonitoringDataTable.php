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
            ->addIndexColumn()
            ->addColumn('Maps Datang', function (Kehadiran $kehadiran) {
                return view('admin.monitoring.maps_checkin', ['kehadiran' => $kehadiran]);
            })
            ->addColumn('Maps Pulang', function (Kehadiran $kehadiran) {
                return view('admin.monitoring.maps_checkout', ['kehadiran' => $kehadiran]);
            })
            ->editColumn('user_nik', function (Kehadiran $kehadiran) {
                return $kehadiran->user->nik;
            })
            ->editColumn('user_name', function (Kehadiran $kehadiran) {
                return $kehadiran->user->name;
            })
            ->filterColumn('user_nik', function ($query, $keyword) {
                $query->whereRaw('LOWER(users.nik) LIKE ?', ["%{$keyword}%"]);
            })
            ->filterColumn('user_name', function ($query, $keyword) {
                $query->whereRaw('LOWER(users.name) LIKE ?', ["%{$keyword}%"]);
            })
            ->editColumn('check_in_time', function (Kehadiran $kehadiran) {
                return Carbon::parse($kehadiran->check_in_time)->format('H:i');
            })->editColumn('check_out_time', function (Kehadiran $kehadiran) {
                return Carbon::parse($kehadiran->check_out_time)->format('H:i');
            })
            ->editColumn('check_in_photo', function (Kehadiran $kehadiran) {
                return '<img src="' . asset('img/' . $kehadiran->check_in_photo) . '"width="90px">';
            })
            ->setRowId('id')
            ->rawColumns(['check_in_photo']);
    }


    /**
     * Get the query source of dataTable.
     */
    public function query(Kehadiran $model): QueryBuilder
    {
        // Ambil nilai filter tanggal dari request
        $filterDate = request('work_date', \Carbon\Carbon::today()->toDateString());

        // Filter data kehadiran berdasarkan tanggal kerja (work_date)
        return $model->newQuery()
            ->join('users', 'users.id', '=', 'kehadiran.user_id') // Join dengan tabel users
            ->select('kehadiran.*', 'users.nik as user_nik', 'users.name as user_name')
            ->where('kehadiran.work_date', $filterDate); // Filter berdasarkan tanggal kerja
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
            //->dom('Bfrtip')
            ->orderBy([7, 'asc'])
            ->selectStyleSingle()
            ->buttons([]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::computed('DT_RowIndex')
                ->title('No.') // Ubah judul kolom menjadi "No."
                ->searchable(false)
                ->orderable(false)
                ->width(30)
                ->addClass('text-center')
                ->searchable(false),
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
            Column::make('user_nik')->title('NIK'),
            Column::make('user_name')->title('Nama Karyawan'),
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