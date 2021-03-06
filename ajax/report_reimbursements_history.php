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


// asset_tag,serial_number,asset_name,model,asset_status,asset_status_label,location,category,eol,notes,order_number,check_out_date,expected_check_in_date,id,CONCAT(last_name,', ',first_name,' ',middle_name)as current_holder
$index=-1;

$columns = array(
    array( 'db' => 'transaction_date','dt' => ++$index ,'formatter'=>function($d,$row){
        $date=date_create($d);
        return htmlspecialchars($date->format("m/d/Y"));
    }),
    array(
        'db'        => 'file_date',
        'dt'        => ++$index,
        'formatter' => function( $d, $row ) {
            if(!empty($d) && $d<>'0000-00-00'){

                $date=date_create($d);
                return htmlspecialchars($date->format("m/d/Y"));
            }
            return "";
        }
    ),
    //array( 'db' => 'serial_number','dt' => ++$index ),
    array( 'db' => 'user','dt' => ++$index ),
    array( 'db' => 'department','dt' => ++$index ),
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
    array( 'db' => 'expense_classification','dt' => ++$index,'formatter'=>function($d,$row){
        return htmlspecialchars($d);
    }),
    array( 'db' => 'tax_type','dt' => ++$index,'formatter'=>function($d,$row){
        return htmlspecialchars($d);
    }),
    array( 'db' => 'status','dt' => ++$index,'formatter'=>function($d,$row){
        return htmlspecialchars($d);
    }),
    array(
        'db'        => 'id',
        'dt'        => ++$index,
        'formatter' => function( $d, $row ) {

            $action_buttons="";
                    $action_buttons.="<a class='btn btn-flat btn-sm btn-brand' title='View Details' href='view_history.php?id={$d}'><span class='fa fa-search'></span></a> ";
            return $action_buttons;
        }
    )
    ,
    array( 'db' => 'expense_classification_id','dt' => ++$index ),
    array( 'db' => 'tax_type_id','dt' => ++$index )
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
if(!empty($_GET['department_id'])){
    $dep_sql="u.department_id=:department_id";
    $inputs['department_id']=$_GET['department_id'];
    $filter_sql.=$dep_sql;
    $bindings[]=array('key'=>'department_id','val'=>$_GET['department_id'],'type'=>0);
}

if(!empty($_GET['user_id'])){
    $user_sql="u.id=:user_id";
    $inputs['user_id']=$_GET['user_id'];
    if(!empty($filter_sql)){
        $filter_sql.=" AND ";
    }
    $bindings[]=array('key'=>'user_id','val'=>$_GET['user_id'],'type'=>0);
    $filter_sql.=$user_sql;
}


if(!empty($dep_sql) || !empty($user_sql)){
    $filter_sql=" AND user_id IN (SELECT id FROM users u WHERE {$filter_sql})";
}
else{
    $filter_sql="";
}

if(!empty($_GET['expense_classification_id'])){
    $user_sql="expense_classification_id=:expense_classification_id";
    $inputs['expense_classification_id']=$_GET['expense_classification_id'];
    
        $filter_sql.=" AND ";
    
    $bindings[]=array('key'=>'expense_classification_id','val'=>$_GET['expense_classification_id'],'type'=>0);
    $filter_sql.=$user_sql;
}

if(!empty($_GET['tax_type_id'])){
    $user_sql="tax_type_id=:tax_type_id";
    $inputs['tax_type_id']=$_GET['tax_type_id'];
    
        $filter_sql.=" AND ";
    
    $bindings[]=array('key'=>'tax_type_id','val'=>$_GET['tax_type_id'],'type'=>0);
    $filter_sql.=$user_sql;
}

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

$whereAll=" is_deleted=0";

if(!empty($_GET['status'])){

  switch ($_GET['status']) {
    case 'For Audit':
      $filter_sql.=" AND status='For Audit' ";
      break;
    case 'For Approval':
      $filter_sql.=" AND status='For Approval' ";
      break;

    case 'Rejected':
      $filter_sql.=" AND status='Rejected' ";
      break;

    case 'Approved':
      $filter_sql.=" AND status='Approved' ";
      break;
    
    default:
      $filter_sql.=" AND (status <> 'Cancelled' AND status <> 'Draft') ";
      break;
  }
  
}
else{
      $filter_sql.=" AND (status <> 'Cancelled' AND status <> 'Draft') ";
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
             FROM `vw_reimbursements` {$where} {$order} {$limit}";
            // echo $complete_query;
             //var_dump($bindings);
// echo $where;
// echo $complete_query;
// var_dump($bindings);
// die;

$data=$con->myQuery($complete_query,$bindings)->fetchAll();
$recordsFiltered=$con->myQuery("SELECT FOUND_ROWS();")->fetchColumn();

$recordsTotal=$con->myQuery("SELECT COUNT(id) FROM `vw_reimbursements` {$where};",$bindings)->fetchColumn();


$json['draw']=isset ( $request['draw'] ) ?intval( $request['draw'] ) :0;
$json['recordsTotal']=$recordsFiltered;
$json['recordsFiltered']=$recordsFiltered;
$json['data']=SSP::data_output($columns,$data);

echo json_encode($json);

die;