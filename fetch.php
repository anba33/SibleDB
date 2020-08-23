<?php
//fetch.php
$connect = mysqli_connect("localhost", "rootas", "rootas", "import_excel");
$column = array("kartotuvai.Cele", "kartotuvai.Pavadimimas", "kartotuvai.Kodas", "kartotuvai.Scala","kartotuvai.Paleista", "kartotuvai.Abonentai", "kartotuvai.Tipas");
$query = "
 SELECT * FROM kartotuvai  
";
$query .= " WHERE ";
if(isset($_POST["is_category"]))
{
 $query .= "kartotuvai = '".$_POST["is_category"]."' AND ";
}
if(isset($_POST["search"]["value"]))
{
 $query .= '(kartotuvai.Cele LIKE "%'.$_POST["search"]["value"].'%" ';
 $query .= 'OR kartotuvai.Pavadimimas LIKE "%'.$_POST["search"]["value"].'%" ';
 $query .= 'OR kartotuvai.Kodas LIKE "%'.$_POST["search"]["value"].'%" ';
 $query .= 'OR kartotuvai.Scala LIKE "%'.$_POST["search"]["value"].'%") ';
}

if(isset($_POST["order"]))
{
 $query .= 'ORDER BY '.$column[$_POST['order']['0']['column']].' '.$_POST['order']['0']['dir'].' ';
}
else
{
 $query .= 'ORDER BY Paleista DESC ';
}

$query1 = '';

if($_POST["length"] != 1)
{
 $query1 .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$number_filter_row = mysqli_num_rows(mysqli_query($connect, $query));

$result = mysqli_query($connect, $query . $query1);

$data = array();

while($row = mysqli_fetch_array($result))
{
 $sub_array = array();
 $sub_array[] = $row["Cele"];
 $sub_array[] = $row["Pavadimimas"];
 $sub_array[] = $row["Kodas"];
 $sub_array[] = $row["Scala"];
 $sub_array[] = $row["Paleista"];
 $sub_array[] = $row["Abonentai"];
 $sub_array[] = $row["Tipas"];
 $data[] = $sub_array;
}

function get_all_data($connect)
{
 $query = "SELECT * FROM kartotuvai";
 $result = mysqli_query($connect, $query);
 return mysqli_num_rows($result);
}

$output = array(
 "draw"    => intval($_POST["draw"]),
 "recordsTotal"  =>  get_all_data($connect),
 "recordsFiltered" => $number_filter_row,
 "data"    => $data
);

echo json_encode($output);

?>