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

 
// Table's primary key
$primaryKey = 'id';
 
// Array of database columns which should be read and sent back to DataTables.
// The `db` parameter represents the column name in the database, while the `dt`
// parameter represents the DataTables column identifier. In this case simple
// indexes
// asset_tag,serial_number,asset_name,model,asset_status,asset_status_label,location,category,eol,notes,order_number,check_out_date,expected_check_in_date,id,CONCAT(last_name,', ',first_name,' ',middle_name)as current_holder
$index=-1;

$columns = array(
    array(
        'db'        => 'asset_tag',
        'dt'        => ++$index,
        'formatter' => function( $d, $row ) {
            return "<button class='btn btn-sm btn-flat btn-success'  onclick='get_barcode({$row['id']})' title='View Barcode'><span class='fa fa-barcode'></span></button>";
            // return "<a href='barcode/download.php?id={$row['id']}' class='btn btn-sm btn-flat btn-success' title='Download Barcode'><span class='fa fa-barcode'></span></a>";
        }
    ),
    array( 'db' => 'asset_tag','dt' => ++$index ,'formatter'=>function($d,$row){
        return "<a href='view_asset.php?id={$row['id']}' title='View Asset Details'>{$d}</a>";
    }),
    //array( 'db' => 'serial_number','dt' => ++$index ),
    array( 'db' => 'asset_name','dt' => ++$index ),
    array( 'db' => 'model','dt' => ++$index ),
    array( 'db' => 'asset_status_label','dt' => ++$index,'formatter'=> function ($d,$row){
        if($row['check_out_date']!="0000-00-00"){
            return "Deployed (".htmlspecialchars($row['last_name'].", ".$row['first_name']." ".$row['middle_name']).")";
        }
        else{
            return htmlspecialchars($d);
        }
    } ),
    array( 'db' => 'location','dt' => ++$index ),
    array( 'db' => 'category','dt' => ++$index ),
    // array( 'db' => 'EOL','dt' => ++$index,'formatter'=>function($d,$row){
    //     if($d!="0000-00-00" ){
    //         $date=new DateTime($d);
    //     return $date->format('m/d/Y');
    //     }
    //     else{
    //         return "";
    //     }
    // }),
    
    array( 'db' => 'order_number','dt' => ++$index),
    array( 'db' => 'check_out_date','dt' => ++$index,'formatter'=>function($d,$row){
        if($d!="0000-00-00"){
            $date=new DateTime($d);
        return $date->format('m/d/Y');
        }
        else{
            return "";
        }
    }),
    array( 'db' => 'expected_check_in_date','dt' => ++$index ,'formatter'=>function($d,$row){
        if($d!="0000-00-00"){
            $date=new DateTime($d);
        return $date->format('m/d/Y');
        }
        else{
            return "";
        }
    }),
    // array( 'db' => 'notes','dt' => ++$index ),
    array(
        'db'        => 'id',
        'dt'        => ++$index,
        'formatter' => function( $d, $row ) {

            $action_buttons="";
                
                    if($row['asset_status']==4):
                        if(empty($row['check_out_date']) || $row['check_out_date']=="0000-00-00"):
                    
                        $action_buttons.="<a class='btn btn-flat btn-sm btn-success' title='Check Out Asset' href='check_asset.php?id={$d}&type=out'><span class='fa fa-arrow-right'></span> Check Out</a> ";
                    
                        else:
                    
                        $action_buttons.="<a class='btn btn-flat btn-sm btn-success' title='Check In Asset' href='check_asset.php?id={$d}&type=in'><span class='fa fa-arrow-left'></span> Check In</a> ";
                    
                        endif;
                    endif;
                    $action_buttons.="<a class='btn btn-flat btn-sm btn-success' title='Edit Asset' href='frm_assets.php?id={$d}'><span class='fa fa-pencil'></span></a> ";
                    $action_buttons.="<a class='btn btn-flat btn-sm btn-danger' title='Delete Asset' href='delete.php?id={$d}&t=a' onclick='return confirm(\"Are you sure you want to delete this asset?.\")'><span class='fa fa-trash'></span></a> ";
            return $action_buttons;
        }
    ),
    array( 'db' => 'asset_status','dt' => ++$index ),
    array( 'db' => 'last_name','dt' => ++$index ),
    array( 'db' => 'first_name','dt' => ++$index ),
    array( 'db' => 'middle_name','dt' => ++$index ),
);
 
//var_dump($columns);
 
/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
 * If you just want to use the basic configuration for DataTables with PHP
 * server-side, there is no need to edit below this line.
 */
 
require( '../support/ssp.class.php' );
// echo json_encode(
//     SSP::simple( $_GET, $con, $table, $primaryKey, $columns )
// );

if($_GET['status']=="All"){   

    $json=SSP::jp_complex( $_GET, $con, "qry_assets", $primaryKey, $columns,NULL,array("is_deleted=0"));
    
}
else{
    if ($_GET['status']!='Deployed') {
        if($_GET['status']=='Deployable'){
            //is_deleted=0 AND asset_status_label=? AND qry_assets.user_id=0
            $json=SSP::jp_complex( $_GET, $con, "qry_assets", $primaryKey, $columns,NULL,array("is_deleted=0","asset_status_label = :status","qry_assets.user_id=0"));
        }
        else{
            //is_deleted=0 AND asset_status_label=?
            $json=SSP::jp_complex( $_GET, $con, "qry_assets", $primaryKey, $columns,NULL,array("is_deleted=0","asset_status_label = :status"));
        }
    }
    else{
        //is_deleted=0 AND check_out_date<>'0000-00-00'
        $json=SSP::jp_complex( $_GET, $con, "qry_assets", $primaryKey, $columns,NULL,array("is_deleted=0","check_out_date <> '0000-00-00'"));
    }
}

