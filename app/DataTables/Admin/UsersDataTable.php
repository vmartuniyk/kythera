<?php

namespace App\DataTables\Admin;

use Auth;
use Kythera\Models\User;
use Yajra\Datatables\Services\DataTable;

class UsersDataTable extends DataTable
{
    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $usersDT = $this->datatables->eloquent($this->query());

        if ($this->type == 'trashed') {
            $usersDT = $usersDT->onlyTrashed();
        } elseif ($this->type == 'unconfirmed' || $this->type == 'all') {
            $usersDT = $usersDT->withTrashed();
        }

        return $usersDT
            ->addColumn('action', function($row) {

                $user = \App\Models\User::withTrashed()->find($row->id);
                

                if ($user->hasRole('administrator')) {
                            $action = '<a href="users/' . $row->id . '/action/degrade" title="Remove Admin role"><i class="glyphicon glyphicon-tower" style="color:blue"></i></a>';

                            $action .= ($row->enable_email) ?
                            '&nbsp;&nbsp;<a href="users/' . $row->id . '/action/deactivate-admin-email" title="Disable Admin Emails"><i class="glyphicon glyphicon-envelope" style="color:red;"></i></a>'
                             : '&nbsp;&nbsp;<a href="users/' . $row->id . '/action/activate-admin-email" title="Enable  Admin Emails"><i class="glyphicon glyphicon-envelope" style="color:blue"></i></a>';
      					} else {
      						  $action = '<a href="users/' . $row->id . '/action/promote" title="Grant Admin role"><i class="glyphicon glyphicon-tower" style="color:silver"></i></a>';
      					}

                $action .= '&nbsp;&nbsp;';

                if ($row->confirmed) {
                    $action .= '<a href="users/' . $row->id . '/action/unconfirm" title="Unconfirm user (force them to reconfirm)"><i class="glyphicon glyphicon-user" style="color:blue"></i></a>';
                } else {
                    $action .= '<a href="users/' . $row->id . '/action/confirm" title="Confirm user"><i class="glyphicon glyphicon-user" style="color:silver"></i></a>';
                }

                $action .= '&nbsp;&nbsp;<a href="users/'. $row->id .'/edit" title="Edit User"><i class="glyphicon glyphicon-pencil create"></i></a>&nbsp;&nbsp;';

                if ($row->deleted_at) {
                    $action .= '<a href="users/' . $row->id . '/action/enable" title="Enable user"><i class="glyphicon glyphicon-check" style="color:silver"></i></a>';
                    if ($this->type = 'trashed') {
                        $action .= '&nbsp;&nbsp;<a href="users/' . $row->id . '/action/delete" title="DELETE user permanently!"><i class="glyphicon glyphicon-trash" style="color:red"></i></a>';
                    }
                } else {
                    $action .= '<a href="users/' . $row->id . '/action/disable" title="Disable user"><i class="glyphicon glyphicon-check" style="color:blue"></i></a>';
                }

                return $action;
            })
            ->make(true);
    }

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        if ($this->type == 'trashed') {
            $query = User::onlyTrashed();
        } elseif ($this->type == 'unconfirmed') {
            $query = User::withTrashed()->where('confirmed', 0);
        } elseif ($this->type == 'all') {
            $query = User::withTrashed();
        } elseif ($this->type == 'admin') {
            $query = \App\Models\User::whereRoleIs('administrator');
        } else { // implicit type == 'active'
            $query = User::query();
        }

        return $this->applyScopes($query);
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\Datatables\Html\Builder
     */
    public function html()
    {

        return $this->builder()
                    ->ajax('')
                    ->columns($this->getColumns())
                    ->addAction(['width' => '80px'])
                    ->parameters($this->getBuilderParameters());
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            'id',
            'lastname',
            'firstname',
            'email',
            'created_at',
        ];
    }

    /**
     * Get builder parameters.
     *
     * @return array
     */
    protected function getBuilderParameters()
    {
        return [
            'dom' => 'Bfrtlip',
            'buttons' => ['export', 'print', 'reset', 'reload'],
            'order' => [
                4, // here is the column number (4 = 'created_at')
                'desc'
            ],
            'lengthMenu' => [ [10, 20, 30, 40, 50, -1], [10, 20, 30, 40, 50, "All"] ],
            'pageLength' => 20,
            'pagingType' => 'full_numbers',
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'admin_usersdatatables_' . time();
    }
}
