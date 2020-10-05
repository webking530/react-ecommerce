<?php

/**
 * Product Reports DataTable
 *
 * @package     Spiffy
 * @subpackage  DataTable
 * @category    Product Reports
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\ProductReports;
use Yajra\DataTables\Services\DataTable;

class ProductReportsDataTable extends DataTable
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
            ->of($query);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \ProductReports $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(ProductReports $model)
    {
        return $model->with(['users', 'products']);
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
            ['data' => 'users.full_name', 'name' => 'users.full_name', 'title' => 'Username','orderable' => false],
            ['data' => 'products.title', 'name' => 'products.title', 'title' => 'Product Name','orderable' => false],
            ['data' => 'products.user_name', 'name' => 'products.user_name', 'title' => 'Merchant Name','orderable' => false, 'searchable' => false],
        );
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'product_reports_' . date('YmdHis');
    }
}