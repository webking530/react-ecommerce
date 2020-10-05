<?php

/**
 * Blocked Users DataTable
 *
 * @package     Spiffy
 * @subpackage  DataTable
 * @category    Blocked Users
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\BlockUsers;
use Yajra\DataTables\Services\DataTable;

class BlockUsersDataTable extends DataTable
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
     * @param \BlockUsers $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(BlockUsers $model)
    {
        return $model->with(['users', 'blocked_users']);
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
            ['data' => 'users.full_name', 'name' => 'users.full_name', 'title' => 'Username','orderable' => false,],
            ['data' => 'blocked_users.full_name', 'name' => 'blocked_users.full_name', 'title' => 'Blocked Username','orderable' => false,],
        );
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'blocked_users_' . date('YmdHis');
    }
	
}