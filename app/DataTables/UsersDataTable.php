<?php

/**
 * Users DataTable
 *
 * @package     Spiffy
 * @subpackage  DataTable
 * @category    Users
 * @author      Trioangle Product Team
 * @version     1.5
 * @link        http://trioangle.com
 */

namespace App\DataTables;

use App\Models\User;
use Yajra\DataTables\Services\DataTable;
use Auth;

class UsersDataTable extends DataTable
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
            ->addColumn('action', function ($users) {
                $edit = (Auth::guard('admin')->user()->can('update-users')) ? '<a href="'.url(ADMIN_URL.'/edit_user/'.$users->id).'" class="btn btn-xs btn-primary"><i class="glyphicon glyphicon-edit"></i></a>' : '';
                $delete = (Auth::guard('admin')->user()->can('delete-users')) ? '<a data-href="'.url(ADMIN_URL.'/delete_user/'.$users->id).'" class="btn btn-xs btn-primary" data-toggle="modal" data-target="#confirm-delete"><i class="glyphicon glyphicon-trash"></i></a>' : '';

                return $edit.'&nbsp;'.$delete;
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \User $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(User $model)
    {
        return $model->select('id','full_name','user_name','email','store_name','type','is_header','status');
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
                    ->addAction(["printable" => false])
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
			['data' => 'full_name', 'name' => 'full_name', 'title' => 'Name'],
			['data' => 'user_name', 'name' => 'user_name', 'title' => 'Username'],
			['data' => 'email', 'name' => 'email', 'title' => 'Email'],
			['data' => 'store_name', 'name' => 'store_name', 'title' => 'Store name'],
			['data' => 'type', 'name' => 'type', 'title' => 'Type'],
			['data' => 'status', 'name' => 'status', 'title' => 'Status'],
        );
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'users_' . date('YmdHis');
    }
}