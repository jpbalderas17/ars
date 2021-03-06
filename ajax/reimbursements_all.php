<?php
require_once("../support/config.php"); 
if(!isLoggedIn()){
        toLogin();
        die();
    }
if(!AllowUser(array(1,2,3))){
    redirect("index.php");
}


// var_dump($_GET);

 
// Table's primary key
$primaryKey = 'id';
 // SELECT 
 // r.id,
 // r.payee,
 // r.invoice_number,
 // r.goods_services,
 // r.description,
 // CONCAT(u.last_name,', ',u.first_name,' ',u.middle_name) as user,
 // d.name as department,
 // tt.name as tax_type,
 // ec.name  as expence_classification,
 // r.transaction_date,
 // r.file_date,
 // r.status,
 // r.is_deleted,
 // r.expense_capital,
 // r.amount,
 // r.status,
 // u.department_id,
 // r.user_id,
 // r.expense_classification_id
 // FROM
 // reimbursements r
 // JOIN 
 //    users u ON r.user_id=u.id
 // LEFT JOIN
 //    tax_types tt ON r.tax_type_id=tt.id
 // LEFT JOIN
 //    expense_classifications ec ON r.expense_classification_id=ec.id
 // LEFT JOIN departments d ON u.department_id=d.id



$index=-1;

$columns = array(
    array( 'db' => 'transaction_date','dt' => ++$index ,'formatter'=>function($d,$row){
        $date=date_create($d);
        return htmlspecialchars($date->format("m/d/Y"));
    }),
    array( 'db' => 'file_date','dt' => ++$index ,'formatter'=>function($d,$row){
        $date=date_create($d);
        return htmlspecialchars($date->format("m/d/Y"));
    }),
    //array( 'db' => 'serial_number','dt' => ++$index ),
    // array( 'db' => 'user','dt' => ++$index ),
    // array( 'db' => 'department','dt' => ++$index ),
    array( 'db' => 'payee','dt' => ++$index,'formatter'=> function ($d,$row){
            return htmlspecialchars($d);
        
    }),
    array( 'db' => 'amount','dt' => ++$index,'formatter'=>function($d,$row){
        return number_format($d,2);
    } ),
    array( 'db' => 'or_number','dt' => ++$index,'formatter'=> function ($d,$row){
            return htmlspecialchars($d); 
    }),
    array( 'db' => 'goods_services','dt' => ++$index,'formatter'=>function($d,$row){
        switch ($d) {
            case '1':
                return "Services";
                break;
            case '2':
                return "Goods";
                break;
            case '3':
                return "Goods/Services";
                break;
            default:
                # code...
                break;
        }
    }),
    
    array( 'db' => 'description','dt' => ++$index,'formatter'=>function($d,$row){
        return htmlspecialchars($d);
    }),
    array( 'db' => 'status','dt' => ++$index,'formatter'=>function($d,$row){
        return htmlspecialchars($d);
    }),
    // array( 'db' => 'notes','dt' => ++$index ),
    array(
        'db'        => 'id',
        'dt'        => ++$index,
        'formatter' => function( $d, $row ) {

            $action_buttons="";
                    $action_buttons.="<a class='btn btn-flat btn-sm btn-brand' title='View Details' href='view_details.php?id={$d}'><span class='fa fa-search'></span></a>";
            return $action_buttons;
        }
    )
    // ,
    // array( 'db' => 'asset_status','dt' => ++$index ),
    // array( 'db' => 'last_name','dt' => ++$index ),
    // array( 'db' => 'first_name','dt' => ++$index ),
    // array( 'db' => 'middle_name','dt' => ++$index ),
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
// if(!empty($_GET['department_id'])){
//     $dep_sql="u.department_id=:department_id";
//     $inputs['department_id']=$_GET['department_id'];
//     $filter_sql.=$dep_sql;
//     $bindings[]=array('key'=>'department_id','val'=>$_GET['department_id'],'type'=>0);
// }

// if(!empty($_GET['user_id'])){
//     $user_sql="u.id=:user_id";
//     $inputs['user_id']=$_GET['user_id'];
//     if(!empty($filter_sql)){
//         $filter_sql.=" AND ";
//     }
//     $bindings[]=array('key'=>'user_id','val'=>$_GET['user_id'],'type'=>0);
//     $filter_sql.=$user_sql;
// }



// if(!empty($dep_sql) || !empty($user_sql)){
//     $filter_sql=" AND user_id IN (SELECT id FROM users u WHERE {$filter_sql})";
// }
// else{
//     $filter_sql="";
// }


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
    $date_filter.=" AND transaction_date >= '".date_format($date_start,'Y-m-d')."'";
}

if(!empty($date_end)){
    $date_filter.=" AND transaction_date <= '".date_format($date_end,'Y-m-d')."  23:59:59'";
}

$filter_sql.=$date_filter;

if(!empty($_GET['start_date_file'])){
    $date_start_file=date_create($_GET['start_date_file']);
}
else{
    $date_start_file="";
}

if(!empty($_GET['end_date_file'])){
    $date_end_file=date_create($_GET['end_date_file']);
}
else{
    $date_end_file="";
}

$date_filter="";
if(!empty($date_start_file)){
    $date_filter.=" AND file_date >= '".date_format($date_start_file,'Y-m-d')."'";
}

if(!empty($date_end_file)){
    $date_filter.=" AND file_date <= '".date_format($date_end_file,'Y-m-d')."  23:59:59'";
}
$filter_sql.=$date_filter;
// var_dump($bindings);
if(!empty($_GET['status'])){
  switch ($_GET['status']) {
    case 'For Audit':
      $filter_sql.=" AND status='For Audit' ";
      break;
    case 'For Approval':
      $filter_sql.=" AND status='For Approval' ";
      break;
    
    default:
      $filter_sql.=" AND (status='For Approval' OR status='For Audit') ";
      break;
  }
}
else{
      $filter_sql.=" AND (status='For Approval' OR status='For Audit') ";
}
//             $bindings[]=array('key'=>'status','val'=>$_GET['status'],'type'=>0);
$whereAll=" is_deleted=0";
$whereAll.=" AND user_id=:user_id";
$bindings[]=array('key'=>'user_id','val'=>$_SESSION[WEBAPP]['user']['id'],'type'=>0);
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
/*
$complete_query="SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", SSP::pluck($columns, 'db'))."`,(SELECT notes FROM reimbursement_movement WHERE action_time='Returned' ORDER BY id DESC) as 'reason'
             FROM `vw_reimbursements` {$where} {$order} {$limit}";
*/



$complete_query="SELECT SQL_CALC_FOUND_ROWS `transaction_date`,`file_date`, `user`, `department`, `payee`, `amount`, `or_number`,`goods_services`, `description`, `id`,`status` FROM `vw_reimbursements` {$where} {$order} {$limit}";
            // echo $complete_query;
             //var_dump($bindings);
// echo $where;
// echo $complete_query;
// // var_dump($bindings);
// die;

$data=$con->myQuery($complete_query,$bindings)->fetchAll();
$recordsFiltered=$con->myQuery("SELECT FOUND_ROWS();")->fetchColumn();

$recordsTotal=$con->myQuery("SELECT COUNT(id) FROM `vw_reimbursements` {$where};",$bindings)->fetchColumn();


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