<?php
require_once("../support/config.php"); 
if(!isLoggedIn()){
        toLogin();
        die();
    }
/*
 * DataTables example server-side processing script.
 *
 * Please note that this script is intentionally extremely simply to show how
 * server-side processing can be implemented, and probably shouldn't be used as
 * the basis for a large complex system. It is suitable for simple use cases as
 * for learning.
 *
 * See http://datatables.net/usage/server-side for full details on the server-
 * side processing requirements of DataTables.
 *
 * @license MIT - http://datatables.net/license_mit
 */
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * Easy set variables
 */
 
// DB table to use
$table = 'qry_users';
 
// Table's primary key
$primaryKey = 'id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
$columns = array(
    // array( 'db' => 'first_name', 'dt' => 0 ),
    array(
        'db'        => 'name',
        'dt'        => 0,
        'formatter' => function( $d, $row ) {
            
            return "<a href='view_user.php?id={$row['id']}'>". htmlspecialchars($d)."</a>";
        }
    ),
    array( 'db' => 'username',  'dt' => 1 ),
    array( 'db' => 'email',   'dt' => 2 ),
    array( 'db' => 'contact_no',     'dt' => 3 ),
    array( 'db' => 'employee_no',     'dt' => 4 ),
    array( 'db' => 'title',     'dt' => 5 ),
    array( 'db' => 'department',     'dt' => 6 ),
    array(
        'db'        => 'id',
        'dt'        => 7,
        'formatter' => function( $d, $row ) {
            $action_buttons="";
            if($row['is_active']==1):

            $action_buttons.="<a class='btn btn-flat btn-sm btn-brand' href='activate.php?id={$d}' onclick='return confirm(\"Are you sure you want to deactivate this user?\")'><span class='fa fa-lock' ></span> Deactivate</a>";
            else:
            $action_buttons.="<a class='btn btn-flat btn-sm btn-brand' href='activate.php?id={$d}' onclick='return confirm(\"Are you sure you want to activate this user?\")'><span class='fa fa-unlock' ></span> Activate</a>";
            endif;
            $action_buttons.=" <a class='btn btn-flat btn-sm btn-brand' href='frm_users.php?id={$d}'><span class='fa fa-pencil'></span></a> <a class='btn btn-flat btn-sm btn-danger' href='delete.php?id={$d}&t=u' onclick='return confirm(\"Are you sure you want to delete this user?\")'><span class='fa fa-trash'></span></a>";
            return $action_buttons;
        }
    )
    // array(
    //     'db'        => 'start_date',
    //     'dt'        => 4,
    //     'formatter' => function( $d, $row ) {
    //         return date( 'jS M y', strtotime($d));
    //     }
    // ),
    // array(
    //     'db'        => 'salary',
    //     'dt'        => 5,
    //     'formatter' => function( $d, $row ) {
    //         return '$'.number_format($d);
    //     }
    // )
);
 
// SQL server connection information
 
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require( '../support/ssp.class.php' );
// echo json_encode(
//     SSP::simple( $_GET, $con, $table, $primaryKey, $columns )
// );


        $bindings = array();
        //$db = self::db( $conn );
        // Build the SQL query string from the request
        $limit = SSP::limit( $_GET, $columns );
        $order = SSP::order( $_GET, $columns );
        $where = SSP::filter( $_GET, $columns, $bindings );
        // Main query to actually get the data

  
        $inputs=array();
        foreach ($bindings as  $value) {
            $inputs[$value['key']]=$value['val'];
        }
        if(!empty($inputs)){
            $where="WHERE (CONCAT(first_name,' ',middle_name,' ',last_name) LIKE :binding_0 OR `username` LIKE :binding_1 OR `email` LIKE :binding_2 OR `contact_no` LIKE :binding_3 OR `employee_no` LIKE :binding_4 OR `location` LIKE :binding_5 OR `title` LIKE :binding_6 OR `department` LIKE :binding_7 OR `id` LIKE :binding_8)";
        }
        $data=$con->myQuery("SELECT CONCAT(first_name,' ',middle_name,' ',last_name) as name,username,email,contact_no,employee_no,title,department,id,is_active FROM qry_users {$where} {$order} {$limit}",$inputs)->fetchAll(PDO::FETCH_ASSOC);
        
        //echo "SELECT CONCAT(first_name,' ',middle_name,' ',last_name) as name,username,email,contact_no,employee_no,location,title,department,id FROM qry_users {$where} {$order} {$limit}";
        // var_dump($bindings);
        // Data set length after filtering
        $resFilterLength = count($data);
        $recordsFiltered = $resFilterLength;
        // Total data set length
        $recordsTotal = $con->myQuery("SELECT COUNT(id) FROM qry_users")->fetchColumn(PDO::FETCH_ASSOC);
        /*
         * Output
         */
        echo json_encode( array(
            "draw"            => isset ( $_GET['draw'] ) ?
                intval( $_GET['draw'] ) :
                0,
            "recordsTotal"    => intval( $recordsTotal ),
            "recordsFiltered" => intval( $recordsFiltered ),
            "data"            => SSP::data_output( $columns, $data )
        ));