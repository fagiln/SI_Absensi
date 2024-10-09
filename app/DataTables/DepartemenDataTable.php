<?php

namespace App\DataTables;

use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class DepartemenDataTable extends DataTable
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

            ->addColumn('action', function (Department $departemen) {
                return view('admin.departemen.action', ['departemen' => $departemen]);
            })
            ->editColumn('created_at', function (Department $departemen) {
                return Carbon::parse($departemen->created_at)->format('d F Y');
            })
            ->editColumn( 'updated_at', function (Department $departemen) {
                return Carbon::parse(time: $departemen->updated_at)->format('d F Y');

            })
            ->setRowId('id');
    }

    /**;
     * Get the query source of dataTable.
     */
    public function query(Department $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
            ->setTableId('departemen-table')
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->responsive(true)
            ->parameters([
                'responsive' => true,
                'autoWidth' => false,  // Untuk memastikan lebar kolom diatur secara otomatis
            ])
            //->dom('Bfrtip')
            ->orderBy([2, 'asc'])
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
            Column::make('nama_departemen'),
            Column::make('kode_departemen'),
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
        return 'Departemen_' . date('YmdHis');
    }
}
