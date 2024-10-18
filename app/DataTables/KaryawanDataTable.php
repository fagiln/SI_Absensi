<?php

namespace App\DataTables;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class KaryawanDataTable extends DataTable
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
            // ->addColumn('No', function(){

            // })
            ->addIndexColumn()

            ->addColumn('action', function (User $user) {
                return view('admin.karyawan.action', ['user' => $user]);
            })
            ->editColumn('created_at', function (User $user) {
                return Carbon::parse($user->created_at)->translatedFormat('d F Y ');
            })->editColumn('updated_at', function (User $user) {
                return Carbon::parse($user->updated_at)->translatedFormat('d F Y ');
            })
            ->editColumn('department_id', function (User $user) {

                return $user->departemen->nama_departemen;
            })
            ->editColumn('avatar', function (User $user) {
                return '<img src="' . asset('storage/photos/' . $user->id . $user->username.'.png') . '" style="width:100px; height:100px;   object-fit: cover;">';
            })
            ->rawColumns(['action', 'avatar'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(User $model): QueryBuilder
    {
        return $model->newQuery()->where('role', '!=', 'admin');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('karyawan-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->responsive(true)
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,  // Untuk memastikan lebar kolom diatur secara otomatis
            ])
            //->dom('Bfrtip')
            ->orderBy([9, 'asc'])
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
            Column::make('nik'),
            Column::make('username'),
            Column::make('name'),
            Column::make('email'),
            Column::make('jabatan'),
            Column::make('department_id')->title('Departemen'),
            Column::make('no_hp'),
            Column::make('avatar'),
            Column::make('created_at'),
            Column::make('updated_at'),
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
        return 'Karyawan_' . date('YmdHis');
    }
}
