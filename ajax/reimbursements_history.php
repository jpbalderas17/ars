<?php
require_once("../support/config.php"); 
if(!isLoggedIn()){
        toLogin();
        die();
    }
if(!AllowUser(array(1,2))){
    redirect("index.php");
}


// var_dump($_GET);
 
// Table's primary key
$primaryKey = 'id';

$index=-1;

$columns = array(
 
    array( 'db' => 'action_time','dt' => ++$index ,'formatter'=>function($d,$row){
        $date=date_create($d);
        return htmlspecialchars($date->format("m/d/Y h:i A"));
    }),
    array( 'db' => 'user','dt' => ++$index,'formatter'=> function ($d,$row){
            return htmlspecialchars($d);
    }),
    array( 'db' => 'action','dt' => ++$index ,'formatter'=>function($d,$row){
        return htmlspecialchars($d);
    }),
    array( 'db' => 'notes','dt' => ++$index,'formatter'=>function($d,$row){
        return htmlspecialchars($d);
    }),
    array( 'db' => 'reimbursement_id','dt' => ++$index ),
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


$limit = SSP::limit( $_GET, $columns );
$order = SSP::order( $_GET, $columns );

$where = SSP::filter( $_GET, $columns, $bindings );
$whereAll="";
$whereResult="";

$filter_sql="";
$dep_sql="";
$user_sql="";
$get_val="";



if(!empty($_GET['start_date'])){
    $date_start=date_create($_GET['start_date']);
}
else{
    $date_start="";
}

if(!empty($_GET['end_date'])){
    $date_end=date_create($_GET['end_date']);
}
else{
    $date_end="";
}

$date_filter="";
if(!empty($date_start)){
    $date_filter.=" AND action_time >= '".date_format($date_start,'Y-m-d')."'";
}

if(!empty($date_end)){
    $date_filter.=" AND action_time <= '".date_format($date_end,'Y-m-d')."  23:59:59'";
}

$filter_sql.=$date_filter;
// var_dump($bindings);
// if($_GET['status']=="All"){   
//     $whereAll=" is_deleted=0";
// }
// else{
//     if ($_GET['status']!='Deployed') {
//         if($_GET['status']=='Deployable'){
//             $whereAll=" is_deleted=0 AND asset_status_label = :status AND qry_assets.user_id=0";
//             $bindings[]=array('key'=>'status','val'=>$_GET['status'],'type'=>0);
//         }
//         else{
//             //is_deleted=0 AND asset_status_label=?
//             $whereAll=" is_deleted=0 AND asset_status_label = :status";
//             $bindings[]=array('key'=>'status','val'=>$_GET['status'],'type'=>0);
//         }
//     }
//     else{
//         //is_deleted=0 AND check_out_date<>'0000-00-00'
//         $whereAll=" is_deleted=0 AND check_out_date <> '0000-00-00'";
//     }
// }
//             $bindings[]=array('key'=>'status','val'=>$_GET['status'],'type'=>0);
$whereAll="";
if(!empty($_GET['r_id'])){

$whereAll.=" reimbursement_id=:reimbursement_id";
$bindings[]=array('key'=>'reimbursement_id','val'=>$_GET['r_id'],'type'=>0);

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
if(!empty($whereAll)){
    $where.= !empty($where) ? " AND ".$whereAll:"WHERE ".$whereAll;
}



$bindings=jp_bind($bindings);
$complete_query="SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", SSP::pluck($columns, 'db'))."`
             FROM `vw_reimbursement_movement` {$where} {$order} {$limit}";
            // echo $complete_query;
             //var_dump($bindings);
// echo $where;
// echo $complete_query;
// // var_dump($bindings);
// die;

$data=$con->myQuery($complete_query,$bindings)->fetchAll();
$recordsFiltered=$con->myQuery("SELECT FOUND_ROWS();")->fetchColumn();

$recordsTotal=$con->myQuery("SELECT COUNT(id) FROM `vw_reimbursement_movement` {$where};",$bindings)->fetchColumn();


$json['draw']=isset ( $request['draw'] ) ?intval( $request['draw'] ) :0;
$json['recordsTotal']=$recordsFiltered;
$json['recordsFiltered']=$recordsFiltered;
$json['data']=SSP::data_output($columns,$data);

echo json_encode($json);
die;