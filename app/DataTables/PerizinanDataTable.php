<?php

namespace App\DataTables;

use App\Models\Perizinan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PerizinanDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        Carbon::setLocale('id');

        return (new EloquentDataTable($query))
            ->addIndexColumn()
            ->addColumn('action', function (Perizinan $perizinan) {
                return view('admin.perizinan.action', ['perizinan' => $perizinan]);
            })->addColumn('bukti_path', function (Perizinan $perizinan) {
          
                    return view('admin.perizinan.view_bukti', ['perizinan' => $perizinan]);
    
            })
            ->editColumn('status', function (Perizinan $perizinan) {
                if ($perizinan->status == 'diterima') {
                    return '<span class="badge badge-success">Diterima</span>';
                } elseif ($perizinan->status == 'ditolak') {
                    return '<span class="badge badge-danger">Ditolak</span>';
                }
                return '<span class="badge badge-warning">Pending</span>';
            })
            ->filterColumn('user_nik', function ($query, $keyword) {
                $query->whereRaw('LOWER(users.nik) LIKE ?', ["%{$keyword}%"]);
            })
            ->editColumn('created_at', function (Perizinan $perizinan) {
                return Carbon::parse($perizinan->created_at)->translatedFormat('d F Y');
            })
            ->filterColumn('user_name', function ($query, $keyword) {
                $query->whereRaw('LOWER(users.name) LIKE ?', ["%{$keyword}%"]);
            })->filterColumn('user_jabatan', function ($query, $keyword) {
                $query->whereRaw('LOWER(users.jabatan) LIKE ?', ["%{$keyword}%"]);
            })
            ->setRowId('id')
            ->rawColumns(['status']);
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Perizinan $model): QueryBuilder
    {
        $filterDate = request('created_at', \Carbon\Carbon::today()->toDateString());
        $startDate = request('start_date');
        $filterStatus = request('status');
        return $model->newQuery()

            ->join('users', 'users.id', '=', 'perizinan.user_id') // Join dengan tabel users
            ->select('perizinan.*', 'users.nik as user_nik', 'users.name as user_name', 'users.jabatan as user_jabatan')
            ->when($filterDate, function ($query, $filterDate) {
                return $query->whereDate('perizinan.created_at', $filterDate);
            })
            ->when($startDate, function ($query, $startDate) {
                return $query->whereDate('perizinan.start_date', $startDate);
            })
            ->when($filterStatus, function ($query, $filterStatus) {
                return $query->where('perizinan.status', $filterStatus);
            })
        ;
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('perizinan-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            //->dom('Bfrtip')
            ->responsive(true)
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,
                'language' => [
                    'emptyTable' => 'Tidak ada data perizinan'
                ],  // Untuk memastikan lebar kolom diatur secara otomatis
            ])

            //->dom('Bfrtip')
            ->orderBy([11, 'dsc'])
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
                ->addClass('text-center'),

            Column::make('user_nik')->title('NIK'),
            Column::make('user_name')->title('Nama'),
            Column::make('user_jabatan')->title('Jabatan'),
            Column::make('start_date')->title('Awal cuti'),
            Column::make('end_date')->title('Hingga'),
            Column::make('reason')->title('Alasan'),
            Column::make('keterangan'),
            Column::make('bukti_path')->title('Bukti'),
            Column::make('status'),
            Column::make('keterangan_ditolak'),
            Column::make('created_at')->title('Dibuat pada'),

            Column::computed('action')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),


        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Perizinan_' . date('YmdHis');
    }
}
