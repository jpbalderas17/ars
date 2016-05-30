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


// asset_tag,serial_number,asset_name,model,asset_status,asset_status_label,location,category,eol,notes,order_number,check_out_date,expected_check_in_date,id,CONCAT(last_name,', ',first_name,' ',middle_name)as current_holder
$index=-1;

$columns = array(
    // array(
    //     'db'        => 'file_date',
    //     'dt'        => ++$index,
    //     'formatter' => function( $d, $row ) {
    //         // return "<button class='btn btn-sm btn-flat btn-success'  onclick='get_barcode({$row['id']})' title='View Barcode'><span class='fa fa-barcode'></span></button>";
    //         // return "<a href='barcode/download.php?id={$row['id']}' class='btn btn-sm btn-flat btn-success' title='Download Barcode'><span class='fa fa-barcode'></span></a>";
    //         $date=date_create($d);
    //         return htmlspecialchars($date->format("m/d/Y"));
    //     }
    // ),
    array( 'db' => 'transaction_date','dt' => ++$index ,'formatter'=>function($d,$row){
        $date=date_create($d);
        return htmlspecialchars($date->format("m/d/Y"));
    }),

    // array( 'db' => 'user','dt' => ++$index ),
    // array( 'db' => 'department','dt' => ++$index ),
    array( 'db' => 'payee','dt' => ++$index,'formatter'=> function ($d,$row){
            return htmlspecialchars($d);
        
    }),
    array( 'db' => 'amount','dt' => ++$index,'formatter'=>function($d,$row){
        return number_format($d,2);
    } ),
    array( 'db' => 'or_number','dt' => ++$index ,'formatter'=>function($d,$row){
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
    // array( 'db' => 'notes','dt' => ++$index ),
    array(
        'db'        => 'id',
        'dt'        => ++$index,
        'formatter' => function( $d, $row ) {

            $action_buttons="";
                    $action_buttons.="<a class='btn btn-flat btn-sm btn-brand' title='Edit Details' href='create_reimbursement.php?id={$d}'><span class='fa fa-pencil'></span></a> ";
                    $action_buttons.="<form method='post' action='delete_reimbursements.php' onsubmit='return confirm(\"Are you sure you want to delete this draft?\")' style='display:inline'>";
                    $action_buttons.="<input type='hidden' name='id' value='{$row['id']}'><input type='hidden' name='return_page' value='reimbursements_drafts.php'>";
                    $action_buttons.="<button class='btn btn-sm btn-danger btn-flat' value='leave' title='Delete Draft'><span class='fa fa-trash'></span></button></form>&nbsp;";
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
$whereAll=" status='Draft' AND is_deleted=0";
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
$complete_query="SELECT SQL_CALC_FOUND_ROWS `".implode("`, `", SSP::pluck($columns, 'db'))."`
             FROM `vw_reimbursements` {$where} {$order} {$limit}";
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