<?php

/**
 * Stores DataTable
 *
 * @package     Spiffy
 * @subpackage  DataTable
 * @category    Stores
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Services\DataTable;

class StoresDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->of($query)
            ->addColumn('featured', function ($users) {
                $class = ($users->featured == 'No') ? 'danger' : 'success';
                $url =  url(ADMIN_URL.'/store_update?type=featured&id='.$users->id );
                $featured = '<a href="'.$url.'" class="btn btn-xs btn-'.$class.'">'.$users->featured.'</a>';

                return $featured;
            })
            ->rawcolumns(['featured']);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return $model->select('id','user_name','store_name','featured')->where('type','merchant');
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    ->dom('lBfr<"table-responsive"t>ip')
                    ->orderBy(0)
                    ->buttons(
                        ['csv','excel', 'print', 'reset']
                    );
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return array(
            ['data' => 'id', 'name' => 'id', 'title' => 'Id'],
            ['data' => 'user_name', 'name' => 'user_name', 'title' => 'Username'],
            ['data' => 'store_name', 'name' => 'store_name', 'title' => 'Store name'],
            ['data' => 'featured', 'name' => 'featured', 'title' => 'Featured', 'orderable' => false, 'searchable' => false],
        );
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'stores_' . date('YmdHis');
    }
}