$limit = SSP::limit( $_GET, $columns );
$order = SSP::order( $_GET, $columns );

$where = SSP::filter( $_GET, $columns, $bindings );
$whereAll="";
$whereResult="";

$filter_sql="";
$dep_sql="";
$user_sql="";
$get_val="";
if(!empty($_GET['d_id'])){
    $dep_sql="u.department_id=:department_id";
    $inputs['department_id']=$_GET['d_id'];
    $filter_sql.=$dep_sql;
    $bindings[]=array('key'=>'department_id','val'=>$_GET['d_id'],'type'=>0);
}

if(!empty($_GET['u_id'])){
    $user_sql="u.id=:user_id";
    $inputs['user_id']=$_GET['u_id'];
    if(!empty($filter_sql)){
        $filter_sql.=" AND ";
    }
    $bindings[]=array('key'=>'user_id','val'=>$_GET['u_id'],'type'=>0);
    $filter_sql.=$user_sql;
}
if(!empty($dep_sql) || !empty($user_sql)){
    $filter_sql=" AND user_id IN (SELECT id FROM users u WHERE {$filter_sql})";
}
else{
    $filter_sql="";
}
// var_dump($bindings);
if($_GET['status']=="All"){   
    $whereAll=" is_deleted=0";
}
else{
    if ($_GET['status']!='Deployed') {
        if($_GET['status']=='Deployable'){
            $whereAll=" is_deleted=0 AND asset_status_label = :status AND qry_assets.user_id=0";
            $bindings[]=array('key'=>'status','val'=>$_GET['status'],'type'=>0);
        }
        else{
            //is_deleted=0 AND asset_status_label=?
            $whereAll=" is_deleted=0 AND asset_status_label = :status";
            $bindings[]=array('key'=>'status','val'=>$_GET['status'],'type'=>0);
        }
    }
    else{
        //is_deleted=0 AND check_out_date<>'0000-00-00'
        $whereAll=" is_deleted=0 AND check_out_date <> '0000-00-00'";
    }
}
$whereAll.=$filter_sql;
function jp_bind($bindings)
{
    $return_array=array();
    if ( is_array( $bindings ) ) {
            for ( $i=0, $ien=count($bindings) ; $i<$ien ; $i++ ) {
                //$binding = $bindings[$i];
                // $stmt->bindValue( $binding['key'], $binding['val'], $binding['type'] );
                $return_array[$bindings[$i]['key']]=$bindings[$i]['val'];
            }
        }

        return $return_array;
}
$where.= !empty($where) ? " AND ".$whereAll:"WHERE ".$whereAll;



$bindings=jp_bind($bindings);
$complete_query="SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", SSP::pluck($columns, 'db'))."`
             FROM `qry_assets` {$where} {$order} {$limit}";
            // echo $complete_query;
             //var_dump($bindings);

// echo $complete_query;
// var_dump($bindings);
// die;

$data=$con->myQuery($complete_query,$bindings)->fetchAll();
$recordsFiltered=$con->myQuery("SELECT FOUND_ROWS();")->fetchColumn();

$recordsTotal=$con->myQuery("SELECT COUNT(id) FROM `qry_assets` {$where};",$bindings)->fetchColumn();


$json['draw']=isset ( $request['draw'] ) ?intval( $request['draw'] ) :0;
$json['recordsTotal']=$recordsFiltered;
$json['recordsFiltered']=$recordsFiltered;
$json['data']=SSP::data_output($columns,$data);

echo json_encode($json);

// $resTotalLength = SSP::sql_exec( $db, $bindings,
//             "SELECT COUNT(`{$primaryKey}`)
//              FROM   `$table` ".
//             $whereAllSql
//         );

die;
        $bindings = array();
        //$db = self::db( $conn );
        // Build the SQL query string from the request
        $limit = SSP::limit( $_GET, $columns );
        $order = SSP::order( $_GET, $columns );
        $where = SSP::filter( $_GET, $columns, $bindings );
        // Main query to actually get the data
        echo $where;
        die;
        $inputs=array();
        foreach ($bindings as  $value) {
            $inputs[$value['key']]=$value['val'];
        }
        if(!empty($inputs)){
            $where="WHERE (CONCAT(first_name,' ',middle_name,' ',last_name) LIKE :binding_0 OR `username` LIKE :binding_1 OR `email` LIKE :binding_2 OR `contact_no` LIKE :binding_3 OR `employee_no` LIKE :binding_4 OR `location` LIKE :binding_5 OR `title` LIKE :binding_6 OR `department` LIKE :binding_7 OR `id` LIKE :binding_8)";
        }

        if(empty($_GET['status']) || $_GET['status']=='All'){
            $where="is_deleted=0";
        }
        else{
            if($_GET['status']!="Deployed"){
                if($_GET['status']=='Deployable'){
                    $where="is_deleted=0 AND asset_status_label=:status AND qry_assets.user_id=0";
                }
                else{
                    $where="is_deleted=0 AND asset_status_label=:status";
                }
            }
            else{             
                $where="is_deleted=0 AND check_out_date<>'0000-00-00'";
            }
        }


        $data=$con->myQuery("SELECT asset_tag,serial_number,asset_name,model,asset_status,asset_status_label,location,category,DATE_FORMAT(eol,'%m/%d/%Y') as eol,notes,order_number,DATE_FORMAT(check_out_date,'%m/%d/%Y') as check_out_date,expected_check_in_date,id,CONCAT(last_name,', ',first_name,' ',middle_name)as current_holder FROM qry_assets {$where} {$order} {$limit}",$inputs)->fetchAll(PDO::FETCH_ASSOC);
        
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